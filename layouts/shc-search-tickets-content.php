<?php global $search_tickets_count;
$ent_attrs = get_option('wp_ticket_com_attr_list'); ?>
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
<td class="search-results-row"><?php echo get_the_modified_date(); ?></td>
</tr>