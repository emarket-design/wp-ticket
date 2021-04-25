<?php
/**
 * Entity Widget Classes
 *
 * @package WP_TICKET_COM
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
/**
 * Entity widget class extends Emd_Widget class
 *
 * @since WPAS 4.0
 */
class wp_ticket_com_recent_tickets_sidebar_widget extends Emd_Widget {
	public $title;
	public $text_domain = 'wp-ticket-com';
	public $class_label;
	public $class = 'emd_ticket';
	public $type = 'entity';
	public $has_pages = false;
	public $css_label = 'recent-tickets';
	public $id = 'wp_ticket_com_recent_tickets_sidebar_widget';
	public $query_args = array(
		'post_type' => 'emd_ticket',
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'context' => 'wp_ticket_com_recent_tickets_sidebar_widget',
	);
	public $filter = '';
	public $header = '';
	public $footer = '';
	/**
	 * Instantiate entity widget class with params
	 *
	 * @since WPAS 4.0
	 */
	public function __construct() {
		parent::__construct($this->id, __('Recent Tickets', 'wp-ticket-com') , __('Tickets', 'wp-ticket-com') , __('The most recent tickets', 'wp-ticket-com'));
	}
	/**
	 * Get header and footer for layout
	 *
	 * @since WPAS 4.6
	 */
	protected function get_header_footer() {
		ob_start();
		emd_get_template_part('wp_ticket_com', 'widget', 'recent-tickets-sidebar-header');
		$this->header = ob_get_clean();
		ob_start();
		emd_get_template_part('wp_ticket_com', 'widget', 'recent-tickets-sidebar-footer');
		$this->footer = ob_get_clean();
	}
	/**
	 * Enqueue css and js for widget
	 *
	 * @since WPAS 4.5
	 */
	protected function enqueue_scripts() {
		wp_ticket_com_enq_custom_css_js();
	}
	/**
	 * Returns widget layout
	 *
	 * @since WPAS 4.0
	 */
	public static function layout() {
		ob_start();
		emd_get_template_part('wp_ticket_com', 'widget', 'recent-tickets-sidebar-content');
		$layout = ob_get_clean();
		return $layout;
	}
}
$access_views = get_option('wp_ticket_com_access_views', Array());
if (empty($access_views['widgets']) || (!empty($access_views['widgets']) && in_array('recent_tickets_sidebar', $access_views['widgets']) && current_user_can('view_recent_tickets_sidebar'))) {
	register_widget('wp_ticket_com_recent_tickets_sidebar_widget');
}
