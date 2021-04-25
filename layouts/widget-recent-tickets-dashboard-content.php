<?php $real_post = $post;
$ent_attrs = get_option('wp_ticket_com_attr_list');
?>
<li>* <a title="<?php echo esc_html(emd_mb_meta('emd_ticket_id')); ?>
 - <?php echo get_the_date(); ?> - <?php echo get_the_time(); ?>" href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></li>
