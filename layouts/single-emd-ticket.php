<?php $real_post = $post;
$ent_attrs = get_option('wp_ticket_com_attr_list');
?>
<div id="single-emd-ticket-<?php echo get_the_ID(); ?>" class="emd-container emd-ticket-wrap single-wrap">
<?php $is_editable = 0; ?>
<div class="ticket-wrap">
	<div class="entry-title"><h1><?php echo get_the_title(); ?></h1></div>
	<div class="ticket-well well">
		<div class="row emd-buffer">
			<?php if (emd_is_item_visible('ent_ticket_id', 'wp_ticket_com', 'attribute')) { ?>
			<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-6">
						<strong><?php _e('Ticket ID', 'wp-ticket-com'); ?>:</strong>
					</div>
					<div class="col-sm-6">
						<div style="font-size:80%">
							<?php echo esc_html(emd_mb_meta('emd_ticket_id')); ?>

						</div>
					</div>
				</div>
			</div><?php
} ?> <?php if (emd_is_item_visible('tax_ticket_priority', 'wp_ticket_com', 'taxonomy')) { ?>
			<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-6">
						<strong><?php _e('Priority', 'wp-ticket-com'); ?>:</strong>
					</div>
					<div class="col-sm-6">
						<div class="ticket-tax <?php echo emd_get_tax_slugs(get_the_ID() , 'ticket_priority') ?>">
							<?php echo emd_get_tax_vals(get_the_ID() , 'ticket_priority', 1); ?>
						</div>
					</div>
				</div>
				</div><?php
} ?>
		</div>
		<div class="row emd-buffer">
			<?php if (emd_is_item_visible('tax_ticket_topic', 'wp_ticket_com', 'taxonomy')) { ?>
			<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-6">
						<strong><?php _e('Topic', 'wp-ticket-com'); ?>:</strong>
					</div>
					<div class="col-sm-6">
						<div class="ticket-tax <?php echo emd_get_tax_slugs(get_the_ID() , 'ticket_topic') ?>">
							<?php echo emd_get_tax_vals(get_the_ID() , 'ticket_topic', 1); ?>
						</div>
					</div>
				</div>
			</div><?php
} ?> <?php if (emd_is_item_visible('tax_ticket_status', 'wp_ticket_com', 'taxonomy')) { ?>
			<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-6">
						<strong><?php _e('Status', 'wp-ticket-com'); ?>:</strong>
					</div>
					<div class="col-sm-6">
						<div class="ticket-tax <?php echo emd_get_tax_slugs(get_the_ID() , 'ticket_status') ?>">
							<?php echo emd_get_tax_vals(get_the_ID() , 'ticket_status', 1); ?>
						</div>
					</div>
				</div>
			</div><?php
} ?>
		</div>
	</div>
	<div class="ticket-inner">
		<?php if (emd_is_item_visible('ent_ticket_first_name', 'wp_ticket_com', 'attribute')) { ?>
		<div class="row emd-buffer">
			<div class="col-sm-6">
				<strong><?php _e('First Name', 'wp-ticket-com'); ?>:</strong>
			</div>
			<div class="col-sm-6">
				<?php echo esc_html(emd_mb_meta('emd_ticket_first_name')); ?>

			</div>
		</div><?php
} ?> <?php if (emd_is_item_visible('ent_ticket_last_name', 'wp_ticket_com', 'attribute')) { ?>
		<div class="row emd-buffer">
			<div class="col-sm-6">
				<strong><?php _e('Last Name', 'wp-ticket-com'); ?>:</strong>
			</div>
			<div class="col-sm-6">
				<?php echo esc_html(emd_mb_meta('emd_ticket_last_name')); ?>

			</div>
		</div><?php
} ?> <?php if (emd_is_item_visible('ent_ticket_duedate', 'wp_ticket_com', 'attribute')) { ?>
		<div class="row emd-buffer">
			<div class="col-sm-6">
				<strong><?php _e('Due', 'wp-ticket-com'); ?>:</strong>
			</div>
			<div class="col-sm-6">
				<?php echo esc_html(emd_translate_date_format($ent_attrs['emd_ticket']['emd_ticket_duedate'], emd_mb_meta('emd_ticket_duedate') , 1)); ?>

			</div>
		</div><?php
} ?> <?php do_action("emd_frontend_display_cust_fields", "wp_ticket_com", "emd_ticket", $post->ID);
?> <?php if (emd_is_item_visible('ent_ticket_attachment', 'wp_ticket_com', 'attribute')) { ?>
		<div class="row emd-buffer">
			<div class="col-sm-6">
				<strong><?php _e('Attachments', 'wp-ticket-com'); ?>:</strong>
			</div>
			<div class="col-sm-6">
				<?php
	add_thickbox();
	$emd_mb_file = emd_mb_meta('emd_ticket_attachment', 'type=file');
	if (!empty($emd_mb_file)) {
		echo '<div class="clearfix">';
		foreach ($emd_mb_file as $info) {
			emd_get_attachment_layout($info);
		}
		echo '</div>';
	}
?>

			</div>
		</div><?php
} ?>
		<div class="ticket-content emd-buffer">
			<?php echo $post->post_content; ?>
		</div>
		<div class="emd-buffer">
			<div class="ticket-connections">
				<?php if (shortcode_exists('wpas_woo_order_woo_ticket')) {
	echo do_shortcode("[wpas_woo_order_woo_ticket con_name='woo_ticket' app_name='wp_ticket_com' type='layout' post= " . get_the_ID() . "]");
} ?>
 <?php if (shortcode_exists('wpas_woo_product_woo_ticket')) {
	echo do_shortcode("[wpas_woo_product_woo_ticket con_name='woo_ticket' app_name='wp_ticket_com' type='layout' post= " . get_the_ID() . "]");
} ?>
 <?php if (shortcode_exists('wpas_edd_order_edd_ticket')) {
	echo do_shortcode("[wpas_edd_order_edd_ticket con_name='edd_ticket' app_name='wp_ticket_com' type='layout' post= " . get_the_ID() . "]");
} ?>
 <?php if (shortcode_exists('wpas_edd_product_edd_ticket')) {
	echo do_shortcode("[wpas_edd_product_edd_ticket con_name='edd_ticket' app_name='wp_ticket_com' type='layout' post= " . get_the_ID() . "]");
} ?>

			</div>
		</div>
	</div>
</div>
</div><!--container-end-->