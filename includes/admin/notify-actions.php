<?php
/**
 * Notification Actions Functions
 *
 * @package     EMD
 * @copyright   Copyright (c) 2014,  Emarket Design
 * @since       1.0
 */
if (!defined('ABSPATH')) exit;
/**
 * Sends notification if there is active tax notification and if any change between old and new terms
 *
 * @since WPAS 4.0
 *
 * @param string $app
 * @param int $pid
 * @param string $type
 * @param string $field
 * @param array $old_val
 * @param array $new_val
 *
 */
if (!function_exists('emd_check_change_notify')) {
	function emd_check_change_notify($app, $pid, $type, $field, $old_val, $new_val) {
		$notify_list = get_option($app . "_notify_list");
		if (!empty($notify_list)) {
			foreach ($notify_list as $mynotify) {
				if ($mynotify['active'] == 1) {
					$send_msg = 0;
					if ($type == 'tax' && isset($mynotify['object']) && $mynotify['object'] == $field) {
						//first see if there is any change
						$diff1 = array_diff($old_val, $new_val);
						$diff2 = array_diff($new_val, $old_val);
						if (empty($mynotify['ev_change_val']) && (!empty($diff1) || !empty($diff2))) {
							$send_msg = 1;
						} elseif (!empty($mynotify['ev_change_val'])) {
							$old_terms = emd_get_terms($old_val, $field);
							$new_terms = emd_get_terms($new_val, $field);
							$change_vals_arr = explode(",",$mynotify['ev_change_val']);
							foreach($change_vals_arr as $ev_change_val){
								if (in_array(strtolower($ev_change_val) , $new_terms) && !in_array(strtolower($ev_change_val) , $old_terms)) {
									$send_msg = 1;
								}
							}
						}
					}
					if ($send_msg == 1) {
						do_action('emd_do_before_change_notify', $send_msg, $app, $pid, $mynotify);
						emd_send_notification($app, $mynotify, 'change', $pid);
					}
				}
			}
		}
	}
}
/**
 * Get terms of taxonomies by term taxonomy ids
 *
 * @since WPAS 4.0
 *
 * @param array $vals
 * @param string $field
 *
 * @return array $terms
 */
if (!function_exists('emd_get_terms')) {
	function emd_get_terms($vals, $field) {
		$terms = Array();
		if (!empty($vals)) {
			foreach ($vals as $myval) {
				$term = get_term_by('term_taxonomy_id', $myval, $field);
				//$terms[] = strtolower($term->name);
				$terms[] = strtolower($term->slug);
			}
		}
		return $terms;
	}
}
/**
 * Sends user and admin notifications
 *
 * @since WPAS 4.0
 *
 * @param string $app
 * @param array $mynotify
 * @param string $event
 * @param string $pid
 * @param array $rel_uniqs
 *
 */
if (!function_exists('emd_send_notification')) {
	function emd_send_notification($app, $mynotify, $event, $pid, $rel_uniqs = Array() , $comment_id = 0) {
		if (!empty($mynotify['user_msg'])) {
			$user_msg = $mynotify['user_msg'];
			$user_msg_arr = Array();
			$attr_list = get_option($app . '_attr_list');
			foreach ($mynotify['user_msg']['send_to'] as $send_to) {
				if ($send_to['active'] == 1 && $mynotify['level'] == 'com' && isset($send_to['com_email']) && $send_to['com_email'] == 1) {
					$comment = get_comment($comment_id);
					$comments = get_comments(array(
						'post_id' => $pid,
						'type' => $comment->comment_type
					));
					$com_sendto = Array();
					foreach ($comments as $pcomment) {
						if ($pcomment->comment_author_email != $comment->comment_author_email && !in_array($pcomment->comment_author_email, $com_sendto)) {
							$com_sendto[] = $pcomment->comment_author_email;
						}
					}
					if (!empty($com_sendto)) {
						foreach ($com_sendto as $sendto_email) {
							$user_msg_arr[$sendto_email]['message'] = emd_parse_template_tags($app, $user_msg['message'], $pid);
							$user_msg_arr[$sendto_email]['subject'] = emd_parse_template_tags($app, $user_msg['subject'], $pid);
						}
					}
				}
				if ($send_to['active'] == 1 && $event == 'front_add' && empty($mynotify['ev_back_add']) && !empty($send_to['rel']) && isset($rel_uniqs[$send_to['rel']])) {
					$rel_post = get_post($rel_uniqs[$send_to['rel']]);
					if ($rel_post->post_type == $send_to['entity']) {
						if($attr_list[$send_to['entity']][$send_to['attr']]['display_type'] == 'user'){
							$user_id = emd_mb_meta($send_to['attr'], '', $rel_uniqs[$send_to['rel']]);
							$user_info = get_userdata($user_id);
							$sendto_email = $user_info->user_email;
						}
						else {
							$sendto_email = emd_mb_meta($send_to['attr'], '', $rel_uniqs[$send_to['rel']]);
						}
					}
					else {
						if($attr_list[$send_to['entity']][$send_to['attr']]['display_type'] == 'user'){
							$user_id = emd_mb_meta($send_to['attr'], '', $pid);
							$user_info = get_userdata($user_id);
							$sendto_email = $user_info->user_email;
						}
						else {
							$sendto_email = emd_mb_meta($send_to['attr'], '', $pid);
						}
					}
					$user_msg['message'] = emd_parse_template_tags($app, $user_msg['message'], $rel_post->ID, 0, 'rel');
					$user_msg['subject'] = emd_parse_template_tags($app, $user_msg['subject'], $rel_post->ID, 0, 'rel');
					$user_msg_arr[$sendto_email]['message'] = emd_parse_template_tags($app, $user_msg['message'], $pid , 1, 'rel');
					$user_msg_arr[$sendto_email]['subject'] = emd_parse_template_tags($app, $user_msg['subject'], $pid, 1, 'rel');
				} elseif ($send_to['active'] == 1 && $event != 'front_add' && !empty($send_to['rel'])) {
					if($send_to['entity'] == $mynotify['entity'] || $mynotify['level'] == 'com' || (($event == 'change' || $event == 'back_add') && $send_to['entity'] != $mynotify['entity'])){
						global $wpdb;
						$other = "from";
						if ($send_to['from_to'] == 'from') {
							$other = "to";
						}
						if(!empty($send_to['rel']) && $event == 'back_add' && $send_to['entity'] != $mynotify['entity'] && $mynotify['level'] == 'rel'){
							$connection = p2p_get_connection($pid);
							if(empty($connection)){
								$conns = $wpdb->get_results($wpdb->prepare("SELECT p2p_{$send_to['from_to']} as pid FROM {$wpdb->p2p} 
									WHERE p2p_type= %s AND p2p_{$other} = %s", $send_to['rel'],$pid),ARRAY_A);
								foreach ($conns as $mycon) {
									if($attr_list[$send_to['entity']][$send_to['attr']]['display_type'] == 'user'){
										$user_id = emd_mb_meta($send_to['attr'], '', $pid);
										$user_info = get_userdata($user_id);
										$sendto_email = $user_info->user_email;
									}
									else {
										$sendto_email = emd_mb_meta($send_to['attr'], '', $pid);
									}
									$user_msg['message'] = emd_parse_template_tags($app, $user_msg['message'], $pid, 0, 'rel');
									$user_msg['subject'] = emd_parse_template_tags($app, $user_msg['subject'], $pid, 0, 'rel');
									$user_msg_arr[$sendto_email]['message'] = emd_parse_template_tags($app, $user_msg['message'], $mycon['pid'],1,'rel');
									$user_msg_arr[$sendto_email]['subject'] = emd_parse_template_tags($app, $user_msg['subject'], $mycon['pid'],1,'rel');
								}
							}
							else {
								$pid = $connection->p2p_to;
								$mycon_pid = $connection->p2p_from;
								if($attr_list[$send_to['entity']][$send_to['attr']]['display_type'] == 'user'){
									$user_id = emd_mb_meta($send_to['attr'], '', $pid);
									$user_info = get_userdata($user_id);
									$sendto_email = $user_info->user_email;
								}
								else {
									$sendto_email = emd_mb_meta($send_to['attr'], '', $pid);
								}
								$user_msg['message'] = emd_parse_template_tags($app, $user_msg['message'], $pid, 0, 'rel');
								$user_msg['subject'] = emd_parse_template_tags($app, $user_msg['subject'], $pid, 0, 'rel');
								$user_msg_arr[$sendto_email]['message'] = emd_parse_template_tags($app, $user_msg['message'], $mycon_pid,1,'rel');
								$user_msg_arr[$sendto_email]['subject'] = emd_parse_template_tags($app, $user_msg['subject'], $mycon_pid,1,'rel');
							}
						}
						else if($event == 'back_add' && $mynotify['level'] == 'rel'){
							$connection = p2p_get_connection($pid);
							if(empty($connection)){
								$conns = $wpdb->get_results($wpdb->prepare("SELECT p2p_{$other} as pid FROM {$wpdb->p2p} 
									WHERE p2p_type= %s AND p2p_{$send_to['from_to']} = %s",$send_to['rel'],$pid), ARRAY_A);
								foreach ($conns as $mycon) {
									if($attr_list[$send_to['entity']][$send_to['attr']]['display_type'] == 'user'){
										$user_id = emd_mb_meta($send_to['attr'], '', $mycon['pid']);
										$user_info = get_userdata($user_id);
										$sendto_email = $user_info->user_email;
									}
									else {
										$sendto_email = emd_mb_meta($send_to['attr'], '', $mycon['pid']);
									}
									$user_msg['message'] = apply_filters('emd_do_before_send_notify_rel', $user_msg['message'], $app, $pid);
									$user_msg['message'] = emd_parse_template_tags($app, $user_msg['message'], $pid, 0, 'rel');
									$user_msg['subject'] = emd_parse_template_tags($app, $user_msg['subject'], $pid, 0, 'rel');
									$user_msg['message'] = apply_filters('emd_do_before_send_notify_rel', $user_msg['message'], $app, $mycon['pid']);
									$user_msg_arr[$sendto_email]['message'] = emd_parse_template_tags($app, $user_msg['message'], $mycon['pid'], 1, 'rel');
									$user_msg_arr[$sendto_email]['subject'] = emd_parse_template_tags($app, $user_msg['subject'], $mycon['pid'], 1, 'rel');
								}
							}
							else {
								$pid = $connection->p2p_to;
								$mycon_pid = $connection->p2p_from;
								if($attr_list[$send_to['entity']][$send_to['attr']]['display_type'] == 'user'){
									$user_id = emd_mb_meta($send_to['attr'], '', $mycon_pid);
									$user_info = get_userdata($user_id);
									$sendto_email = $user_info->user_email;
								}
								else {
									$sendto_email = emd_mb_meta($send_to['attr'], '', $mycon_pid);
								}
								$user_msg['message'] = apply_filters('emd_do_before_send_notify_rel', $user_msg['message'], $app, $pid);
								$user_msg['message'] = emd_parse_template_tags($app, $user_msg['message'], $pid, 0, 'rel');
								$user_msg['subject'] = emd_parse_template_tags($app, $user_msg['subject'], $pid, 0, 'rel');
								$user_msg['message'] = apply_filters('emd_do_before_send_notify_rel', $user_msg['message'], $app, $mycon_pid);
								$user_msg_arr[$sendto_email]['message'] = emd_parse_template_tags($app, $user_msg['message'], $mycon_pid, 1, 'rel');
								$user_msg_arr[$sendto_email]['subject'] = emd_parse_template_tags($app, $user_msg['subject'], $mycon_pid, 1, 'rel');
							}
						}
						else {
							$conns = $wpdb->get_results($wpdb->prepare("SELECT p2p_{$other} as pid FROM {$wpdb->p2p} 
								WHERE p2p_type= %s AND p2p_{$send_to['from_to']} = %s", $send_to['rel'],$pid),ARRAY_A);
							foreach ($conns as $mycon) {
								if($attr_list[$send_to['entity']][$send_to['attr']]['display_type'] == 'user'){
									$user_id = emd_mb_meta($send_to['attr'], '', $mycon['pid']);
									$user_info = get_userdata($user_id);
									$sendto_email = $user_info->user_email;
								}
								else {
									$sendto_email = emd_mb_meta($send_to['attr'], '', $mycon['pid']);
								}
								$user_msg['message'] = apply_filters('emd_do_before_send_notify_rel', $user_msg['message'], $app, $pid);
								$user_msg['message'] = emd_parse_template_tags($app, $user_msg['message'], $pid, 0, 'rel');
								$user_msg['subject'] = emd_parse_template_tags($app, $user_msg['subject'], $pid, 0, 'rel');
								$user_msg['message'] = apply_filters('emd_do_before_send_notify_rel', $user_msg['message'], $app, $mycon['pid']);
								$user_msg_arr[$sendto_email]['message'] = emd_parse_template_tags($app, $user_msg['message'], $mycon['pid'], 1, 'rel');
								$user_msg_arr[$sendto_email]['subject'] = emd_parse_template_tags($app, $user_msg['subject'], $mycon['pid'], 1, 'rel');
							}
						}
					}
					else {
						if($attr_list[$send_to['entity']][$send_to['attr']]['display_type'] == 'user'){
							$user_id = emd_mb_meta($send_to['attr'],'',$pid);
							$user_info = get_userdata($user_id);
							$sendto_email = $user_info->user_email;
						}
						else {
							$sendto_email = emd_mb_meta($send_to['attr'], '', $pid);
						}
						$user_msg_arr[$sendto_email]['message'] = emd_parse_template_tags($app, $user_msg['message'], $pid);
						$user_msg_arr[$sendto_email]['subject'] = emd_parse_template_tags($app, $user_msg['subject'], $pid);
					}
				} elseif ($send_to['active'] == 1 && empty($send_to['rel']) && !empty($send_to['attr'])) {
					if($attr_list[$send_to['entity']][$send_to['attr']]['display_type'] == 'user'){
						$user_id = emd_mb_meta($send_to['attr'], '', $pid);
						$user_info = get_userdata($user_id);
						$sendto_email = $user_info->user_email;
					}
					else {
						$sendto_email = emd_mb_meta($send_to['attr'], '', $pid);
					}
					$user_msg_arr[$sendto_email]['message'] = emd_parse_template_tags($app, $user_msg['message'], $pid);
					$user_msg_arr[$sendto_email]['subject'] = emd_parse_template_tags($app, $user_msg['subject'], $pid);
				}
			}
			foreach($user_msg_arr as $msg_key => $msg_arr){
				$msg_arr['send_to'] = $msg_key;
				$msg_arr['reply_to'] = $user_msg['reply_to'];
				$msg_arr['from_name'] = (!empty($user_msg['from_name'])) ? $user_msg['from_name'] : '';
				$msg_arr['from_email'] = (!empty($user_msg['from_email'])) ? $user_msg['from_email'] : '';
				$msg_arr['cc'] = $user_msg['cc'];
				$msg_arr['bcc'] = $user_msg['bcc'];
				$emd_email = new Emd_Notify_Email($msg_arr['from_name'],$msg_arr['from_email']);
				$emd_email->emd_send_email($msg_arr);
			}
		}
		if (!empty($mynotify['admin_msg'])) {
			$mynotify['admin_msg']['message'] = emd_parse_template_tags($app, $mynotify['admin_msg']['message'], $pid);
			$mynotify['admin_msg']['subject'] = emd_parse_template_tags($app, $mynotify['admin_msg']['subject'], $pid);
			if(empty($emd_email)){
				$emd_email = new Emd_Notify_Email($mynotify['admin_msg']['from_name'],$mynotify['admin_msg']['from_email']);
			}
			$emd_email->emd_send_email($mynotify['admin_msg']);
		}
	}
}
/**
 * Sends notification if there is active rel, attr, comment or entity events
 *
 * @since WPAS 4.0
 *
 * @param string $app
 * @param int $pid
 * @param string $type
 * @param string $event
 * @param array $rel_uniqs
 *
 */
if (!function_exists('emd_check_notify')) {
	function emd_check_notify($app, $pid, $type, $event, $rel_uniqs = Array()) {
		//if type = rel & event back_add $pid is p2p_id
		$notify_list = get_option($app . "_notify_list");
		$notify_init_list = get_option($app . "_notify_init_list");
		$attr_list = get_option($app . "_attr_list");
		$comment_id = 0;
		if ($type != 'rel' && $type != 'com') {
			$mypost = get_post($pid);
			$ptype = $mypost->post_type;
		}
		if (!empty($notify_init_list)) {
			foreach ($notify_init_list as $knotify => $mynotify) {
				if(!empty($notify_list) && !empty($notify_list[$knotify])) {
					$mynotify = $notify_list[$knotify];
				}
				if ($mynotify['active'] == 1) {
					$send_msg = 0;
					if ($type == 'attr' && isset($mynotify['object']) && $event == 'change' && isset($_POST[$mynotify['object']])) {
						$old_val = emd_mb_meta($mynotify['object'], '', $pid);
						$new_val = sanitize_text_field($_POST[$mynotify['object']]);
						$new_val = emd_translate_date_format($attr_list[$ptype][$mynotify['object']], $new_val, 0);
						if (empty($mynotify['ev_change_val']) && $old_val != $new_val) {
							$send_msg = 1;
						} elseif (!empty($mynotify['ev_change_val']) && $new_val == $mynotify['ev_change_val'] && $old_val != $new_val) {
							$send_msg = 1;
						}
					} elseif(!empty($rel_uniqs) && $type == 'rel' && isset($mynotify['object']) && $mynotify['level'] == $type && isset($mynotify['ev_front_add']) && $mynotify['ev_front_add'] == 1) {
						$send_msg = 1;
					} elseif ($type == 'rel' && isset($mynotify['object']) && $mynotify['level'] == $type && isset($mynotify['ev_' . $event]) && $mynotify['ev_' . $event] == 1) {
						$connection = p2p_get_connection($pid);
						if (!empty($connection) && $mynotify['object'] == $connection->p2p_type) {
							$send_msg = 1;
							/*$pid = $connection->p2p_to;
							$to_p2p = get_post($connection->p2p_to);
							if ($to_p2p->post_type == $mynotify['entity']) {
								$pid = $connection->p2p_from;
							}*/
						}
					} elseif ($type == 'com' && isset($mynotify['object']) && $mynotify['level'] == $type && isset($mynotify['ev_' . $event]) && $mynotify['ev_' . $event] == 1) {
						$comment = get_comment($pid);
						if(!empty($comment)){
							$comment_id = $pid;
							if (isset($mynotify['object']) && $mynotify['object'] == $comment->comment_type) {
								$send_msg = 1;
								$pid = $comment->comment_post_ID;
							}
						}
					} elseif ($type == 'entity' && $mynotify['level'] == $type && isset($mynotify['ev_' . $event]) && $mynotify['ev_' . $event] == 1 && $mynotify['entity'] == $ptype) {
						$send_msg = 1;
					}
					if ($send_msg == 1) {
						emd_send_notification($app, $mynotify, $event, $pid, $rel_uniqs, $comment_id);
					}
				}
			}
		}
	}
}
add_action('emd_notify', 'emd_check_notify', 10, 5);
add_action('emd_change_notify', 'emd_check_change_notify', 10, 6);
add_action( 'login_redirect', 'emd_login_redirect', 10, 3);
/**
 * Check if login is from a notification email and forward it to redirect if a user is logged in
 *
 * @since WPAS 4.6
 *
 */
if (!function_exists('emd_login_redirect')) {
	function emd_login_redirect($redirect_to,$request, $user){
	      if(preg_match('/fr_emd_notify/', $redirect_to)){
		      $redirect_to = preg_replace('/fr_emd_notify.*/','',$redirect_to);
			$my_user = wp_get_current_user();
			if(!empty($my_user) && $my_user->ID != 0){
				global $user;
				$user = $my_user;
				return $redirect_to;
			}
			else {
				return $redirect_to;
			}
		}
		return $redirect_to;
	}
}
if (!class_exists('Emd_Notify_Email')) {
	class Emd_Notify_Email {

		private $from_email;

		private $from_name;

		public function __construct($from_name,$from_email) {
			$this->from_email = $from_email;
			$this->from_name = $from_name;
		}
		/**
		 * Use wp_mail to send notifications
		 *
		 * @since WPAS 4.0
		 *
		 * @param array $conf_arr
		 *
		 */
		public function emd_send_email($conf_arr) {
			if(!empty($conf_arr['send_to'])){
				if ($conf_arr['from_name'] != '') {
					$from_name = $conf_arr['from_name'];
					add_filter('wp_mail_from_name', array($this,'emd_set_from_name'));
				}
				else {
					$from_name = get_bloginfo('name');
				}
				if ($conf_arr['from_email'] != '') {
					$from_email = $conf_arr['from_email'];
					add_filter('wp_mail_from', array($this,'emd_set_from_email'));
				}
				else {
					$from_email = get_option('admin_email');
				}
				$from_name = utf8_encode($from_name);
				$headers = "From: " . stripslashes_deep(html_entity_decode($from_name, ENT_COMPAT, 'UTF-8')) . " <" . $from_email . ">\r\n";
				$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
				if ($conf_arr['reply_to'] != '') {
					$headers.= "Reply-To: " . $conf_arr['reply_to'] . "\r\n";
				} else {
					$headers.= "Reply-To: " . $from_email . "\r\n";
				}
				if ($conf_arr['cc'] != '') {
					$headers.= "Cc: " . $conf_arr['cc'] . "\r\n";
				}
				if ($conf_arr['bcc'] != '') {
					$headers.= "Bcc: " . $conf_arr['bcc'] . "\r\n";
				}
				wp_mail($conf_arr['send_to'], $conf_arr['subject'], $conf_arr['message'], $headers);
				remove_filter('wp_mail_from_name', array($this,'emd_set_from_name'));
				remove_filter('wp_mail_from', array($this,'emd_set_from_email'));

			}
		}
		public function emd_set_from_name(){
			return $this->from_name;
		}
		public function emd_set_from_email(){
			return $this->from_email;
		}
	}
}
