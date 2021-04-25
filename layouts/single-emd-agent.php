<?php $ent_attrs = get_option('wp_ticket_com_attr_list'); ?>
<div class="emd-container">
<?php
if (emd_is_item_visible('emd_agent_photo', 'wp_ticket_com', 'attribute')) {
	$images = emd_mb_meta('emd_agent_photo', 'type=image');
	if (!empty($images)) { ?>
    <div id="emd-agent-emd-agent-photo-div" class="emd-single-div">
    <div id="emd-agent-emd-agent-photo-key" class="emd-single-title">
    <?php _e('Photo', 'wp-ticket-com'); ?>
    </div>
    <div id="emd-agent-emd-agent-photo-val" class="emd-single-val">
    <?php foreach ($images as $image) { ?>
    <a href='<?php echo esc_html($image['full_url']); ?>' title='<?php echo esc_attr($image['title']); ?>' rel='thickbox'>
    <img src='<?php echo esc_url($image['url']); ?>' width='<?php echo esc_attr($image['width']); ?>' height='<?php echo esc_attr($image['height']); ?>' alt='<?php echo esc_attr($image['alt']); ?>' />
    </a>
    <?php
		} ?>
    </div>
    </div>
<?php
	}
}
?>
<?php
if (emd_is_item_visible('emd_agent_first_name', 'wp_ticket_com', 'attribute')) {
	$emd_agent_first_name = emd_mb_meta('emd_agent_first_name');
	if (!empty($emd_agent_first_name)) { ?>
   <div id="emd-agent-emd-agent-first-name-div" class="emd-single-div">
   <div id="emd-agent-emd-agent-first-name-key" class="emd-single-title">
<?php _e('First Name', 'wp-ticket-com'); ?>
   </div>
   <div id="emd-agent-emd-agent-first-name-val" class="emd-single-val">
<?php echo $emd_agent_first_name; ?>
   </div>
   </div>
<?php
	}
}
?>
<?php
if (emd_is_item_visible('emd_agent_last_name', 'wp_ticket_com', 'attribute')) {
	$emd_agent_last_name = emd_mb_meta('emd_agent_last_name');
	if (!empty($emd_agent_last_name)) { ?>
   <div id="emd-agent-emd-agent-last-name-div" class="emd-single-div">
   <div id="emd-agent-emd-agent-last-name-key" class="emd-single-title">
<?php _e('Last Name', 'wp-ticket-com'); ?>
   </div>
   <div id="emd-agent-emd-agent-last-name-val" class="emd-single-val">
<?php echo $emd_agent_last_name; ?>
   </div>
   </div>
<?php
	}
}
?>
<?php
if (emd_is_item_visible('emd_agent_userid', 'wp_ticket_com', 'attribute')) {
	$emd_agent_userid = emd_mb_meta('emd_agent_userid');
	if (!empty($emd_agent_userid)) { ?>
   <div id="emd-agent-emd-agent-userid-div" class="emd-single-div">
   <div id="emd-agent-emd-agent-userid-key" class="emd-single-title">
<?php _e('Agent User', 'wp-ticket-com'); ?>
   </div>
   <div id="emd-agent-emd-agent-userid-val" class="emd-single-val">
<?php echo $emd_agent_userid; ?>
   </div>
   </div>
<?php
	}
}
?>
<?php
if (emd_is_item_visible('emd_agent_email', 'wp_ticket_com', 'attribute')) {
	$emd_agent_email = emd_mb_meta('emd_agent_email');
	if (!empty($emd_agent_email)) { ?>
   <div id="emd-agent-emd-agent-email-div" class="emd-single-div">
   <div id="emd-agent-emd-agent-email-key" class="emd-single-title">
<?php _e('Email', 'wp-ticket-com'); ?>
   </div>
   <div id="emd-agent-emd-agent-email-val" class="emd-single-val">
<?php echo $emd_agent_email; ?>
   </div>
   </div>
<?php
	}
}
?>
<?php
if (emd_is_item_visible('emd_agent_phone', 'wp_ticket_com', 'attribute')) {
	$emd_agent_phone = emd_mb_meta('emd_agent_phone');
	if (!empty($emd_agent_phone)) { ?>
   <div id="emd-agent-emd-agent-phone-div" class="emd-single-div">
   <div id="emd-agent-emd-agent-phone-key" class="emd-single-title">
<?php _e('Phone', 'wp-ticket-com'); ?>
   </div>
   <div id="emd-agent-emd-agent-phone-val" class="emd-single-val">
<?php echo $emd_agent_phone; ?>
   </div>
   </div>
<?php
	}
}
?>
<?php
if (emd_is_item_visible('emd_agent_phone_ext', 'wp_ticket_com', 'attribute')) {
	$emd_agent_phone_ext = emd_mb_meta('emd_agent_phone_ext');
	if (!empty($emd_agent_phone_ext)) { ?>
   <div id="emd-agent-emd-agent-phone-ext-div" class="emd-single-div">
   <div id="emd-agent-emd-agent-phone-ext-key" class="emd-single-title">
<?php _e('Extension', 'wp-ticket-com'); ?>
   </div>
   <div id="emd-agent-emd-agent-phone-ext-val" class="emd-single-val">
<?php echo $emd_agent_phone_ext; ?>
   </div>
   </div>
<?php
	}
}
?>
<?php
if (emd_is_item_visible('emd_agent_mobile', 'wp_ticket_com', 'attribute')) {
	$emd_agent_mobile = emd_mb_meta('emd_agent_mobile');
	if (!empty($emd_agent_mobile)) { ?>
   <div id="emd-agent-emd-agent-mobile-div" class="emd-single-div">
   <div id="emd-agent-emd-agent-mobile-key" class="emd-single-title">
<?php _e('Mobile', 'wp-ticket-com'); ?>
   </div>
   <div id="emd-agent-emd-agent-mobile-val" class="emd-single-val">
<?php echo $emd_agent_mobile; ?>
   </div>
   </div>
<?php
	}
}
?>
<?php
$cust_fields = get_metadata('post', get_the_ID());
$real_cust_fields = Array();
$ent_map_list = get_option('wp_ticket_com_ent_map_list', Array());
foreach ($cust_fields as $ckey => $cval) {
	if (empty($ent_attrs['emd_agent'][$ckey]) && !preg_match('/^(_|wpas_|emd_)/', $ckey)) {
		$cust_key = str_replace('-', '_', sanitize_title($ckey));
		if (!empty($ent_map_list) && empty($ent_map_list['emd_agent']['cust_fields'][$cust_key])) {
			$real_cust_fields[$ckey] = $cval;
		}
	}
}
if (!empty($real_cust_fields)) {
	$fcount = 0;
	foreach ($real_cust_fields as $rkey => $rval) {
		$val = implode($rval, " ");
		$fcount++;
?>
<div id="cust-field-<?php echo $fcount; ?>-div" class="emd-single-div">
<div id="cust-field-<?php echo $fcount; ?>-key" class="emd-single-title">
<?php echo $rkey; ?>
</div>
   <div id="cust-field-<?php echo $fcount; ?>-val" class="emd-single-val">
<?php echo $val; ?>
</div>
</div>
<?php
	}
}
?>
<div id="emd_agent-emd_ticket-relation-sec" class="relation-sec"><div class='connected-div' id='rel-tickets-assigned-to-connected'>
						<div class='connected-title' id='rel-tickets-assigned-to-connected-title'><?php echo __('Tickets Assigned', 'wp-ticket-com'); ?></div>
<?php $post = get_post();
$rel_filter = "";
$res = emd_get_p2p_connections('connected', 'tickets_assigned_to', 'std', $post, 0, 0, '', 'wp_ticket_com', $rel_filter);
$rel_list = get_option('wp_ticket_com_rel_list');
echo $res['before_list'];
$real_post = $post;
$rel_count_id = 1;
$rel_eds = Array();
foreach ($res['rels'] as $myrel) {
	$post = $myrel;
	echo $res['before_item']; ?>
<a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title(); ?>"><?php echo get_the_title(); ?></a><?php
	echo $res['after_item'];
	$rel_count_id++;
}
$post = $real_post;
echo $res['after_list'];
?>
</div></div>
</div><!--container-end-->