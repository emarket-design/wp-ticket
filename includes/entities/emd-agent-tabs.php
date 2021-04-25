<?php
/**
 * Tab /Accordion functions for entity admin edit/add new
 *
 * @package WP_TICKET_COM
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
add_action('admin_enqueue_scripts', 'wp_ticket_com_load_tabs_scripts');
add_action('emd_mb_before_tab_emd_agent_0', 'wp_ticket_com_show_tab_emd_agent_0');
add_filter('emd_mb_emd_agent_photo_begin_html', 'wp_ticket_com_begin_tab_emd_agent_0_0');
add_filter('emd_mb_emd_agent_email_begin_html', 'wp_ticket_com_begin_tab_emd_agent_0_1');
add_action('emd_mb_after_tab_emd_agent_0', 'wp_ticket_com_show_end_tabs_emd_agent');
/**
 * Tab show func
 * @since WPAS 4.0
 *
 * @return html
 */
function wp_ticket_com_show_tab_emd_agent_0() {
?>
<div id="tab_emd_agent_0" class="emd-tabs-acc" style="min-height:300px;">
<ul>
<li><a href="#0"><?php _e('Info', 'wp-ticket-com'); ?></a></li>
<li><a href="#1"><?php _e('Contact', 'wp-ticket-com'); ?></a></li>
</ul>
<?php
}
/**
 * tab begin func
 * @since WPAS 4.0
 * @param string $begin
 *
 * @return html
 */
function wp_ticket_com_begin_tab_emd_agent_0_0($begin) {
	$be0 = '<div id="0">';
	return $be0 . $begin;
}
/**
 * tab begin func
 * @since WPAS 4.0
 * @param string $begin
 *
 * @return html
 */
function wp_ticket_com_begin_tab_emd_agent_0_1($begin) {
	$be1 = '</div><div id="1">';
	return $be1 . $begin;
}
/**
 * Accordion/tab end func
 * @since WPAS 4.0
 *
 * @return html
 */
function wp_ticket_com_show_end_tabs_emd_agent() {
?>
</div>
</div>
<?php
}
