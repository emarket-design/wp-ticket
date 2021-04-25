<?php global $support_tickets_count, $support_tickets_filter, $support_tickets_set_list;
$real_post = $post;
$ent_attrs = get_option('wp_ticket_com_attr_list');
?>
<tr>
<?php if (emd_is_item_visible('ent_ticket_id', 'wp_ticket_com', 'attribute', 1)) { ?>
<td class="search-results-row"><a href="<?php echo get_permalink(); ?>" target="_blank"><?php echo esc_html(emd_mb_meta('emd_ticket_id')); ?>
</a></td>
<?php
} ?>
<td class="search-results-row"><?php echo get_the_title(); ?></td>
<?php if (emd_is_item_visible('tax_ticket_status', 'wp_ticket_com', 'taxonomy', 1)) { ?>
<td class="search-results-row"><span class="ticket-tax <?php echo emd_get_tax_slugs(get_the_ID() , 'ticket_status') ?>"><?php echo emd_get_tax_vals(get_the_ID() , 'ticket_status', 1); ?></span></td>
<?php
} ?>
<?php if (emd_is_item_visible('tax_ticket_priority', 'wp_ticket_com', 'taxonomy', 1)) { ?>
<td class="search-results-row"><span class="ticket-tax <?php echo emd_get_tax_slugs(get_the_ID() , 'ticket_priority') ?>"><?php echo emd_get_tax_vals(get_the_ID() , 'ticket_priority', 1); ?></span></td>
<?php
} ?>
 <?php echo ((shortcode_exists('wpas_woo_order_woo_ticket')) ? "<td class=\"search-results-row\">" . do_shortcode("[wpas_woo_order_woo_ticket con_name='woo_ticket' app_name='wp_ticket_com' type='list_div' post= " . get_the_ID() . "]") . "</td>" : ""); ?>
 <?php echo ((shortcode_exists('wpas_woo_product_woo_ticket')) ? "<td class=\"search-results-row\">" . do_shortcode("[wpas_woo_product_woo_ticket con_name='woo_ticket' app_name='wp_ticket_com' type='list_ol' post= " . get_the_ID() . "]") . "</td>" : ""); ?>
<?php echo ((shortcode_exists('wpas_edd_order_edd_ticket')) ? "<td class=\"search-results-row\">" . do_shortcode("[wpas_edd_order_edd_ticket con_name='edd_ticket' app_name='wp_ticket_com' type='list_div' post= " . get_the_ID() . "]") . "</td>" : ""); ?>
 <?php echo ((shortcode_exists('wpas_edd_product_edd_ticket')) ? "<td class=\"search-results-row\">" . do_shortcode("[wpas_edd_product_edd_ticket con_name='edd_ticket' app_name='wp_ticket_com' type='list_ol' post= " . get_the_ID() . "]") . "</td>" : ""); ?>
<td class="search-results-row"><?php echo get_the_modified_date(); ?></td>
</tr>