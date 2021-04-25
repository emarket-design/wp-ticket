<?php
/**
 * Query Filter Functions
 *
 * @package WP_TICKET_COM
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
add_filter('posts_request', 'wp_ticket_com_posts_request', 99, 2);
add_filter('post_limits', 'wp_ticket_com_post_limits', 99, 2);
add_filter('posts_orderby', 'wp_ticket_com_posts_orderby', 99, 2);
/**
 * Change limit for author archive before wp_query is processed
 *
 * @since WPAS 4.8
 * @param string $input
 *
 * @return string $input
 */
function wp_ticket_com_post_limits($input, $query) {
	if (!is_admin() && $query->is_main_query() && (is_author() || is_search())) {
		global $wp_ticket_com_limit;
		$wp_ticket_com_limit = $input;
	}
	return $input;
}
/**
 * Change orderby for author archive before wp_query is processed
 *
 * @since WPAS 4.8
 * @param string $input
 *
 * @return string $input
 */
function wp_ticket_com_posts_orderby($input, $query) {
	$set_types = emd_find_limitby('frontend', 'wp_ticket_com');
	if (!is_admin() && $query->is_main_query() && (is_author() || (is_search() && !empty($set_types)))) {
		global $wpdb;
		global $wp_ticket_com_orderby;
		$input = str_replace($wpdb->posts . ".", "", $input);
		$wp_ticket_com_orderby = $input;
		return '';
	}
	return $input;
}
/**
 * Change request for author archive before wp_query is processed
 *
 * @since WPAS 4.8
 * @param string $input
 *
 * @return string $input
 */
function wp_ticket_com_posts_request($input, $query) {
	global $wpdb;
	if (!is_admin() && $query->is_main_query() && is_search()) {
		$input = emd_author_search_results('wp_ticket_com', $input, $query, 'search');
	} elseif (!is_admin() && $query->is_main_query() && is_author()) {
		$input = emd_author_search_results('wp_ticket_com', $input, $query, 'author');
	}
	return $input;
}
/**
 * Change query parameters before wp_query is processed
 *
 * @since WPAS 4.0
 * @param object $query
 *
 * @return object $query
 */
function wp_ticket_com_query_filters($query) {
	if (!is_admin() && $query->is_main_query()) {
		$front_ents = emd_find_limitby('frontend', 'wp_ticket_com');
		if ($query->is_author) {
			return $query;
		} elseif ($query->is_search && empty($front_ents)) {
			return $query;
		} elseif ($query->is_search && !empty($front_ents)) {
			$cap_post_types = get_post_types();
			foreach ($cap_post_types as $ptype) {
				if (!is_post_type_viewable($ptype)) {
					unset($cap_post_types[$ptype]);
				}
			}
			$query->set('post_type', array_diff($cap_post_types, $front_ents));
			return $query;
		} elseif (!empty($front_ents) && !empty($query->query['post_type']) && in_array($query->query['post_type'], $front_ents)) {
			$query = emd_limit_tax_single_archive('wp_ticket_com', $query);
		}
	} elseif ($query->is_admin && $query->is_post_type_archive()) {
		$back_ents = emd_find_limitby('backend', 'wp_ticket_com');
		if (empty($back_ents)) return $query;
		if (defined('DOING_AJAX') && DOING_AJAX) return $query;
		if (!empty($_GET['page']) && !empty($_GET['post_type']) && preg_match('/operations_' . $_GET['post_type'] . '/', $_GET['page'])) return $query;
		$query = emd_afc_filter('wp_ticket_com', $query);
	}
	return $query;
}
add_action('pre_get_posts', 'wp_ticket_com_query_filters');
/**
 * Get previous link for post type limited to seen by user
 * @since WPAS 4.0
 * @param string $link
 */
function wp_ticket_com_limit_previous_post_link($link) {
	return emd_limit_prev_next_link('wp_ticket_com', $link, true);
}
/**
 * Get next link for post type limited to seen by user
 * @since WPAS 4.0
 * @param string $link
 */
function wp_ticket_com_limit_next_post_link($link) {
	return emd_limit_prev_next_link('wp_ticket_com', $link, false);
}
add_filter('p2p_connectable_args', 'wp_ticket_com_limit_by_filters', 10, 3);
/**
 * Limitby relationships seen by user
 * @since WPAS 4.0
 * @param array $args
 * @param string $ctype
 * @param object $lpost
 *
 * @return array $args
 */
function wp_ticket_com_limit_by_filters($args, $ctype, $lpost) {
	return emd_limit_by_filters('wp_ticket_com', $args, $ctype);
}
