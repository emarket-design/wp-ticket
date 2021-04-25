<?php
/**
 * Entity Related Shortcode Functions
 *
 * @package WP_TICKET_COM
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
/**
 * Shortcode function
 *
 * @since WPAS 4.0
 * @param array $atts
 * @param array $args
 * @param string $form_name
 * @param int $pageno
 *
 * @return html
 */
function wp_ticket_com_search_tickets_set_shc($atts, $args = Array() , $form_name = '', $pageno = 1, $shc_page_count = 0) {
	global $shc_count;
	if ($shc_page_count != 0) {
		$shc_count = $shc_page_count;
	} else {
		if (empty($shc_count)) {
			$shc_count = 1;
		} else {
			$shc_count++;
		}
	}
	$fields = Array(
		'app' => 'wp_ticket_com',
		'class' => 'emd_ticket',
		'shc' => 'search_tickets',
		'shc_count' => $shc_count,
		'form' => $form_name,
		'has_pages' => false,
		'pageno' => $pageno,
		'pgn_class' => '',
		'theme' => 'bs',
		'hier' => 0,
		'hier_type' => 'ul',
		'hier_depth' => - 1,
		'hier_class' => '',
		'has_json' => 0,
	);
	$args_default = array(
		'posts_per_page' => '-1',
		'post_status' => 'publish',
		'orderby' => 'modified',
		'order' => 'DESC',
		'filter' => ''
	);
	return emd_shc_get_layout_list($atts, $args, $args_default, $fields);
}
/**
 * Shortcode function
 *
 * @since WPAS 4.0
 * @param array $atts
 * @param array $args
 * @param string $form_name
 * @param int $pageno
 *
 * @return html
 */
function wp_ticket_com_support_tickets_set_shc($atts, $args = Array() , $form_name = '', $pageno = 1, $shc_page_count = 0) {
	global $shc_count;
	if ($shc_page_count != 0) {
		$shc_count = $shc_page_count;
	} else {
		if (empty($shc_count)) {
			$shc_count = 1;
		} else {
			$shc_count++;
		}
	}
	$fields = Array(
		'app' => 'wp_ticket_com',
		'class' => 'emd_ticket',
		'shc' => 'support_tickets',
		'shc_count' => $shc_count,
		'form' => $form_name,
		'has_pages' => true,
		'pageno' => $pageno,
		'pgn_class' => '',
		'theme' => 'na',
		'hier' => 0,
		'hier_type' => 'ul',
		'hier_depth' => - 1,
		'hier_class' => '',
		'has_json' => 0,
	);
	$args_default = array(
		'posts_per_page' => '10',
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'filter' => ''
	);
	return emd_shc_get_layout_list($atts, $args, $args_default, $fields);
}
add_shortcode('support_tickets', 'support_tickets_list');
function support_tickets_list($atts) {
	$show_shc = 1;
	$show_shc = apply_filters('emd_show_shc', $show_shc, 'support_tickets');
	if ($show_shc == 1) {
		wp_enqueue_style('pagination');
		add_action('wp_footer', 'wp_ticket_com_enq_allview');
		wp_enqueue_style('emd-pagination');
		wp_ticket_com_enq_custom_css_js();
		$list = wp_ticket_com_support_tickets_set_shc($atts);
	} else {
		$list = '<div class="alert alert-info not-authorized">You are not allowed to access to this area. Please contact the site administrator.</div>';
	}
	return $list;
}
add_filter('widget_text', 'shortcode_unautop');
add_filter('widget_text', 'do_shortcode', 11);
