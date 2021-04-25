<?php
/**
 * Settings Glossary Functions
 *
 * @package WP_TICKET_COM
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
add_action('wp_ticket_com_settings_glossary', 'wp_ticket_com_settings_glossary');
/**
 * Display glossary information
 * @since WPAS 4.0
 *
 * @return html
 */
function wp_ticket_com_settings_glossary() {
	global $title;
?>
<div class="wrap">
<h2><?php echo $title; ?></h2>
<p><?php _e('WP Ticket enables support staff to receive, process, and respond to service requests efficiently and effectively.', 'wp-ticket-com'); ?></p>
<p><?php _e('The below are the definitions of entities, attributes, and terms included in Wp Ticket.', 'wp-ticket-com'); ?></p>
<div id="glossary" class="accordion-container">
<ul class="outer-border">
<li id="emd_agent" class="control-section accordion-section">
<h3 class="accordion-section-title hndle" tabindex="2"><?php _e('Agents', 'wp-ticket-com'); ?></h3>
<div class="accordion-section-content">
<div class="inside">
<table class="form-table"><p class"lead"><?php _e('Agents are employees from the company that addresses issues to the customer\'s satisfaction.', 'wp-ticket-com'); ?></p><tr><th style='font-size: 1.1em;color:cadetblue;border-bottom: 1px dashed;padding-bottom: 10px;' colspan=2><div><?php _e('Attributes', 'wp-ticket-com'); ?></div></th></tr>
<tr>
<th><?php _e('Photo', 'wp-ticket-com'); ?></th>
<td><?php _e('Photo of the staff member. Photo does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('First Name', 'wp-ticket-com'); ?></th>
<td><?php _e('First name of the staff member. First Name is a required field. Being a unique identifier, it uniquely distinguishes each instance of Agent entity. First Name is filterable in the admin area. First Name does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('Last Name', 'wp-ticket-com'); ?></th>
<td><?php _e('Last name of the staff member. Last Name is a required field. Being a unique identifier, it uniquely distinguishes each instance of Agent entity. Last Name is filterable in the admin area. Last Name does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('Agent User', 'wp-ticket-com'); ?></th>
<td><?php _e('System user assigned to the staff member. Agent User is a required field. Being a unique identifier, it uniquely distinguishes each instance of Agent entity. Agent User does not have a default value. Only users who have the following access roles are listed as allowable value; <ul style="list-style: square outside none;padding: 30px;"><li>manager</li><li>agent</li></ul>', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('Email', 'wp-ticket-com'); ?></th>
<td><?php _e('Email address of the staff member. Email is filterable in the admin area. Email does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('Phone', 'wp-ticket-com'); ?></th>
<td><?php _e('Phone number of the staff member. Phone is filterable in the admin area. Phone does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('Extension', 'wp-ticket-com'); ?></th>
<td><?php _e('Phone number extension of the staff member. Extension is filterable in the admin area. Extension does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('Mobile', 'wp-ticket-com'); ?></th>
<td><?php _e('Mobile phone number of the staff member. Mobile is filterable in the admin area. Mobile does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr><tr><th style='font-size: 1.1em;color:cadetblue;border-bottom: 1px dashed;padding-bottom: 10px;' colspan=2><div><?php _e('Relationships', 'wp-ticket-com'); ?></div></th></tr>
<tr>
<th><?php _e('Tickets Assigned', 'wp-ticket-com'); ?></th>
<td><?php _e('Allows to display and create connections with Tickets', 'wp-ticket-com'); ?>. <?php _e('One instance of Agents can associated with many instances of Tickets', 'wp-ticket-com'); ?>.  <?php _e('The relationship can be set up in the edit area of Tickets using Assignee relationship box', 'wp-ticket-com'); ?>. </td>
</tr></table>
</div>
</div>
</li><li id="emd_ticket" class="control-section accordion-section">
<h3 class="accordion-section-title hndle" tabindex="1"><?php _e('Tickets', 'wp-ticket-com'); ?></h3>
<div class="accordion-section-content">
<div class="inside">
<table class="form-table"><p class"lead"><?php _e('A tickets represents a help request.', 'wp-ticket-com'); ?></p><tr><th style='font-size: 1.1em;color:cadetblue;border-bottom: 1px dashed;padding-bottom: 10px;' colspan=2><div><?php _e('Attributes', 'wp-ticket-com'); ?></div></th></tr>
<tr>
<th><?php _e('Subject', 'wp-ticket-com'); ?></th>
<td><?php _e('Ideally, a question title should be a question. It\'s important that the question title is specific and has at least some meaning with no other information. A question such as "Why doesn\'t this work?" makes absolutely no sense without the rest of the question. Subject is a required field. Subject does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('Message', 'wp-ticket-com'); ?></th>
<td><?php _e('Describe the problem or question. Include all necessary details but no unnecessary ones. A short description is easier to understand and will save the reviewer time. Please avoid asking multiple questions in one ticket. Open a separate ticket so that we can better answer your question. Message is a required field. Message does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('Ticket ID', 'wp-ticket-com'); ?></th>
<td><?php _e('Unique identifier for a ticket Being a unique identifier, it uniquely distinguishes each instance of Ticket entity. Ticket ID is filterable in the admin area. Ticket ID does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('First Name', 'wp-ticket-com'); ?></th>
<td><?php _e(' First Name is a required field. First Name is filterable in the admin area. First Name does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('Last Name', 'wp-ticket-com'); ?></th>
<td><?php _e(' Last Name is filterable in the admin area. Last Name does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('Email', 'wp-ticket-com'); ?></th>
<td><?php _e('Our responses to your ticket will be sent to this email address. Email is a required field. Email is filterable in the admin area. Email does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('Phone', 'wp-ticket-com'); ?></th>
<td><?php _e('Please enter a phone number in case we need to contact you. Phone does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('Due', 'wp-ticket-com'); ?></th>
<td><?php _e('The due date of the ticket Due is filterable in the admin area. Due does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('Attachments', 'wp-ticket-com'); ?></th>
<td><?php _e('Attach related files to the ticket. Attachments does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('Form Name', 'wp-ticket-com'); ?></th>
<td><?php _e(' Form Name is filterable in the admin area. Form Name has a default value of <b>admin</b>.', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('Form Submitted By', 'wp-ticket-com'); ?></th>
<td><?php _e(' Form Submitted By is filterable in the admin area. Form Submitted By does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<th><?php _e('Form Submitted IP', 'wp-ticket-com'); ?></th>
<td><?php _e(' Form Submitted IP is filterable in the admin area. Form Submitted IP does not have a default value. ', 'wp-ticket-com'); ?></td>
</tr><tr><th style='font-size:1.1em;color:cadetblue;border-bottom: 1px dashed;padding-bottom: 10px;' colspan=2><div><?php _e('Taxonomies', 'wp-ticket-com'); ?></div></th></tr>
<tr>
<th><?php _e('Priority', 'wp-ticket-com'); ?></th>

<td><?php _e('When you create a ticket, you should prioritize the ticket based on the scope and impact of the request. Priority accepts multiple values like tags', 'wp-ticket-com'); ?>. <?php _e('Priority has a default value of:', 'wp-ticket-com'); ?> <?php _e(' uncategorized', 'wp-ticket-com'); ?>. <div class="taxdef-block"><p><?php _e('The following are the preset values and value descriptions for <b>Priority:</b>', 'wp-ticket-com'); ?></p>
<table class="table tax-table form-table"><tr><td><?php _e('Critical', 'wp-ticket-com'); ?></td>
<td><?php _e('A problem or issue impacting a significant group of customers or any mission critical issue affecting a single customer.', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<td><?php _e(' Major', 'wp-ticket-com'); ?></td>
<td><?php _e('Non critical but significant issue affecting a single user or an issue that is degrading the performance and reliability of supported services, however, the services are still operational. Support issues that could escalate to Critical if not addressed quickly.', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<td><?php _e('Normal', 'wp-ticket-com'); ?></td>
<td><?php _e('Routine support requests that impact a single user or non-critical software or hardware error.', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<td><?php _e('Minor', 'wp-ticket-com'); ?></td>
<td><?php _e('Work that has been scheduled in advance with the customer, a minor service issue, or general inquiry.', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<td><?php _e('Uncategorized', 'wp-ticket-com'); ?></td>
<td><?php _e('No priority assigned', 'wp-ticket-com'); ?></td>
</tr>
</table>
</div></td>
</tr>

<tr>
<th><?php _e('Topic', 'wp-ticket-com'); ?></th>

<td><?php _e('Topics are the categories for tickets. Topic accepts multiple values like tags', 'wp-ticket-com'); ?>. <?php _e('Topic has a default value of:', 'wp-ticket-com'); ?> <?php _e(' uncategorized', 'wp-ticket-com'); ?>. <div class="taxdef-block"><p><?php _e('The following are the preset values for <b>Topic:</b>', 'wp-ticket-com'); ?></p><p class="taxdef-values"><?php _e('Feature request', 'wp-ticket-com'); ?>, <?php _e('Task', 'wp-ticket-com'); ?>, <?php _e('Bug', 'wp-ticket-com'); ?>, <?php _e('Uncategorized', 'wp-ticket-com'); ?>, <?php _e('Order', 'wp-ticket-com'); ?>, <?php _e('Presales', 'wp-ticket-com'); ?></p></div></td>
</tr>

<tr>
<th><?php _e('Status', 'wp-ticket-com'); ?></th>

<td><?php _e(' Status accepts multiple values like tags', 'wp-ticket-com'); ?>. <?php _e('Status has a default value of:', 'wp-ticket-com'); ?> <?php _e(' open', 'wp-ticket-com'); ?>. <div class="taxdef-block"><p><?php _e('The following are the preset values and value descriptions for <b>Status:</b>', 'wp-ticket-com'); ?></p>
<table class="table tax-table form-table"><tr><td><?php _e('Open', 'wp-ticket-com'); ?></td>
<td><?php _e('This ticket is in the initial state, ready for the assignee to start work on it.', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<td><?php _e('In Progress', 'wp-ticket-com'); ?></td>
<td><?php _e('This ticket is being actively worked on at the moment.', 'wp-ticket-com'); ?></td>
</tr>
<tr>
<td><?php _e('Closed', 'wp-ticket-com'); ?></td>
<td><?php _e('This ticket is complete.', 'wp-ticket-com'); ?></td>
</tr>
</table>
</div></td>
</tr>
<tr><th style='font-size: 1.1em;color:cadetblue;border-bottom: 1px dashed;padding-bottom: 10px;' colspan=2><div><?php _e('Relationships', 'wp-ticket-com'); ?></div></th></tr>
<tr>
<th><?php _e('Assignee', 'wp-ticket-com'); ?></th>
<td><?php _e('Allows to display and create connections with Agents', 'wp-ticket-com'); ?>. <?php _e('One instance of Tickets can associated with <b>only one</b> instance of Agents', 'wp-ticket-com'); ?>.  <?php _e('The relationship can be set up in the edit area of Tickets using Assignee relationship box. ', 'wp-ticket-com'); ?> </td>
</tr></table>
</div>
</div>
</li>
</ul>
</div>
</div>
<?php
}
