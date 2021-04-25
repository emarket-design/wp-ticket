<?php
/**
 * Dashboard Widget Functions
 *
 * @package     EMD
 * @copyright   Copyright (c) 2014,  Emarket Design
 * @since       1.0
 */
if (!defined('ABSPATH')) exit;
/**
 * Display admin,comment or entity dashboard widget
 *
 * @since WPAS 4.0
 * @param string $widget_id
 * @param string $type
 * @param array $args
 * @param string $default
 *
 * @return string layout html
 */
function emd_dashboard_widget($widget_id, $type, $args = Array() , $default = '') {
	$dwidgets = get_option('dashboard_widget_options');
	if ($type == 'admin' || $type == 'integration') {
		$message = $default;
		if (isset($dwidgets[$widget_id]) && isset($dwidgets[$widget_id]['message'])) {
			$message = $dwidgets[$widget_id]['message'];
		}
		echo $message;
	} else {
		$pids = Array();
		$back_ents = emd_find_limitby('backend', $args['app']);
		if(!empty($back_ents) && in_array($args['class'],$back_ents)){
			$pids = apply_filters('emd_limit_by', Array() , $args['app'], $args['class'],'backend');
		}
		$total_items = 5;
		if (isset($dwidgets[$widget_id]) && isset($dwidgets[$widget_id]['items'])) {
			$total_items = absint($dwidgets[$widget_id]['items']);
		}
		if ($type == 'comment') {
			echo emd_dashboard_comment_layout($total_items, $pids, $args);
		} else {
			echo '<div class="emd-container">' . $args['header'];
			echo Emd_Widget::get_ent_widget_layout($total_items, $pids, $args);
			echo $args['footer'] . '</div>';
		}
	}
}
/**
 * Display comment dashboard widget layout
 *
 * @since WPAS 4.0
 * @param int $posts_per_page
 * @param array $pids
 * @param array $args
 *
 * @return string $res layout html
 */
function emd_dashboard_comment_layout($posts_per_page, $pids, $args) {
	$res = sprintf(__('No %s found', 'emd-plugins') , $args['label']);
	$comments = array();
	$comments_query = $args['query'];
	$comments_query['number'] = $posts_per_page * 5;
	$comments_query['offset'] = 0;
	while (count($comments) < $posts_per_page && $possible = get_comments($comments_query)) {
		foreach ($possible as $comment) {
			if (!current_user_can('read_post', $comment->comment_post_ID) || (!empty($pids) && !in_array($comment->comment_post_ID, $pids))) continue;
			$comments[] = $comment;
			if (count($comments) == $posts_per_page) break 2;
		}
		$comments_query['offset']+= $comments_query['number'];
		$comments_query['number'] = $posts_per_page * 10;
	}
	if ($comments) {
		require_once (ABSPATH . 'wp-admin/includes/dashboard.php');
		ob_start();
		echo '<div id="activity-widget">';
		echo '<div id="latest-comments" class="activity-block">';
		echo '<div id="the-comment-list" data-wp-lists="list:comment">';
		foreach ($comments as $comment) {
			_wp_dashboard_recent_comments_row($comment);
		}
		echo '</div>';
		wp_comment_reply(-1, false, 'dashboard', false);
		wp_comment_trashnotice();
		echo '</div>';
		echo '</div>';
		return ob_get_clean();
	}
	return $res;
}
/**
 * Dashboard widget control for admin, entity or comment
 *
 * @since WPAS 4.0
 * @param string $widget_id
 * @param string $clabel
 * @param string $type
 * @param string $default
 *
 */
function emd_dashboard_widget_control($widget_id, $clabel, $type, $default = '') {
	$form_id = $widget_id . '_control';
	if (!$dwidgets = get_option('dashboard_widget_options')) $dwidgets = array();
	if (!isset($dwidgets[$widget_id])) $dwidgets[$widget_id] = array();
	if ('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST[$form_id])) {
		if ($type == 'admin') {
			$message = sanitize_textarea_field($_POST[$form_id]);
			$dwidgets[$widget_id]['message'] = stripslashes($message);
		} else {
			$number = absint($_POST[$form_id]['items']);
			$dwidgets[$widget_id]['items'] = $number;
		}
		update_option('dashboard_widget_options', $dwidgets);
	}
	if ($type == 'admin') {
		$message = isset($dwidgets[$widget_id]['message']) ? $dwidgets[$widget_id]['message'] : $default;
		$settings = array(
			'text_area_name' => $form_id,
			'quicktags' => true,
			'media_buttons' => false,
			'textarea_rows' => 3,
			'tinymce' => array(
				'theme_advanced_buttons1' => 'bold,italic,underline, justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,outdent,indent'
			) ,
		);
		$id = $form_id;
		wp_editor($message, $id, $settings);
	} else {
		$number = isset($dwidgets[$widget_id]['items']) ? (int)$dwidgets[$widget_id]['items'] : '';
		echo '<p><label for="' . esc_attr($widget_id) . '-number">' . sprintf(__('Number of %s to show:', 'emd-plugins') , $clabel) . '</label>';
		echo '<input id="' . esc_attr($widget_id) . '-number" name="' . esc_attr($form_id) . '[items]" type="text" value="' . $number . '" size="3" /></p>';
	}
}
