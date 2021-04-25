<?php
/**
 * Install and Deactivate Plugin Functions
 * @package WP_TICKET_COM
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
if (!class_exists('Wp_Ticket_Com_Install_Deactivate')):
	/**
	 * Wp_Ticket_Com_Install_Deactivate Class
	 * @since WPAS 4.0
	 */
	class Wp_Ticket_Com_Install_Deactivate {
		private $option_name;
		/**
		 * Hooks for install and deactivation and create options
		 * @since WPAS 4.0
		 */
		public function __construct() {
			$this->option_name = 'wp_ticket_com';
			add_action('admin_init', array(
				$this,
				'check_update'
			));
			register_activation_hook(WP_TICKET_COM_PLUGIN_FILE, array(
				$this,
				'install'
			));
			register_deactivation_hook(WP_TICKET_COM_PLUGIN_FILE, array(
				$this,
				'deactivate'
			));
			add_action('wp_head', array(
				$this,
				'version_in_header'
			));
			add_action('admin_init', array(
				$this,
				'setup_pages'
			));
			add_action('admin_notices', array(
				$this,
				'install_notice'
			));
			add_action('generate_rewrite_rules', 'emd_create_rewrite_rules');
			add_filter('query_vars', 'emd_query_vars');
			add_action('admin_init', array(
				$this,
				'register_settings'
			) , 0);
			$this->notify_actions();
			add_action('before_delete_post', array(
				$this,
				'delete_post_file_att'
			));
			add_action('wp_ajax_emd_load_file', 'emd_load_file');
			add_action('wp_ajax_nopriv_emd_load_file', 'emd_load_file');
			add_action('wp_ajax_emd_delete_file', 'emd_delete_file');
			add_action('wp_ajax_nopriv_emd_delete_file', 'emd_delete_file');
			add_action('init', array(
				$this,
				'init_extensions'
			) , 99);
			do_action('emd_ext_actions', $this->option_name);
			add_filter('tiny_mce_before_init', array(
				$this,
				'tinymce_fix'
			));
		}
		public function check_update() {
			$curr_version = get_option($this->option_name . '_version', 1);
			$new_version = constant(strtoupper($this->option_name) . '_VERSION');
			if (version_compare($curr_version, $new_version, '<')) {
				P2P_Storage::install();
				$this->set_options();
				$this->set_roles_caps();
				$this->set_notification();
				if (!get_option($this->option_name . '_activation_date')) {
					$triggerdate = mktime(0, 0, 0, date('m') , date('d') + 7, date('Y'));
					add_option($this->option_name . '_activation_date', $triggerdate);
				}
				set_transient($this->option_name . '_activate_redirect', true, 30);
				do_action($this->option_name . '_upgrade', $new_version);
				update_option($this->option_name . '_version', $new_version);
			}
		}
		public function version_in_header() {
			$version = constant(strtoupper($this->option_name) . '_VERSION');
			$name = constant(strtoupper($this->option_name) . '_NAME');
			echo '<meta name="generator" content="' . $name . ' v' . $version . ' - https://emdplugins.com" />' . "\n";
		}
		public function init_extensions() {
			do_action('emd_ext_init', $this->option_name);
		}
		/**
		 * Runs on plugin install to setup custom post types and taxonomies
		 * flushing rewrite rules, populates settings and options
		 * creates roles and assign capabilities
		 * @since WPAS 4.0
		 *
		 */
		public function install() {
			$this->set_options();
			$this->set_notification();
			P2P_Storage::install();
			Emd_Ticket::register();
			Emd_Agent::register();
			flush_rewrite_rules();
			$this->set_roles_caps();
			set_transient($this->option_name . '_activate_redirect', true, 30);
			do_action('emd_ext_install_hook', $this->option_name);
		}
		/**
		 * Runs on plugin deactivate to remove options, caps and roles
		 * flushing rewrite rules
		 * @since WPAS 4.0
		 *
		 */
		public function deactivate() {
			flush_rewrite_rules();
			$this->remove_caps_roles();
			$this->reset_options();
			do_action('emd_ext_deactivate', $this->option_name);
		}
		/**
		 * Register notification and/or license settings
		 * @since WPAS 4.0
		 *
		 */
		public function register_settings() {
			$notif_settings = new Emd_Notifications($this->option_name);
			$notif_settings->register_settings();
			do_action('emd_ext_register', $this->option_name);
			if (!get_transient($this->option_name . '_activate_redirect')) {
				return;
			}
			// Delete the redirect transient.
			delete_transient($this->option_name . '_activate_redirect');
			$query_args = array(
				'page' => $this->option_name
			);
			wp_safe_redirect(add_query_arg($query_args, admin_url('admin.php')));
		}
		/**
		 * Add notify actions
		 * @since WPAS 4.0
		 *
		 */
		private function notify_actions() {
			if (is_admin()) {
				add_action('wp_insert_post', array(
					$this,
					'notify_post_insert'
				) , 10, 3);
			}
			add_action('p2p_created_connection', array(
				$this,
				'notify_handle_new_connection'
			));
		}
		public function notify_post_insert($post_id, $post, $update) {
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
			if ($post->post_status == 'trash' || $post->post_title == 'Auto Draft') return;
			if (isset($_POST['original_post_status']) && $_POST['original_post_status'] == 'auto-draft' && !isset($_POST['emd_import_step2'])) {
				do_action('emd_notify', $this->option_name, $post_id, 'entity', 'back_add');
			} elseif (!empty($_POST['action']) && $_POST['action'] == 'editpost') {
				remove_action('wp_insert_post', array(
					$this,
					'notify_post_insert'
				) , 10, 3);
				do_action('emd_notify', $this->option_name, $post_id, 'entity', 'trigger_change');
				add_action('wp_insert_post', array(
					$this,
					'notify_post_insert'
				) , 10, 3);
			}
		}
		/**
		 * Send notification when relationship added
		 * @since WPAS 4.0
		 * @param int $p2p_id
		 *
		 */
		public function notify_handle_new_connection($p2p_id) {
			do_action('emd_notify', $this->option_name, $p2p_id, 'rel', 'back_add');
		}
		/**
		 * Sets caps and roles
		 *
		 * @since WPAS 4.0
		 *
		 */
		public function set_roles_caps() {
			global $wp_roles;
			$cust_roles = Array(
				'manager' => __('Manager', 'wp-ticket-com') ,
				'agent' => __('Agent', 'wp-ticket-com') ,
			);
			update_option($this->option_name . '_cust_roles', $cust_roles);
			$add_caps = Array(
				'edit_published_emd_tickets' => Array(
					'administrator',
					'editor',
					'author',
					'manager',
					'agent'
				) ,
				'manage_ticket_topic' => Array(
					'administrator'
				) ,
				'manage_operations_emd_canned_responses' => Array(
					'administrator'
				) ,
				'delete_cannedresponse_category' => Array(
					'administrator'
				) ,
				'delete_ticket_topic' => Array(
					'administrator'
				) ,
				'edit_emd_tickets' => Array(
					'administrator',
					'editor',
					'author',
					'contributor',
					'subscriber',
					'manager',
					'agent'
				) ,
				'delete_private_emd_agents' => Array(
					'administrator',
					'manager'
				) ,
				'edit_private_emd_agents' => Array(
					'administrator',
					'manager'
				) ,
				'delete_emd_canned_responses' => Array(
					'administrator',
					'editor',
					'author',
					'manager',
					'agent'
				) ,
				'edit_others_emd_agents' => Array(
					'administrator',
					'manager'
				) ,
				'edit_dashboard' => Array(
					'administrator',
					'manager'
				) ,
				'edit_others_emd_canned_responses' => Array(
					'administrator',
					'editor',
					'author',
					'manager',
					'agent'
				) ,
				'edit_emd_canned_responses' => Array(
					'administrator',
					'editor',
					'author',
					'manager',
					'agent'
				) ,
				'edit_private_emd_tickets' => Array(
					'administrator',
					'editor',
					'manager',
					'agent'
				) ,
				'edit_private_emd_canned_responses' => Array(
					'administrator',
					'editor',
					'author',
					'manager',
					'agent'
				) ,
				'manage_cannedresponse_category' => Array(
					'administrator'
				) ,
				'delete_cannedresponse_tag' => Array(
					'administrator'
				) ,
				'delete_private_emd_canned_responses' => Array(
					'administrator',
					'editor',
					'author',
					'manager',
					'agent'
				) ,
				'manage_operations_emd_agents' => Array(
					'administrator'
				) ,
				'edit_ticket_priority' => Array(
					'administrator'
				) ,
				'delete_emd_tickets' => Array(
					'administrator',
					'editor',
					'author',
					'contributor',
					'manager',
					'agent'
				) ,
				'delete_others_emd_canned_responses' => Array(
					'administrator',
					'editor',
					'manager'
				) ,
				'limitby_author_backend_emd_tickets' => Array(
					'author',
					'contributor',
					'subscriber',
					'agent'
				) ,
				'view_recent_tickets_dashboard' => Array(
					'administrator',
					'editor',
					'author',
					'subscriber',
					'manager',
					'agent'
				) ,
				'assign_ticket_priority' => Array(
					'administrator',
					'editor',
					'author',
					'manager',
					'agent'
				) ,
				'delete_published_emd_canned_responses' => Array(
					'administrator',
					'editor',
					'author',
					'manager',
					'agent'
				) ,
				'edit_emd_agents' => Array(
					'administrator',
					'manager'
				) ,
				'read_private_emd_agents' => Array(
					'administrator',
					'manager'
				) ,
				'assign_cannedresponse_tag' => Array(
					'administrator',
					'editor',
					'author',
					'manager',
					'agent'
				) ,
				'limitby_author_frontend_emd_tickets' => Array(
					'author',
					'contributor',
					'subscriber',
					'agent'
				) ,
				'read_private_emd_tickets' => Array(
					'administrator',
					'editor',
					'manager',
					'agent'
				) ,
				'manage_operations_emd_tickets' => Array(
					'administrator'
				) ,
				'set_author_emd_tickets' => Array(
					'administrator',
					'editor',
					'manager'
				) ,
				'edit_ticket_topic' => Array(
					'administrator'
				) ,
				'edit_ticket_status' => Array(
					'administrator'
				) ,
				'assign_ticket_status' => Array(
					'administrator',
					'editor',
					'author',
					'manager',
					'agent'
				) ,
				'edit_cannedresponse_tag' => Array(
					'administrator'
				) ,
				'manage_ticket_priority' => Array(
					'administrator'
				) ,
				'delete_others_emd_tickets' => Array(
					'administrator',
					'editor',
					'manager'
				) ,
				'edit_published_emd_agents' => Array(
					'administrator',
					'manager'
				) ,
				'view_wp_ticket_com_dashboard' => Array(
					'administrator',
					'editor'
				) ,
				'publish_emd_tickets' => Array(
					'administrator',
					'editor',
					'author',
					'manager',
					'agent'
				) ,
				'assign_cannedresponse_category' => Array(
					'administrator',
					'editor',
					'author',
					'manager',
					'agent'
				) ,
				'delete_ticket_priority' => Array(
					'administrator'
				) ,
				'delete_others_emd_agents' => Array(
					'administrator',
					'manager'
				) ,
				'manage_ticket_status' => Array(
					'administrator'
				) ,
				'edit_cannedresponse_category' => Array(
					'administrator'
				) ,
				'delete_ticket_status' => Array(
					'administrator'
				) ,
				'export' => Array(
					'administrator'
				) ,
				'manage_cannedresponse_tag' => Array(
					'administrator'
				) ,
				'publish_emd_canned_responses' => Array(
					'administrator',
					'editor',
					'author',
					'manager',
					'agent'
				) ,
				'delete_emd_agents' => Array(
					'administrator',
					'manager'
				) ,
				'limitby_tickets_assigned_to' => Array(
					'agent'
				) ,
				'read_private_emd_canned_responses' => Array(
					'administrator',
					'editor',
					'author',
					'manager',
					'agent'
				) ,
				'publish_emd_agents' => Array(
					'administrator',
					'manager'
				) ,
				'configure_recent_tickets_dashboard' => Array(
					'administrator',
					'manager'
				) ,
				'assign_ticket_topic' => Array(
					'administrator',
					'editor',
					'author',
					'manager',
					'agent'
				) ,
				'delete_published_emd_agents' => Array(
					'administrator',
					'manager'
				) ,
				'edit_others_emd_tickets' => Array(
					'administrator',
					'editor',
					'manager',
					'agent'
				) ,
				'delete_private_emd_tickets' => Array(
					'administrator',
					'editor',
					'manager',
					'agent'
				) ,
				'edit_published_emd_canned_responses' => Array(
					'administrator',
					'editor',
					'author',
					'manager',
					'agent'
				) ,
				'delete_published_emd_tickets' => Array(
					'administrator',
					'editor',
					'author',
					'manager',
					'agent'
				) ,
				'read' => Array(
					'manager',
					'agent'
				) ,
			);
			update_option($this->option_name . '_add_caps', $add_caps);
			if (class_exists('WP_Roles')) {
				if (!isset($wp_roles)) {
					$wp_roles = new WP_Roles();
				}
			}
			if (is_object($wp_roles)) {
				if (!empty($cust_roles)) {
					foreach ($cust_roles as $krole => $vrole) {
						$myrole = get_role($krole);
						if (empty($myrole)) {
							$myrole = add_role($krole, $vrole);
						}
					}
				}
				$this->set_reset_caps($wp_roles, 'add');
			}
		}
		/**
		 * Removes caps and roles
		 *
		 * @since WPAS 4.0
		 *
		 */
		public function remove_caps_roles() {
			global $wp_roles;
			if (class_exists('WP_Roles')) {
				if (!isset($wp_roles)) {
					$wp_roles = new WP_Roles();
				}
			}
			if (is_object($wp_roles)) {
				$this->set_reset_caps($wp_roles, 'remove');
				remove_role('manager');
				remove_role('agent');
			}
		}
		/**
		 * Set  capabilities
		 *
		 * @since WPAS 4.0
		 * @param object $wp_roles
		 * @param string $type
		 *
		 */
		public function set_reset_caps($wp_roles, $type) {
			$caps['enable'] = get_option($this->option_name . '_add_caps', Array());
			$caps['enable'] = apply_filters('emd_ext_get_caps', $caps['enable'], $this->option_name);
			foreach ($caps as $stat => $role_caps) {
				foreach ($role_caps as $mycap => $roles) {
					foreach ($roles as $myrole) {
						if (($type == 'add' && $stat == 'enable') || ($stat == 'disable' && $type == 'remove')) {
							$wp_roles->add_cap($myrole, $mycap);
						} else if (($type == 'remove' && $stat == 'enable') || ($type == 'add' && $stat == 'disable')) {
							$wp_roles->remove_cap($myrole, $mycap);
						}
					}
				}
			}
		}
		/**
		 * Sets notification options
		 * @since WPAS 4.0
		 *
		 */
		private function set_notification() {
			$notify_list['new_ticket'] = Array(
				'label' => __('New Ticket', 'wp-ticket-com') ,
				'active' => 1,
				'level' => 'entity',
				'entity' => 'emd_ticket',
				'ev_front_add' => 1,
				'ev_back_add' => 1,
				'user_msg' => Array(
					'subject' => 'Thanks for your support ticket.',
					'message' => '<p>Dear {emd_ticket_first_name} {emd_ticket_last_name},</p>
<p>We successfully received your ticket. One of representatives will review your ticket and get back to you.</p>
<p>Thanks,</p>',
					'send_to' => Array(
						Array(
							'active' => 1,
							'entity' => 'emd_ticket',
							'attr' => 'emd_ticket_email',
							'label' => __('Ticket Email', 'wp-ticket-com')
						)
					) ,
					'reply_to' => '',
					'cc' => '',
					'bcc' => ''
				) ,
				'admin_msg' => Array(
					'subject' => 'A new support ticket has been submitted',
					'message' => '<p>Dear Administrator,</p>
<p>The support ticket, <a href="{permalink}">{emd_ticket_id} - {title}</a> has been submitted. </p>
<h3>Details</h3>
<p><strong>First Name:</strong>  {emd_ticket_first_name}</p>
<p><strong>Last Name:</strong> {emd_ticket_last_name}</p>
<p><strong>Email:</strong> {emd_ticket_email}</p>
<p><strong>Phone:</strong> {emd_ticket_phone}</p>
<p><strong>Content:</strong> {content}</p>',
					'send_to' => '',
					'reply_to' => '',
					'cc' => '',
					'bcc' => ''
				)
			);
			$notify_list['new_ticket_assigned'] = Array(
				'label' => __('New Ticket Assigned', 'wp-ticket-com') ,
				'active' => 1,
				'level' => 'rel',
				'entity' => 'emd_agent',
				'ev_back_add' => 1,
				'object' => 'tickets_assigned_to',
				'user_msg' => Array(
					'subject' => 'A ticket assigned to you',
					'message' => '<p>A new support ticket, <a href="{permalink}">{emd_ticket_id}</a> has been assigned to you  Please click the link below to go to the ticket and publish:</p>
<p><a href="{permalink}">!#title#</a></p>
<h3>Details</h3>
<p><strong>First Name:</strong>  {emd_ticket_first_name}</p>
<p><strong>Last Name:</strong> {emd_ticket_last_name}</p>
<p><strong>Email:</strong> {emd_ticket_email}</p>
<p><strong>Phone:</strong> {emd_ticket_phone}</p>
<p><strong>Content:</strong> {content}</p>',
					'send_to' => Array(
						Array(
							'active' => 1,
							'entity' => 'emd_agent',
							'attr' => 'emd_agent_email',
							'label' => __('Assignee', 'wp-ticket-com') ,
							'rel' => 'tickets_assigned_to',
							'from_to' => 'to'
						)
					) ,
					'reply_to' => '',
					'cc' => '',
					'bcc' => ''
				)
			);
			update_option($this->option_name . '_notify_init_list', $notify_list);
			if (get_option($this->option_name . '_notify_list') === false) {
				update_option($this->option_name . '_notify_list', $notify_list);
			}
		}
		/**
		 * Set app specific options
		 *
		 * @since WPAS 4.0
		 *
		 */
		private function set_options() {
			$access_views = Array();
			if (get_option($this->option_name . '_setup_pages', 0) == 0) {
				update_option($this->option_name . '_setup_pages', 1);
			}
			$limitby_auth_caps = Array(
				'emd_ticket' => Array(
					'limitby_author_backend_emd_tickets',
					'limitby_author_frontend_emd_tickets'
				)
			);
			$limitby_caps['emd_ticket'] = Array(
				'tickets_assigned_to' => 'emd_agent_userid'
			);
			if (!empty($limitby_caps)) {
				update_option($this->option_name . '_limitby_caps', $limitby_caps);
			}
			if (!empty($limitby_auth_caps)) {
				update_option($this->option_name . '_limitby_auth_caps', $limitby_auth_caps);
			}
			update_option($this->option_name . '_access_views', $access_views);
			$ent_list = Array(
				'emd_ticket' => Array(
					'label' => __('Tickets', 'wp-ticket-com') ,
					'rewrite' => 'tickets',
					'archive_view' => 0,
					'rest_api' => 0,
					'sortable' => 0,
					'searchable' => 1,
					'class_title' => Array(
						'emd_ticket_id'
					) ,
					'unique_keys' => Array(
						'emd_ticket_id'
					) ,
					'req_blt' => Array(
						'blt_title' => Array(
							'msg' => __('Subject', 'wp-ticket-com')
						) ,
						'blt_content' => Array(
							'msg' => __('Message', 'wp-ticket-com')
						) ,
					) ,
				) ,
				'emd_agent' => Array(
					'label' => __('Agents', 'wp-ticket-com') ,
					'rewrite' => 'agents',
					'archive_view' => 0,
					'rest_api' => 0,
					'sortable' => 0,
					'searchable' => 0,
					'class_title' => Array(
						'emd_agent_userid',
						'emd_agent_first_name',
						'emd_agent_last_name'
					) ,
					'unique_keys' => Array(
						'emd_agent_userid',
						'emd_agent_first_name',
						'emd_agent_last_name'
					) ,
					'user_key' => 'emd_agent_userid',
					'limit_user_roles' => Array(
						'manager',
						'agent'
					) ,
					'user_email_key' => 'emd_agent_email'
				) ,
			);
			update_option($this->option_name . '_ent_list', $ent_list);
			$shc_list['app'] = 'Wp Ticket';
			$shc_list['has_gmap'] = 0;
			$shc_list['has_form_lite'] = 1;
			$shc_list['has_lite'] = 1;
			$shc_list['has_bs'] = 1;
			$shc_list['has_autocomplete'] = 0;
			$shc_list['remove_vis'] = 0;
			$shc_list['forms']['submit_tickets'] = Array(
				'name' => 'submit_tickets',
				'type' => 'submit',
				'ent' => 'emd_ticket',
				'targeted_device' => 'desktops',
				'label_position' => 'top',
				'element_size' => 'medium',
				'display_inline' => '0',
				'noaccess_msg' => '<p>You are not allowed to access to this area. Please contact the site administrator.</p>',
				'disable_submit' => '0',
				'submit_status' => 'publish',
				'visitor_submit_status' => 'publish',
				'submit_button_type' => 'btn-success',
				'submit_button_label' => 'Submit Ticket',
				'submit_button_size' => 'btn-large',
				'submit_button_block' => '1',
				'submit_button_fa' => 'fa-angle-double-right',
				'submit_button_fa_size' => 'fa-lg',
				'submit_button_fa_pos' => 'left',
				'show_captcha' => 'show-to-visitors',
				'disable_after' => '0',
				'confirm_method' => 'text',
				'confirm_url' => '',
				'confirm_success_txt' => 'Thanks for your submission.',
				'confirm_error_txt' => 'There has been an error when submitting your entry. Please contact the site administrator.',
				'enable_ajax' => '0',
				'after_submit' => 'clear',
				'schedule_start' => '',
				'schedule_end' => '',
				'enable_operators' => '0',
				'ajax_search' => '0',
				'result_templ' => '',
				'result_fields' => '',
				'noresult_msg' => 'Your search returned no results.',
				'view_name' => '',
				'honeypot' => '1',
				'login_reg' => 'none',
				'page_title' => __('Open a Ticket', 'wp-ticket-com')
			);
			$shc_list['forms']['search_tickets'] = Array(
				'name' => 'search_tickets',
				'type' => 'search',
				'ent' => 'emd_ticket',
				'targeted_device' => 'desktops',
				'label_position' => 'top',
				'element_size' => 'medium',
				'display_inline' => '0',
				'noaccess_msg' => '<p>You are not allowed to access to this area. Please contact the site administrator.</p>',
				'disable_submit' => '0',
				'submit_status' => 'publish',
				'visitor_submit_status' => 'draft',
				'submit_button_type' => 'btn-primary',
				'submit_button_label' => 'Search Tickets',
				'submit_button_size' => 'btn-large',
				'submit_button_block' => '1',
				'submit_button_fa' => 'fa-search',
				'submit_button_fa_size' => 'fa-lg',
				'submit_button_fa_pos' => 'left',
				'show_captcha' => 'show-to-visitors',
				'disable_after' => '0',
				'confirm_method' => 'text',
				'confirm_url' => '',
				'confirm_success_txt' => 'Thanks for your submission.',
				'confirm_error_txt' => 'There has been an error when submitting your entry. Please contact the site administrator.',
				'enable_ajax' => '0',
				'after_submit' => 'show',
				'schedule_start' => '',
				'schedule_end' => '',
				'enable_operators' => '0',
				'ajax_search' => '0',
				'result_templ' => 'cust_table',
				'result_fields' => '',
				'noresult_msg' => 'Your search returned no results.',
				'view_name' => 'search_tickets',
				'honeypot' => '1',
				'login_reg' => 'none',
				'page_title' => __('Search Tickets', 'wp-ticket-com')
			);
			$shc_list['shcs']['support_tickets'] = Array(
				"class_name" => "emd_ticket",
				"type" => "std",
				'page_title' => __('Ticket List', 'wp-ticket-com') ,
			);
			if (!empty($shc_list)) {
				update_option($this->option_name . '_shc_list', $shc_list);
			}
			$attr_list['emd_ticket']['emd_ticket_id'] = Array(
				'label' => __('Ticket ID', 'wp-ticket-com') ,
				'display_type' => 'hidden',
				'required' => 0,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 1,
				'mid' => 'emd_ticket_info_emd_ticket_0',
				'desc' => __('Unique identifier for a ticket', 'wp-ticket-com') ,
				'type' => 'char',
				'hidden_func' => 'unique_id',
				'uniqueAttr' => true,
			);
			$attr_list['emd_ticket']['emd_ticket_first_name'] = Array(
				'label' => __('First Name', 'wp-ticket-com') ,
				'display_type' => 'text',
				'required' => 1,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 1,
				'mid' => 'emd_ticket_info_emd_ticket_0',
				'type' => 'char',
				'user_map' => 'user_firstname',
			);
			$attr_list['emd_ticket']['emd_ticket_last_name'] = Array(
				'label' => __('Last Name', 'wp-ticket-com') ,
				'display_type' => 'text',
				'required' => 0,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 1,
				'mid' => 'emd_ticket_info_emd_ticket_0',
				'type' => 'char',
				'user_map' => 'user_lastname',
			);
			$attr_list['emd_ticket']['emd_ticket_email'] = Array(
				'label' => __('Email', 'wp-ticket-com') ,
				'display_type' => 'text',
				'required' => 1,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 1,
				'mid' => 'emd_ticket_info_emd_ticket_0',
				'desc' => __('Our responses to your ticket will be sent to this email address.', 'wp-ticket-com') ,
				'type' => 'char',
				'email' => true,
			);
			$attr_list['emd_ticket']['emd_ticket_phone'] = Array(
				'label' => __('Phone', 'wp-ticket-com') ,
				'display_type' => 'text',
				'required' => 0,
				'srequired' => 0,
				'filterable' => 0,
				'list_visible' => 0,
				'mid' => 'emd_ticket_info_emd_ticket_0',
				'desc' => __('Please enter a phone number in case we need to contact you.', 'wp-ticket-com') ,
				'type' => 'char',
			);
			$attr_list['emd_ticket']['emd_ticket_duedate'] = Array(
				'label' => __('Due', 'wp-ticket-com') ,
				'display_type' => 'datetime',
				'required' => 0,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 0,
				'mid' => 'emd_ticket_info_emd_ticket_0',
				'desc' => __('The due date of the ticket', 'wp-ticket-com') ,
				'type' => 'datetime',
				'dformat' => array(
					'dateFormat' => 'mm-dd-yy',
					'timeFormat' => 'HH:mm'
				) ,
				'date_format' => 'm-d-Y H:i',
				'time_format' => 'hh:mm',
			);
			$attr_list['emd_ticket']['emd_ticket_attachment'] = Array(
				'label' => __('Attachments', 'wp-ticket-com') ,
				'display_type' => 'file',
				'required' => 0,
				'srequired' => 0,
				'filterable' => 0,
				'list_visible' => 1,
				'mid' => 'emd_ticket_info_emd_ticket_0',
				'desc' => __('Attach related files to the ticket.', 'wp-ticket-com') ,
				'type' => 'char',
			);
			$attr_list['emd_ticket']['wpas_form_name'] = Array(
				'label' => __('Form Name', 'wp-ticket-com') ,
				'display_type' => 'hidden',
				'required' => 0,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 0,
				'mid' => 'emd_ticket_info_emd_ticket_0',
				'type' => 'char',
				'options' => array() ,
				'no_update' => 1,
				'std' => 'admin',
			);
			$attr_list['emd_ticket']['wpas_form_submitted_by'] = Array(
				'label' => __('Form Submitted By', 'wp-ticket-com') ,
				'display_type' => 'hidden',
				'required' => 0,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 0,
				'mid' => 'emd_ticket_info_emd_ticket_0',
				'type' => 'char',
				'options' => array() ,
				'hidden_func' => 'user_login',
				'no_update' => 1,
			);
			$attr_list['emd_ticket']['wpas_form_submitted_ip'] = Array(
				'label' => __('Form Submitted IP', 'wp-ticket-com') ,
				'display_type' => 'hidden',
				'required' => 0,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 0,
				'mid' => 'emd_ticket_info_emd_ticket_0',
				'type' => 'char',
				'options' => array() ,
				'hidden_func' => 'user_ip',
				'no_update' => 1,
			);
			$attr_list['emd_agent']['emd_agent_photo'] = Array(
				'label' => __('Photo', 'wp-ticket-com') ,
				'display_type' => 'image',
				'required' => 0,
				'srequired' => 0,
				'filterable' => 0,
				'list_visible' => 1,
				'mid' => 'tab_emd_agent_0',
				'desc' => __('Photo of the staff member.', 'wp-ticket-com') ,
				'type' => 'char',
				'max_file_uploads' => 1,
				'file_ext' => 'jpg,jpeg,png,gif',
			);
			$attr_list['emd_agent']['emd_agent_first_name'] = Array(
				'label' => __('First Name', 'wp-ticket-com') ,
				'display_type' => 'text',
				'required' => 1,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 0,
				'mid' => 'tab_emd_agent_0',
				'desc' => __('First name of the staff member.', 'wp-ticket-com') ,
				'type' => 'char',
				'uniqueAttr' => true,
				'user_map' => 'user_firstname',
			);
			$attr_list['emd_agent']['emd_agent_last_name'] = Array(
				'label' => __('Last Name', 'wp-ticket-com') ,
				'display_type' => 'text',
				'required' => 1,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 0,
				'mid' => 'tab_emd_agent_0',
				'desc' => __('Last name of the staff member.', 'wp-ticket-com') ,
				'type' => 'char',
				'uniqueAttr' => true,
				'user_map' => 'user_lastname',
			);
			$attr_list['emd_agent']['emd_agent_userid'] = Array(
				'label' => __('Agent User', 'wp-ticket-com') ,
				'display_type' => 'user',
				'required' => 1,
				'srequired' => 0,
				'filterable' => 0,
				'list_visible' => 1,
				'mid' => 'tab_emd_agent_0',
				'desc' => __('System user assigned to the staff member.', 'wp-ticket-com') ,
				'type' => 'char',
				'placeholder' => __('Please Select', 'wp-ticket-com') ,
				'field_type' => 'select',
				'roles' => Array(
					'manager',
					'agent'
				) ,
				'uniqueAttr' => true,
			);
			$attr_list['emd_agent']['emd_agent_email'] = Array(
				'label' => __('Email', 'wp-ticket-com') ,
				'display_type' => 'text',
				'required' => 0,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 1,
				'mid' => 'tab_emd_agent_0',
				'desc' => __('Email address of the staff member.', 'wp-ticket-com') ,
				'type' => 'char',
				'email' => true,
			);
			$attr_list['emd_agent']['emd_agent_phone'] = Array(
				'label' => __('Phone', 'wp-ticket-com') ,
				'display_type' => 'text',
				'required' => 0,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 1,
				'mid' => 'tab_emd_agent_0',
				'desc' => __('Phone number of the staff member.', 'wp-ticket-com') ,
				'type' => 'char',
			);
			$attr_list['emd_agent']['emd_agent_phone_ext'] = Array(
				'label' => __('Extension', 'wp-ticket-com') ,
				'display_type' => 'text',
				'required' => 0,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 0,
				'mid' => 'tab_emd_agent_0',
				'desc' => __('Phone number extension of the staff member.', 'wp-ticket-com') ,
				'type' => 'char',
			);
			$attr_list['emd_agent']['emd_agent_mobile'] = Array(
				'label' => __('Mobile', 'wp-ticket-com') ,
				'display_type' => 'text',
				'required' => 0,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 0,
				'mid' => 'tab_emd_agent_0',
				'desc' => __('Mobile phone number of the staff member.', 'wp-ticket-com') ,
				'type' => 'char',
			);
			$attr_list = apply_filters('emd_ext_attr_list', $attr_list, $this->option_name);
			if (!empty($attr_list)) {
				update_option($this->option_name . '_attr_list', $attr_list);
			}
			update_option($this->option_name . '_glob_init_list', Array());
			$glob_forms_list['search_tickets']['captcha'] = 'show-to-visitors';
			$glob_forms_list['search_tickets']['noaccess_msg'] = '<p>You are not allowed to access to this area. Please contact the site administrator.</p>';
			$glob_forms_list['search_tickets']['login_reg'] = 'none';
			$glob_forms_list['search_tickets']['noresult_msg'] = 'Your search returned no results.';
			$glob_forms_list['search_tickets']['csrf'] = 0;
			$glob_forms_list['search_tickets']['emd_ticket_id'] = Array(
				'show' => 1,
				'row' => 1,
				'req' => 0,
				'size' => 12,
			);
			$glob_forms_list['search_tickets']['emd_ticket_email'] = Array(
				'show' => 1,
				'row' => 2,
				'req' => 0,
				'size' => 12,
			);
			$glob_forms_list['search_tickets']['ticket_topic'] = Array(
				'show' => 1,
				'row' => 7,
				'req' => 0,
				'size' => 12,
			);
			$glob_forms_list['search_tickets']['ticket_priority'] = Array(
				'show' => 1,
				'row' => 8,
				'req' => 0,
				'size' => 12,
			);
			$glob_forms_list['search_tickets']['emd_ticket_duedate'] = Array(
				'show' => 1,
				'row' => 9,
				'req' => 0,
				'size' => 12,
			);
			$glob_forms_list['submit_tickets']['captcha'] = 'show-to-visitors';
			$glob_forms_list['submit_tickets']['noaccess_msg'] = '<p>You are not allowed to access to this area. Please contact the site administrator.</p>';
			$glob_forms_list['submit_tickets']['error_msg'] = 'There has been an error when submitting your entry. Please contact the site administrator.';
			$glob_forms_list['submit_tickets']['success_msg'] = 'Thanks for your submission.';
			$glob_forms_list['submit_tickets']['login_reg'] = 'none';
			$glob_forms_list['submit_tickets']['csrf'] = 1;
			$glob_forms_list['submit_tickets']['emd_ticket_id'] = Array(
				'show' => 1,
				'row' => 1,
				'req' => 0,
				'size' => 12,
			);
			$glob_forms_list['submit_tickets']['ticket_topic'] = Array(
				'show' => 1,
				'row' => 2,
				'req' => 0,
				'size' => 12,
			);
			$glob_forms_list['submit_tickets']['emd_ticket_first_name'] = Array(
				'show' => 1,
				'row' => 7,
				'req' => 1,
				'size' => 12,
			);
			$glob_forms_list['submit_tickets']['emd_ticket_last_name'] = Array(
				'show' => 1,
				'row' => 8,
				'req' => 0,
				'size' => 12,
			);
			$glob_forms_list['submit_tickets']['emd_ticket_email'] = Array(
				'show' => 1,
				'row' => 9,
				'req' => 1,
				'size' => 12,
			);
			$glob_forms_list['submit_tickets']['blt_title'] = Array(
				'show' => 1,
				'row' => 10,
				'req' => 1,
				'size' => 12,
				'label' => __('Subject', 'wp-ticket-com')
			);
			$glob_forms_list['submit_tickets']['blt_content'] = Array(
				'show' => 1,
				'row' => 11,
				'req' => 1,
				'size' => 12,
				'label' => __('Message', 'wp-ticket-com')
			);
			$glob_forms_list['submit_tickets']['emd_ticket_phone'] = Array(
				'show' => 1,
				'row' => 12,
				'req' => 0,
				'size' => 12,
			);
			$glob_forms_list['submit_tickets']['emd_ticket_attachment'] = Array(
				'show' => 1,
				'row' => 13,
				'req' => 0,
				'size' => 12,
			);
			$glob_forms_list['submit_tickets']['ticket_priority'] = Array(
				'show' => 1,
				'row' => 14,
				'req' => 0,
				'size' => 12,
			);
			$glob_forms_list['submit_tickets']['emd_ticket_duedate'] = Array(
				'show' => 1,
				'row' => 15,
				'req' => 0,
				'size' => 12,
			);
			if (!empty($glob_forms_list)) {
				update_option($this->option_name . '_glob_forms_init_list', $glob_forms_list);
				if (get_option($this->option_name . '_glob_forms_list') === false) {
					update_option($this->option_name . '_glob_forms_list', $glob_forms_list);
				}
			}
			$tax_list['emd_ticket']['ticket_priority'] = Array(
				'archive_view' => 0,
				'label' => __('Priorities', 'wp-ticket-com') ,
				'single_label' => __('Priority', 'wp-ticket-com') ,
				'default' => Array(
					__('Uncategorized', 'wp-ticket-com')
				) ,
				'type' => 'single',
				'hier' => 0,
				'sortable' => 0,
				'list_visible' => 0,
				'required' => 0,
				'srequired' => 0,
				'rewrite' => 'ticket_priority',
				'init_values' => Array(
					Array(
						'name' => __('Critical', 'wp-ticket-com') ,
						'slug' => sanitize_title('Critical') ,
						'desc' => __('A problem or issue impacting a significant group of customers or any mission critical issue affecting a single customer.', 'wp-ticket-com')
					) ,
					Array(
						'name' => __('Major', 'wp-ticket-com') ,
						'slug' => sanitize_title('Major') ,
						'desc' => __('Non critical but significant issue affecting a single user or an issue that is degrading the performance and reliability of supported services, however, the services are still operational. Support issues that could escalate to Critical if not addressed quickly.', 'wp-ticket-com')
					) ,
					Array(
						'name' => __('Normal', 'wp-ticket-com') ,
						'slug' => sanitize_title('Normal') ,
						'desc' => __('Routine support requests that impact a single user or non-critical software or hardware error.', 'wp-ticket-com')
					) ,
					Array(
						'name' => __('Minor', 'wp-ticket-com') ,
						'slug' => sanitize_title('Minor') ,
						'desc' => __('Work that has been scheduled in advance with the customer, a minor service issue, or general inquiry.', 'wp-ticket-com')
					) ,
					Array(
						'name' => __('Uncategorized', 'wp-ticket-com') ,
						'slug' => sanitize_title('Uncategorized') ,
						'desc' => __('No priority assigned', 'wp-ticket-com')
					)
				)
			);
			$tax_list['emd_ticket']['ticket_topic'] = Array(
				'archive_view' => 0,
				'label' => __('Topics', 'wp-ticket-com') ,
				'single_label' => __('Topic', 'wp-ticket-com') ,
				'default' => Array(
					__('Uncategorized', 'wp-ticket-com')
				) ,
				'type' => 'single',
				'hier' => 0,
				'sortable' => 0,
				'list_visible' => 0,
				'required' => 0,
				'srequired' => 0,
				'rewrite' => 'ticket_topic',
				'init_values' => Array(
					Array(
						'name' => __('Feature request', 'wp-ticket-com') ,
						'slug' => sanitize_title('Feature request')
					) ,
					Array(
						'name' => __('Task', 'wp-ticket-com') ,
						'slug' => sanitize_title('Task')
					) ,
					Array(
						'name' => __('Bug', 'wp-ticket-com') ,
						'slug' => sanitize_title('Bug')
					) ,
					Array(
						'name' => __('Uncategorized', 'wp-ticket-com') ,
						'slug' => sanitize_title('Uncategorized')
					) ,
					Array(
						'name' => __('Order', 'wp-ticket-com') ,
						'slug' => sanitize_title('Order')
					) ,
					Array(
						'name' => __('Presales', 'wp-ticket-com') ,
						'slug' => sanitize_title('Presales')
					)
				)
			);
			$tax_list['emd_ticket']['ticket_status'] = Array(
				'archive_view' => 0,
				'label' => __('Statuses', 'wp-ticket-com') ,
				'single_label' => __('Status', 'wp-ticket-com') ,
				'default' => Array(
					__('Open', 'wp-ticket-com')
				) ,
				'type' => 'single',
				'hier' => 0,
				'sortable' => 0,
				'list_visible' => 0,
				'required' => 0,
				'srequired' => 0,
				'rewrite' => 'ticket_status',
				'init_values' => Array(
					Array(
						'name' => __('Open', 'wp-ticket-com') ,
						'slug' => sanitize_title('Open') ,
						'desc' => __('This ticket is in the initial state, ready for the assignee to start work on it.', 'wp-ticket-com')
					) ,
					Array(
						'name' => __('In Progress', 'wp-ticket-com') ,
						'slug' => sanitize_title('In Progress') ,
						'desc' => __('This ticket is being actively worked on at the moment.', 'wp-ticket-com')
					) ,
					Array(
						'name' => __('Closed', 'wp-ticket-com') ,
						'slug' => sanitize_title('Closed') ,
						'desc' => __('This ticket is complete.', 'wp-ticket-com')
					)
				)
			);
			$tax_list = apply_filters('emd_ext_tax_list', $tax_list, $this->option_name);
			if (!empty($tax_list)) {
				update_option($this->option_name . '_tax_list', $tax_list);
			}
			$rel_list['rel_tickets_assigned_to'] = Array(
				'from' => 'emd_agent',
				'to' => 'emd_ticket',
				'type' => 'one-to-many',
				'from_title' => __('Tickets Assigned', 'wp-ticket-com') ,
				'to_title' => __('Assignee', 'wp-ticket-com') ,
				'required' => 0,
				'srequired' => 0,
				'show' => 'to',
				'filter' => ''
			);
			if (!empty($rel_list)) {
				update_option($this->option_name . '_rel_list', $rel_list);
			}
			$emd_activated_plugins = get_option('emd_activated_plugins');
			if (!$emd_activated_plugins) {
				update_option('emd_activated_plugins', Array(
					'wp-ticket-com'
				));
			} elseif (!in_array('wp-ticket-com', $emd_activated_plugins)) {
				array_push($emd_activated_plugins, 'wp-ticket-com');
				update_option('emd_activated_plugins', $emd_activated_plugins);
			}
			//conf parameters for incoming email
			$has_incoming_email = Array(
				'emd_ticket' => Array(
					'label' => 'Tickets',
					'status' => 'publish',
					'vis_submit' => 1,
					'vis_status' => 'publish',
					'tax' => 'ticket_topic',
					'subject' => 'blt_title',
					'date' => Array(
						'post_date'
					) ,
					'body' => 'emd_blt_content',
					'att' => 'emd_ticket_attachment',
					'email' => 'emd_ticket_email',
					'name' => Array(
						'emd_ticket_first_name',
						'emd_ticket_last_name',
					)
				)
			);
			update_option($this->option_name . '_has_incoming_email', $has_incoming_email);
			$emd_inc_email_apps = get_option('emd_inc_email_apps');
			$emd_inc_email_apps[$this->option_name] = $this->option_name . '_inc_email_conf';
			update_option('emd_inc_email_apps', $emd_inc_email_apps);
			//conf parameters for inline entity
			$has_inline_ent = Array(
				'emd_ticket' => Array(
					'canned_response' => Array(
						'location' => Array(
							'wp_comment',
						) ,
						'button_label' => 'Canned Response',
						'button_icon' => '',
						'entity' => Array(
							'name' => 'emd_canned_response',
							'label' => 'Canned Responses',
							'singular' => 'Canned Response',
							'all_items' => 'Canned Responses',
						) ,
						'taxonomies' => Array(
							'cannedresponse_category' => Array(
								'label' => 'CR Categories',
								'singular' => 'CR Category',
								'type' => 'single',
								'hierarchical' => false,
								'values' => Array(
									Array(
										'name' => __('Business', 'wp-ticket-com') ,
										'slug' => sanitize_title('Business')
									) ,
									Array(
										'name' => __('Education', 'wp-ticket-com') ,
										'slug' => sanitize_title('Education')
									) ,
									Array(
										'name' => __('Science', 'wp-ticket-com') ,
										'slug' => sanitize_title('Science')
									) ,
									Array(
										'name' => __('Technology', 'wp-ticket-com') ,
										'slug' => sanitize_title('Technology')
									)
								) ,
								'default' => Array(
									__('Science', 'wp-ticket-com')
								) ,
							) ,
							'cannedresponse_tag' => Array(
								'label' => 'CR Tags',
								'singular' => 'CR Tag',
								'type' => 'multi',
								'hierarchical' => false,
							) ,
						)
					) ,
				)
			);
			update_option($this->option_name . '_has_inline_ent', $has_inline_ent);
			$emd_inline_ent_apps = get_option('emd_inline_entity_apps', Array());
			$emd_inline_ent_apps[$this->option_name] = $this->option_name . '_has_inline_ent';
			update_option('emd_inline_entity_apps', $emd_inline_ent_apps);
			//conf parameters for calendar
			//conf parameters for woocommerce
			$has_woocommerce = Array(
				'woo_ticket' => Array(
					'label' => 'Woo Ticket',
					'entity' => 'emd_ticket',
					'txn' => 'ticket_topic',
					'order_rel' => 1,
					'product_rel' => 1,
					'myaccount_before' => '',
					'myaccount_after' => '<h2>!#trans[Recent Tickets]#</h2>
!#shortcode[support_tickets filter="misc::author::is::current_user;"]#',
					'smanager_caps' => Array(
						'edit_emd_tickets',
						'delete_emd_tickets',
						'edit_others_emd_tickets',
						'publish_emd_tickets',
						'read_private_emd_tickets',
						'delete_private_emd_tickets',
						'delete_published_emd_tickets',
						'delete_others_emd_tickets',
						'edit_private_emd_tickets',
						'edit_published_emd_tickets',
						'set_author_emd_tickets',
						'edit_emd_canned_responses',
						'delete_emd_canned_responses',
						'edit_others_emd_canned_responses',
						'publish_emd_canned_responses',
						'read_private_emd_canned_responses',
						'delete_private_emd_canned_responses',
						'delete_published_emd_canned_responses',
						'delete_others_emd_canned_responses',
						'edit_private_emd_canned_responses',
						'edit_published_emd_canned_responses',
						'assign_ticket_priority',
						'assign_ticket_topic',
						'assign_ticket_status',
						'view_recent_tickets_dashboard'
					) ,
					'customer_caps' => Array(
						'limitby_author_frontend_emd_tickets'
					) ,
					'order_term' => 'Order',
					'order_type' => 'one-to-many',
					'order_from' => 'Tickets',
					'order_to' => 'Orders',
					'order_box' => 'any',
					'order_layout' => '<tr>
<td> </td>
<td><a href="!#woo_order_link#" title="!#woo_order_id#">!#woo_order_id#</a></td>
<td>!#woo_order_date#</td>
<td>!#woo_order_status#</td>
<td>!#woo_order_total#</td>
<td>!#woo_order_products_ol#</td>
</tr>',
					'order_header' => '<div class="panel panel-default" style="overflow:visible">
  <div class="panel-heading">
    <div class="panel-title"><a class="accor-title-link collapsed" data-parent="#accordion" data-toggle="collapse" href="#collapse-woo-orders">!#trans[Related Order]#</a></div>
  </div>
  <div class="panel-collapse out collapse" id="collapse-woo-orders">
    <div class="panel-body" data-has-attrib="false">
<table id="table-ID" class="table emd-table" data-toggle="table" data-search="true" data-click-to-select="true" data-show-columns="true" data-show-export="true" data-pagination="true">
<thead>
<th data-checkbox="true" data-field="state"></th>
<th data-sortable="true">!#trans[ID]#</th>
<th data-sortable="true">!#trans[Date]#</th>
<th data-sortable="true">!#trans[Status]#</th>
<th data-sortable="true">!#trans[Total]#</th>
<th>!#trans[Product(s)]#</th>
</tr>
</thead>
<tbody>',
					'order_footer' => '</tbody>      
</table>
    </div>
  </div>
</div>',
					'recent_orders_label' => 'Open Ticket',
					'recent_orders_url' => '/open-a-ticket',
					'product_term' => 'Presales',
					'product_type' => 'many-to-many',
					'product_from' => 'Tickets',
					'product_to' => 'Products',
					'product_box' => 'any',
					'product_layout' => '        <tr>
<td> </td>
          <td>!#woo_product_image_thumb#</td>
          <td>
            <a href="!#woo_product_link#" title="!#woo_product_title#">!#woo_product_id#</a>
          </td>
          <td>!#woo_product_title#</td>
          <td>!#woo_product_sku#</td>
          <td>!#woo_product_price#</td>
        </tr>',
					'product_header' => '<div class="panel panel-default" style="overflow:visible">
  <div class="panel-heading">
    <div class="panel-title"><a class="accor-title-link collapsed" data-parent="#accordion" data-toggle="collapse" href="#collapse-woo-products">!#trans[Related Products]#</a></div>
  </div>
  <div class="panel-collapse out collapse" id="collapse-woo-products">
    <div class="panel-body" data-has-attrib="false">
<table id="table-products" class="table emd-table" data-toggle="table" data-search="true" data-click-to-select="true" data-show-columns="true" data-show-export="true" data-pagination="true">
<thead>
        <tr>
          <th data-checkbox="true" data-field="state"></th>
          <th>!#trans[Image]#</th>
          <th data-sortable="true">!#trans[ID]#</th>
          <th data-sortable="true">!#trans[Title]#</th>
          <th data-sortable="true">!#trans[Sku]#</th>
          <th data-sortable="true">!#trans[Price]#</th>
        </tr>
</thead>
<tbody>',
					'product_footer' => '   </tbody>  
   </table>
    </div>
  </div>
</div>'
				)
			);
			update_option($this->option_name . '_has_woocommerce', $has_woocommerce);
			$woo_forms_list['submit_tickets']['rel_emd_ticket_woo_order'] = Array(
				'show' => 1,
				'row' => 3,
				'req' => 0,
				'size' => 12,
			);
			$woo_forms_list['submit_tickets']['rel_emd_ticket_woo_product'] = Array(
				'show' => 1,
				'row' => 4,
				'req' => 0,
				'size' => 12,
			);
			$woo_forms_list['search_tickets']['rel_emd_ticket_woo_order'] = Array(
				'show' => 1,
				'row' => 3,
				'req' => 0,
				'size' => 12,
			);
			$woo_forms_list['search_tickets']['rel_emd_ticket_woo_product'] = Array(
				'show' => 1,
				'row' => 4,
				'req' => 0,
				'size' => 12,
			);
			update_option($this->option_name . '_has_woocommerce_forms_list', $woo_forms_list);
			//conf parameters for woocommerce
			$has_edd = Array(
				'edd_ticket' => Array(
					'label' => 'Easy Digital Downloads',
					'entity' => 'emd_ticket',
					'txn' => 'ticket_topic',
					'order_rel' => 1,
					'product_rel' => 1,
					'myaccount_before' => '',
					'myaccount_after' => '<h2>!#trans[Recent Tickets]#</h2>
!#shortcode[support_tickets filter="misc::author::is::current_user;"]#',
					'smanager_caps' => Array(
						'view_wp_ticket_com_dashboard',
						'edit_emd_tickets',
						'delete_emd_tickets',
						'edit_others_emd_tickets',
						'publish_emd_tickets',
						'read_private_emd_tickets',
						'delete_private_emd_tickets',
						'delete_published_emd_tickets',
						'delete_others_emd_tickets',
						'edit_private_emd_tickets',
						'edit_published_emd_tickets',
						'set_author_emd_tickets',
						'edit_emd_canned_responses',
						'delete_emd_canned_responses',
						'edit_others_emd_canned_responses',
						'publish_emd_canned_responses',
						'read_private_emd_canned_responses',
						'delete_private_emd_canned_responses',
						'delete_published_emd_canned_responses',
						'delete_others_emd_canned_responses',
						'edit_private_emd_canned_responses',
						'edit_published_emd_canned_responses',
						'assign_ticket_priority',
						'assign_ticket_topic',
						'assign_ticket_status',
						'view_recent_tickets_dashboard'
					) ,
					'sacc_caps' => Array() ,
					'svendor_caps' => Array() ,
					'sworker_caps' => Array(
						'edit_emd_tickets',
						'delete_emd_tickets',
						'edit_others_emd_tickets',
						'publish_emd_tickets',
						'read_private_emd_tickets',
						'delete_private_emd_tickets',
						'delete_published_emd_tickets',
						'edit_private_emd_tickets',
						'edit_published_emd_tickets',
						'limitby_author_backend_emd_tickets',
						'limitby_author_frontend_emd_tickets',
						'edit_emd_canned_responses',
						'delete_emd_canned_responses',
						'edit_others_emd_canned_responses',
						'publish_emd_canned_responses',
						'read_private_emd_canned_responses',
						'delete_private_emd_canned_responses',
						'delete_published_emd_canned_responses',
						'edit_private_emd_canned_responses',
						'edit_published_emd_canned_responses',
						'assign_ticket_priority',
						'assign_ticket_topic',
						'assign_ticket_status',
						'view_recent_tickets_dashboard'
					) ,
					'order_term' => 'Order',
					'order_type' => 'one-to-many',
					'order_from' => 'Tickets',
					'order_to' => 'Orders',
					'order_box' => 'any',
					'order_layout' => '<tr>
<td> </td>
<td><a href="!#edd_order_link#" title="!#edd_order_id#">!#edd_order_id#</a></td>
<td>!#edd_order_date#</td>
<td>!#edd_order_status#</td>
<td>!#edd_order_total#</td>
<td>!#edd_order_downloads_ol#</td>
</tr>',
					'order_header' => '<div class="panel panel-default" style="overflow:visible">
  <div class="panel-heading">
    <div class="panel-title"><a class="accor-title-link collapsed" data-parent="#accordion" data-toggle="collapse" href="#collapse-edd-orders">!#trans[Related Order]#</a></div>
  </div>
  <div class="panel-collapse out collapse" id="collapse-edd-orders">
    <div class="panel-body" data-has-attrib="false">
<table id="table-ID" class="table emd-table" data-toggle="table" data-search="true" data-click-to-select="true" data-show-columns="true" data-show-export="true" data-pagination="true">
<thead>
<th data-checkbox="true" data-field="state"></th>
<th data-sortable="true">!#trans[ID]#</th>
<th data-sortable="true">!#trans[Date]#</th>
<th data-sortable="true">!#trans[Status]#</th>
<th data-sortable="true">!#trans[Total]#</th>
<th>!#trans[Product(s)]#</th>
</tr>
</thead>
<tbody>',
					'order_footer' => '</tbody>      
</table>
    </div>
  </div>
</div>',
					'purchase_history_label' => 'Open Ticket',
					'purchase_history_url' => '/open-a-ticket',
					'product_term' => 'Presales',
					'product_type' => 'many-to-many',
					'product_from' => 'Tickets',
					'product_to' => 'Downloads',
					'product_box' => 'any',
					'product_layout' => '        <tr>
          <td> </td>
          <td>!#edd_download_image_thumb#</td>
          <td>
            <a href="!#edd_download_link#" title="!#edd_download_title#">!#edd_download_id#</a>
          </td>
          <td>!#edd_download_title#</td>
          <td>!#edd_download_sku#</td>
          <td>!#edd_download_price#</td>
        </tr>',
					'product_header' => '<div class="panel panel-default" style="overflow:visible">
  <div class="panel-heading">
    <div class="panel-title"><a class="accor-title-link collapsed" data-parent="#accordion" data-toggle="collapse" href="#collapse-edd-products">!#trans[Related Products]#</a></div>
  </div>
  <div class="panel-collapse out collapse" id="collapse-edd-products">
    <div class="panel-body" data-has-attrib="false">
<table id="table-products" class="table emd-table" data-toggle="table" data-search="true" data-click-to-select="true" data-show-columns="true" data-show-export="true" data-pagination="true">
<thead>
        <tr>
          <th data-checkbox="true" data-field="state"></th>
          <th>!#trans[Image]#</th>
          <th data-sortable="true">!#trans[ID]#</th>
          <th data-sortable="true">!#trans[Title]#</th>
          <th data-sortable="true">!#trans[Sku]#</th>
          <th data-sortable="true">!#trans[Price]#</th>
        </tr>
</thead>
<tbody>',
					'product_footer' => '  </tbody>  
   </table>
    </div>
  </div>
</div>'
				)
			);
			update_option($this->option_name . '_has_edd', $has_edd);
			$edd_forms_list['submit_tickets']['rel_emd_ticket_edd_order'] = Array(
				'show' => 1,
				'row' => 5,
				'req' => 0,
				'size' => 12,
			);
			$edd_forms_list['submit_tickets']['rel_emd_ticket_edd_product'] = Array(
				'show' => 1,
				'row' => 6,
				'req' => 0,
				'size' => 12,
			);
			$edd_forms_list['search_tickets']['rel_emd_ticket_edd_order'] = Array(
				'show' => 1,
				'row' => 5,
				'req' => 0,
				'size' => 12,
			);
			$edd_forms_list['search_tickets']['rel_emd_ticket_edd_product'] = Array(
				'show' => 1,
				'row' => 6,
				'req' => 0,
				'size' => 12,
			);
			update_option($this->option_name . '_has_edd_forms_list', $edd_forms_list);
			//conf parameters for ldap
			$has_ldap = Array(
				'agent_ldap' => 'emd_agent'
			);
			update_option($this->option_name . '_has_ldap', $has_ldap);
			//conf parameters for mailchimp
			$has_mailchimp = Array(
				'submit_tickets' => Array(
					'entity' => 'emd_ticket',
					'tax' => Array(
						'ticket_topic'
					)
				)
			);
			update_option($this->option_name . '_has_mailchimp', $has_mailchimp);
			//action to configure different extension conf parameters for this plugin
			do_action('emd_ext_set_conf', 'wp-ticket-com');
		}
		/**
		 * Reset app specific options
		 *
		 * @since WPAS 4.0
		 *
		 */
		private function reset_options() {
			delete_option($this->option_name . '_shc_list');
			$incemail_settings = get_option('emd_inc_email_apps', Array());
			unset($incemail_settings[$this->option_name]);
			update_option('emd_inc_email_apps', $incemail_settings);
			delete_option($this->option_name . '_has_incoming_email');
			$emd_inline_ent_apps = get_option('emd_inline_entity_apps', Array());
			unset($emd_inline_ent_apps[$this->option_name]);
			update_option('emd_inline_entity_apps', $emd_inline_ent_apps);
			delete_option($this->option_name . '_has_inline_ent');
			delete_option($this->option_name . '_has_edd');
			delete_option($this->option_name . '_has_ldap');
			delete_option($this->option_name . '_has_mailchimp');
			do_action('emd_ext_reset_conf', 'wp-ticket-com');
		}
		/**
		 * Show admin notices
		 *
		 * @since WPAS 4.0
		 *
		 * @return html
		 */
		public function install_notice() {
			if (isset($_GET[$this->option_name . '_adm_notice1'])) {
				update_option($this->option_name . '_adm_notice1', true);
			}
			if (current_user_can('manage_options') && get_option($this->option_name . '_adm_notice1') != 1) {
?>
<div class="updated">
<?php
				printf('<p><a href="%1s" target="_blank"> %2$s </a>%3$s<a style="float:right;" href="%4$s"><span class="dashicons dashicons-dismiss" style="font-size:15px;"></span>%5$s</a></p>', 'https://docs.emdplugins.com/docs/wp-ticket-community-documentation/?pk_campaign=wpticket&pk_source=plugin&pk_medium=link&pk_content=notice', __('New To WP Ticket? Review the documentation!', 'wpas') , __('&#187;', 'wpas') , esc_url(add_query_arg($this->option_name . '_adm_notice1', true)) , __('Dismiss', 'wpas'));
?>
</div>
<?php
			}
			if (isset($_GET[$this->option_name . '_adm_notice2'])) {
				update_option($this->option_name . '_adm_notice2', true);
			}
			if (current_user_can('manage_options') && get_option($this->option_name . '_adm_notice2') != 1) {
?>
<div class="updated">
<?php
				printf('<p><a href="%1s" target="_blank"> %2$s </a>%3$s<a style="float:right;" href="%4$s"><span class="dashicons dashicons-dismiss" style="font-size:15px;"></span>%5$s</a></p>', 'https://emdplugins.com/plugins/wp-ticket-wordpress-plugin/?pk_campaign=wpticket&pk_source=plugin&pk_medium=link&pk_content=notice', __('Get More Features You Need to Provide the Best Customer Support!', 'wpas') , __('&#187;', 'wpas') , esc_url(add_query_arg($this->option_name . '_adm_notice2', true)) , __('Dismiss', 'wpas'));
?>
</div>
<?php
			}
			if (current_user_can('manage_options') && get_option($this->option_name . '_setup_pages') == 1) {
				echo "<div id=\"message\" class=\"updated\"><p><strong>" . __('Welcome to Wp Ticket', 'wp-ticket-com') . "</strong></p>
           <p class=\"submit\"><a href=\"" . add_query_arg('setup_wp_ticket_com_pages', 'true', admin_url('index.php')) . "\" class=\"button-primary\">" . __('Setup Wp Ticket Pages', 'wp-ticket-com') . "</a> <a class=\"skip button-primary\" href=\"" . add_query_arg('skip_setup_wp_ticket_com_pages', 'true', admin_url('index.php')) . "\">" . __('Skip setup', 'wp-ticket-com') . "</a></p>
         </div>";
			}
		}
		/**
		 * Setup pages for components and redirect to dashboard
		 *
		 * @since WPAS 4.0
		 *
		 */
		public function setup_pages() {
			if (!is_admin()) {
				return;
			}
			if (!empty($_GET['setup_' . $this->option_name . '_pages'])) {
				$shc_list = get_option($this->option_name . '_shc_list');
				emd_create_install_pages($this->option_name, $shc_list);
				update_option($this->option_name . '_setup_pages', 2);
				wp_redirect(admin_url('admin.php?page=' . $this->option_name . '_settings&wp-ticket-com-installed=true'));
				exit;
			}
			if (!empty($_GET['skip_setup_' . $this->option_name . '_pages'])) {
				update_option($this->option_name . '_setup_pages', 2);
				wp_redirect(admin_url('admin.php?page=' . $this->option_name . '_settings'));
				exit;
			}
		}
		/**
		 * Delete file attachments when a post is deleted
		 *
		 * @since WPAS 4.0
		 * @param $pid
		 *
		 * @return bool
		 */
		public function delete_post_file_att($pid) {
			$entity_fields = get_option($this->option_name . '_attr_list');
			$post_type = get_post_type($pid);
			if (!empty($entity_fields[$post_type])) {
				//Delete fields
				foreach (array_keys($entity_fields[$post_type]) as $myfield) {
					if (in_array($entity_fields[$post_type][$myfield]['display_type'], Array(
						'file',
						'image',
						'plupload_image',
						'thickbox_image'
					))) {
						$pmeta = get_post_meta($pid, $myfield);
						if (!empty($pmeta)) {
							foreach ($pmeta as $file_id) {
								//check if this file is used for another post
								$fargs = array(
									'meta_query' => array(
										array(
											'key' => $myfield,
											'value' => $file_id,
											'compare' => '=',
										)
									) ,
									'fields' => 'ids',
									'post_type' => $post_type,
									'posts_per_page' => - 1,
								);
								$fquery = new WP_Query($fargs);
								if (empty($fquery->posts)) {
									wp_delete_attachment($file_id);
								}
							}
						}
					}
				}
			}
			return true;
		}
		public function tinymce_fix($init) {
			global $post;
			$ent_list = get_option($this->option_name . '_ent_list', Array());
			if (!empty($post) && in_array($post->post_type, array_keys($ent_list))) {
				$init['wpautop'] = false;
				$init['indent'] = true;
			}
			return $init;
		}
	}
endif;
return new Wp_Ticket_Com_Install_Deactivate();
