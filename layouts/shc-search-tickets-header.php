<?php global $search_tickets_shc_count; ?><div class="search-results">
    <div style="margin:20px 0;border-bottom: 2px solid #add8e6;padding: 5px 0;">
<strong>Search Results</strong>
</div>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="search-results-header"><?php if (emd_is_item_visible('ent_ticket_id', 'wp_ticket_com', 'attribute', 1)) { ?>
<?php _e('Ticket ID', 'wp-ticket-com'); ?></th>
                <?php
} ?>

                <th class="search-results-header"><?php _e('Subject', 'wp-ticket-com'); ?></th>
                <?php if (emd_is_item_visible('tax_ticket_status', 'wp_ticket_com', 'taxonomy', 1)) { ?>

                <th class="search-results-header"><?php _e('Status', 'wp-ticket-com'); ?></th>
                <?php
} ?>
<?php if (emd_is_item_visible('tax_ticket_priority', 'wp_ticket_com', 'taxonomy', 1)) { ?>

                <th class="search-results-header"><?php _e('Priority', 'wp-ticket-com'); ?></th>
                <?php
} ?>

                <th class="search-results-header"><?php _e('Updated', 'wp-ticket-com'); ?></th>
            </tr>
        </thead>
        <tbody>