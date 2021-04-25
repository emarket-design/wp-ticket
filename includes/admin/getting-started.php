<?php
/**
 * Getting Started
 *
 * @package WP_TICKET_COM
 * @since WPAS 5.3
 */
if (!defined('ABSPATH')) exit;
add_action('wp_ticket_com_getting_started', 'wp_ticket_com_getting_started');
/**
 * Display getting started information
 * @since WPAS 5.3
 *
 * @return html
 */
function wp_ticket_com_getting_started() {
	global $title;
	list($display_version) = explode('-', WP_TICKET_COM_VERSION);
?>
<style>
.about-wrap img{
max-height: 200px;
}
div.comp-feature {
    font-weight: 400;
    font-size:20px;
}
.edition-com {
    display: none;
}
.green{
color: #008000;
font-size: 30px;
}
#nav-compare:before{
    content: "\f179";
}
#emd-about .nav-tab-wrapper a:before{
    position: relative;
    box-sizing: content-box;
padding: 0px 3px;
color: #4682b4;
    width: 20px;
    height: 20px;
    overflow: hidden;
    white-space: nowrap;
    font-size: 20px;
    line-height: 1;
    cursor: pointer;
font-family: dashicons;
}
#nav-getting-started:before{
content: "\f102";
}
#nav-release-notes:before{
content: "\f348";
}
#nav-resources:before{
content: "\f118";
}
#nav-features:before{
content: "\f339";
}
#emd-about .embed-container { 
	position: relative; 
	padding-bottom: 56.25%;
	height: 0;
	overflow: hidden;
	max-width: 100%;
	height: auto;
	} 

#emd-about .embed-container iframe,
#emd-about .embed-container object,
#emd-about .embed-container embed { 
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	}
#emd-about ul li:before{
    content: "\f522";
    font-family: dashicons;
    font-size:25px;
 }
#gallery {
display: -webkit-box;
display: -ms-flexbox;
display: flex;
-ms-flex-wrap: wrap;
    flex-wrap: wrap;
}
#gallery .gallery-item {
	margin-top: 10px;
	margin-right: 10px;
	text-align: center;
        cursor:pointer;
}
#gallery img {
	border: 2px solid #cfcfcf; 
height: 405px; 
width: auto; 
}
#gallery .gallery-caption {
	margin-left: 0;
}
#emd-about .top{
text-decoration:none;
}
#emd-about .toc{
    background-color: #fff;
    padding: 25px;
    border: 1px solid #add8e6;
    border-radius: 8px;
}
#emd-about h3,
#emd-about h2{
    margin-top: 0px;
    margin-right: 0px;
    margin-bottom: 0.6em;
    margin-left: 0px;
}
#emd-about p,
#emd-about .emd-section li{
font-size:18px
}
#emd-about a.top:after{
content: "\f342";
    font-family: dashicons;
    font-size:25px;
text-decoration:none;
}
#emd-about .toc a,
#emd-about a.top{
vertical-align: top;
}
#emd-about li{
list-style-type: none;
line-height: normal;
}
#emd-about ol li {
    list-style-type: decimal;
}
#emd-about .quote{
    background: #fff;
    border-left: 4px solid #088cf9;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    margin-top: 25px;
    padding: 1px 12px;
}
#emd-about .tooltip{
    display: inline;
    position: relative;
}
#emd-about .tooltip:hover:after{
    background: #333;
    background: rgba(0,0,0,.8);
    border-radius: 5px;
    bottom: 26px;
    color: #fff;
    content: 'Click to enlarge';
    left: 20%;
    padding: 5px 15px;
    position: absolute;
    z-index: 98;
    width: 220px;
}
</style>

<?php add_thickbox(); ?>
<div id="emd-about" class="wrap about-wrap">
<div id="emd-header" style="padding:10px 0" class="wp-clearfix">
<div style="float:right"><img src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/wp_ticket_logo.gif"; ?>"></div>
<div style="margin: .2em 200px 0 0;padding: 0;color: #32373c;line-height: 1.2em;font-size: 2.8em;font-weight: 400;">
<?php printf(__('Welcome to WP Ticket Community %s', 'wp-ticket-com') , $display_version); ?>
</div>

<p class="about-text">
<?php printf(__("For effective and efficient service request management with WPAS extension support", 'wp-ticket-com') , $display_version); ?>
</p>
<div style="display: inline-block;"><a style="height: 50px; background:#ff8484;padding:10px 12px;color:#ffffff;text-align: center;font-weight: bold;line-height: 50px; font-family: Arial;border-radius: 6px; text-decoration: none;" href="https://emdplugins.com/plugin-pricing/wp-ticket-wordpress-plugin-pricing/?pk_campaign=wp-ticket-com-upgradebtn&amp;pk_kwd=wp-ticket-com-resources"><?php printf(__('Upgrade Now', 'wp-ticket-com') , $display_version); ?></a></div>
<div style="display: inline-block;margin-bottom: 20px;"><a style="height: 50px; background:#f0ad4e;padding:10px 12px;color:#ffffff;text-align: center;font-weight: bold;line-height: 50px; font-family: Arial;border-radius: 6px; text-decoration: none;" href="https://wpticketpro.emdplugins.com/?pk_campaign=wp-ticket-com-buybtn&amp;pk_kwd=wp-ticket-com-resources"><?php printf(__('Visit Pro Demo Site', 'wp-ticket-com') , $display_version); ?></a></div>
<?php
	$tabs['getting-started'] = __('Getting Started', 'wp-ticket-com');
	$tabs['release-notes'] = __('Release Notes', 'wp-ticket-com');
	$tabs['resources'] = __('Resources', 'wp-ticket-com');
	$tabs['features'] = __('Features', 'wp-ticket-com');
	$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'getting-started';
	echo '<h2 class="nav-tab-wrapper wp-clearfix">';
	foreach ($tabs as $ktab => $mytab) {
		$tab_url[$ktab] = esc_url(add_query_arg(array(
			'tab' => $ktab
		)));
		$active = "";
		if ($active_tab == $ktab) {
			$active = "nav-tab-active";
		}
		echo '<a href="' . esc_url($tab_url[$ktab]) . '" class="nav-tab ' . $active . '" id="nav-' . $ktab . '">' . $mytab . '</a>';
	}
	echo '</h2>';
?>
<?php echo '<div class="tab-content" id="tab-getting-started"';
	if ("getting-started" != $active_tab) {
		echo 'style="display:none;"';
	}
	echo '>';
?>
<div style="height:25px" id="rtop"></div><div class="toc"><h3 style="color:#0073AA;text-align:left;">Quickstart</h3><ul><li><a href="#gs-sec-180">Live Demo Site</a></li>
<li><a href="#gs-sec-273">Need Help?</a></li>
<li><a href="#gs-sec-274">Learn More</a></li>
<li><a href="#gs-sec-272">Installation, Configuration & Customization Service</a></li>
<li><a href="#gs-sec-155">WP Ticket Community Introduction</a></li>
<li><a href="#gs-sec-157">CSV Import Export Addon helps you get your data in and out of WordPress quickly, saving you ton of time</a></li>
<li><a href="#gs-sec-156">Smart Search Addon for finding what's important faster</a></li>
<li><a href="#gs-sec-161">Active Directory/LDAP Addon</a></li>
<li><a href="#gs-sec-162">MailChimp Addon for building email list through WP Ticket Community</a></li>
<li><a href="#gs-sec-160">Incoming Email WordPress Plugin - Create support tickets from emails</a></li>
<li><a href="#gs-sec-163">WP Ticket Pro - best for growing support teams</a></li>
<li><a href="#gs-sec-164">WP Ticket Enterprise - best for larger support teams</a></li>
</ul></div><div class="quote">
<p class="about-description">The secret of getting ahead is getting started - Mark Twain</p>
</div>
<div id="gs-sec-180"></div><div style="color:white;background:#0000003b;padding:5px 10px;font-size: 1.4em;font-weight: 600;">Live Demo Site</div><div class="changelog emd-section getting-started-180" style="margin:0;background-color:white;padding:10px"><div id="gallery"></div><div class="sec-desc"><p>Feel free to check out our <a target="_blank" href="https://wpticketcom.emdplugins.com//?pk_campaign=wp-ticket-com-gettingstarted&pk_kwd=wp-ticket-com-livedemo">live demo site</a> to learn how to use WP Ticket Community starter edition. The demo site will always have the latest version installed.</p>
<p>You can also use the demo site to identify possible issues. If the same issue exists in the demo site, open a support ticket and we will fix it. If a WP Ticket Community feature is not functioning or displayed correctly in your site but looks and works properly in the demo site, it means the theme or a third party plugin or one or more configuration parameters of your site is causing the issue.</p>
<p>If you'd like us to identify and fix the issues specific to your site, purchase a work order to get started.</p>
<p><a target="_blank" style="
    padding: 16px;
    background: coral;
    border: 1px solid lightgray;
    border-radius: 12px;
    text-decoration: none;
    color: white;
    margin: 10px 0;
    display: inline-block;" href="https://emdplugins.com/expert-service-pricing/?pk_campaign=wp-ticket-com-gettingstarted&pk_kwd=wp-ticket-com-livedemo">Purchase Work Order</a></p></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div id="gs-sec-273"></div><div style="color:white;background:#0000003b;padding:5px 10px;font-size: 1.4em;font-weight: 600;">Need Help?</div><div class="changelog emd-section getting-started-273" style="margin:0;background-color:white;padding:10px"><div id="gallery"></div><div class="sec-desc"><p>There are many resources available in case you need help:</p>
<ul>
<li>Search our <a target="_blank" href="https://emdplugins.com/support">knowledge base</a></li>
<li><a href="https://emdplugins.com/kb_tags/wp-ticket" target="_blank">Browse our WP Ticket Community articles</a></li>
<li><a href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation" target="_blank">Check out WP Ticket Community documentation for step by step instructions.</a></li>
<li><a href="https://emdplugins.com/emdplugins-support-introduction/" target="_blank">Open a support ticket if you still could not find the answer to your question</a></li>
</ul>
<p>Please read <a href="https://emdplugins.com/questions/what-to-write-on-a-support-ticket-related-to-a-technical-issue/" target="_blank">"What to write to report a technical issue"</a> before submitting a support ticket.</p></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div id="gs-sec-274"></div><div style="color:white;background:#0000003b;padding:5px 10px;font-size: 1.4em;font-weight: 600;">Learn More</div><div class="changelog emd-section getting-started-274" style="margin:0;background-color:white;padding:10px"><div id="gallery"></div><div class="sec-desc"><p>The following articles provide step by step instructions on various concepts covered in WP Ticket Community.</p>
<ul><li>
<a target="_blank" href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation/#article167">Concepts</a>
</li>
<li>
<a target="_blank" href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation/#article467">Content Access</a>
</li>
<li>
<a target="_blank" href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation/#article466">Quick Start</a>
</li>
<li>
<a target="_blank" href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation/#article468">Working with Agents</a>
</li>
<li>
<a target="_blank" href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation/#article169">Working with Tickets</a>
</li>
<li>
<a target="_blank" href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation/#article170">Widgets</a>
</li>
<li>
<a target="_blank" href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation/#article354">Standards</a>
</li>
<li>
<a target="_blank" href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation/#article302">Integrations</a>
</li>
<li>
<a target="_blank" href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation/#article171">Forms</a>
</li>
<li>
<a target="_blank" href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation/#article229">Roles and Capabilities</a>
</li>
<li>
<a target="_blank" href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation/#article301">Notifications</a>
</li>
<li>
<a target="_blank" href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation/#article172">Administration</a>
</li>
<li>
<a target="_blank" href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation/#article355">Creating Shortcodes</a>
</li>
<li>
<a target="_blank" href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation/#article174">Screen Options</a>
</li>
<li>
<a target="_blank" href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation/#article173">Localization(l10n)</a>
</li>
<li>
<a target="_blank" href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation/#article469">Customizations</a>
</li>
<li>
<a target="_blank" href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation/#article175">Glossary</a>
</li></ul>
</div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div id="gs-sec-272"></div><div style="color:white;background:#0000003b;padding:5px 10px;font-size: 1.4em;font-weight: 600;">Installation, Configuration & Customization Service</div><div class="changelog emd-section getting-started-272" style="margin:0;background-color:white;padding:10px"><div id="gallery"></div><div class="sec-desc"><p>Get the peace of mind that comes from having WP Ticket Community properly installed, configured or customized by eMarket Design.</p>
<p>Being the developer of WP Ticket Community, we understand how to deliver the best value, mitigate risks and get the software ready for you to use quickly.</p>
<p>Our service includes:</p>
<ul>
<li>Professional installation by eMarket Design experts.</li>
<li>Configuration to meet your specific needs</li>
<li>Installation completed quickly and according to best practice</li>
<li>Knowledge of WP Ticket Community best practices transferred to your team</li>
</ul>
<p>Pricing of the service is based on the complexity of level of effort, required skills or expertise. To determine the estimated price and duration of this service, and for more information about related services, purchase a work order.  
<p><a target="_blank" style="
    padding: 16px;
    background: coral;
    border: 1px solid lightgray;
    border-radius: 12px;
    text-decoration: none;
    color: white;
    margin: 10px 0;
    display: inline-block;" href="https://emdplugins.com/expert-service-pricing/?pk_campaign=wp-ticket-com-gettingstarted&pk_kwd=wp-ticket-com-livedemo">Purchase Work Order</a></p></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div id="gs-sec-155"></div><div style="color:white;background:#0000003b;padding:5px 10px;font-size: 1.4em;font-weight: 600;">WP Ticket Community Introduction</div><div class="changelog emd-section getting-started-155" style="margin:0;background-color:white;padding:10px"><div class="emd-yt" data-youtube-id="Gsaf7TaCOJY" data-ratio="16:9">loading...</div><div class="sec-desc"><p>Watch WP Ticket Community introduction video to learn about the plugin features and configuration.</p></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div id="gs-sec-157"></div><div style="color:white;background:#0000003b;padding:5px 10px;font-size: 1.4em;font-weight: 600;">CSV Import Export Addon helps you get your data in and out of WordPress quickly, saving you ton of time</div><div class="changelog emd-section getting-started-157" style="margin:0;background-color:white;padding:10px"><div class="emd-yt" data-youtube-id="NAp0j-akmFE" data-ratio="16:9">loading...</div><div class="sec-desc"><p>CSV Import Export Addon allows bulk import, export, update tickets or agents from/to CSV files. You can also reset(delete) all data and start over again without modifying database. The export feature is also great for backups and archiving old or obsolete data.</p><p><a href="https://emdplugins.com/plugins/emd-csv-import-export-extension/?pk_campaign=emdimpexp-buybtn&pk_kwd=wp-ticket-com-resources"><img style="width: 154px;" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/button_buy-now.png"; ?>"></a></p></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div id="gs-sec-156"></div><div style="color:white;background:#0000003b;padding:5px 10px;font-size: 1.4em;font-weight: 600;">Smart Search Addon for finding what's important faster</div><div class="changelog emd-section getting-started-156" style="margin:0;background-color:white;padding:10px"><div class="emd-yt" data-youtube-id="EFvPlM83Z_M" data-ratio="16:9">loading...</div><div class="sec-desc"><p>Smart Search Addon for WP Ticket Community edition helps you:</p><ul><li>Filter entries quickly to find what you're looking for</li><li>Save your frequently used filters so you do not need to create them again</li><li>Sort entry columns to see what's important faster</li><li>Change the display order of columns </li><li>Enable or disable columns for better and cleaner look </li><li>Export search results to PDF or CSV for custom reporting</li></ul><div style="margin:25px"><a href="https://emdplugins.com/plugins/emd-advanced-filters-and-columns-extension/?pk_campaign=emd-afc-buybtn&pk_kwd=wp-ticket-com-resources"><img style="width: 154px;" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/button_buy-now.png"; ?>"></a></div></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div id="gs-sec-161"></div><div style="color:white;background:#0000003b;padding:5px 10px;font-size: 1.4em;font-weight: 600;">Active Directory/LDAP Addon</div><div class="changelog emd-section getting-started-161" style="margin:0;background-color:white;padding:10px"><div class="emd-yt" data-youtube-id="onWfeZHLGzo" data-ratio="16:9">loading...</div><div class="sec-desc"><p>Microsoft Active Directory/LDAP Addon allows bulk importing and updating Support Agents by visually mapping LDAP fields. The imports/updates can scheduled on desired intervals as well.</p>
<p><a href="https://emdplugins.com/plugin-features/wp-ticket-microsoft-active-directoryldap-addon/?pk_campaign=emdldap-buybtn&pk_kwd=wp-ticket-com-resources"><img style="width: 154px;" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/button_buy-now.png"; ?>"></a></p></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div id="gs-sec-162"></div><div style="color:white;background:#0000003b;padding:5px 10px;font-size: 1.4em;font-weight: 600;">MailChimp Addon for building email list through WP Ticket Community</div><div class="changelog emd-section getting-started-162" style="margin:0;background-color:white;padding:10px"><div class="emd-yt" data-youtube-id="Oi_c-0W1Sdo" data-ratio="16:9">loading...</div><div class="sec-desc"><p>EMD MailChimp Extension helps you build MailChimp email list based on the contact information collected through ticket submit forms.</p><div style="margin:25px"><a href="https://emdplugins.com/plugin-features/wp-ticket-mailchimp-addon/?pk_campaign=emd-mailchimp-buybtn&pk_kwd=wp-ticket-com-resources"><img style="width: 154px;" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/button_buy-now.png"; ?>"></a></div></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div id="gs-sec-160"></div><div style="color:white;background:#0000003b;padding:5px 10px;font-size: 1.4em;font-weight: 600;">Incoming Email WordPress Plugin - Create support tickets from emails</div><div class="changelog emd-section getting-started-160" style="margin:0;background-color:white;padding:10px"><div class="emd-yt" data-youtube-id="iSbezemGkgc" data-ratio="16:9">loading...</div><div class="sec-desc"><p>WP Ticket Incoming Email Addon allows you accept support tickets from incoming email. It can process also customer replies as ticket comments, assign tickets to  the predefined tags, whitelist or blacklist addresses and more.</p>

<p><a href="https://emdplugins.com/plugin-features/wp-ticket-incoming-email-addon/?pk_campaign=wpasincemail-buybtn&pk_kwd=wp-ticket-com-resources"><img style="width: 154px;" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/button_buy-now.png"; ?>"></a></p></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div id="gs-sec-163"></div><div style="color:white;background:#0000003b;padding:5px 10px;font-size: 1.4em;font-weight: 600;">WP Ticket Pro - best for growing support teams</div><div class="changelog emd-section getting-started-163" style="margin:0;background-color:white;padding:10px"><div id="gallery"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-163" href="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/wpticket_pro-montage_1080.png"; ?>"><img src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/wpticket_pro-montage_1080.png"; ?>"></a></div></div><div class="sec-desc"><p>WP Ticket Pro is a powerful and easy to use customer support and helpdesk system designed for growing businesses in mind.</p>

<p><a href="https://emdplugins.com/plugins/wp-ticket-wordpress-plugin/?pk_campaign=wpticketpro-buybtn&pk_kwd=wp-ticket-com-resources"><img style="width: 154px;" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/button_buy-now.png"; ?>"></a></p></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div id="gs-sec-164"></div><div style="color:white;background:#0000003b;padding:5px 10px;font-size: 1.4em;font-weight: 600;">WP Ticket Enterprise - best for larger support teams</div><div class="changelog emd-section getting-started-164" style="margin:0;background-color:white;padding:10px"><div id="gallery"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-164" href="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/wpticket_ent-montage_1080.png"; ?>"><img src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/wpticket_ent-montage_1080.png"; ?>"></a></div></div><div class="sec-desc"><p>WP Ticket Enterprise is the most powerful and easy to use customer support and helpdesk system, designed for larger support teams.</p>

<p><a href="https://emdplugins.com/plugins/wp-ticket-wordpress-plugin/?pk_campaign=wpticketpro-buybtn&pk_kwd=wp-ticket-com-resources"><img style="width: 154px;" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/button_buy-now.png"; ?>"></a></p></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px">

<?php echo '</div>'; ?>
<?php echo '<div class="tab-content" id="tab-release-notes"';
	if ("release-notes" != $active_tab) {
		echo 'style="display:none;"';
	}
	echo '>';
?>
<p class="about-description">This page lists the release notes from every production version of WP Ticket Community.</p>


<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.9.3 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-1219" style="margin:0">
<h3 style="font-size:18px;" class="fix"><div  style="font-size:110%;color:#c71585"><span class="dashicons dashicons-admin-tools"></span> FIX</div>
Required field validation is not working.</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.9.2 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-1217" style="margin:0">
<h3 style="font-size:18px;" class="fix"><div  style="font-size:110%;color:#c71585"><span class="dashicons dashicons-admin-tools"></span> FIX</div>
multi-select form component missing scroll bars when the content overflows its fixed height.</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.9.1 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-1158" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
tested with WP 5.5.1</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-1157" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
updates to translation strings and libraries</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-1156" style="margin:0">
<h3 style="font-size:18px;" class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Added version numbers to js and css files for caching purposes</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.9.0 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-1118" style="margin:0">
<h3 style="font-size:18px;" class="fix"><div  style="font-size:110%;color:#c71585"><span class="dashicons dashicons-admin-tools"></span> FIX</div>
Emd Form Builder support for WP Ticket WooCommerce Extension</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-1117" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
Emd Form Builder support for WordPress stock themes</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.8.0 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-1093" style="margin:0">
<h3 style="font-size:18px;" class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Added previous and next buttons for the edit screens of tickets and agents</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-1092" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
updates and improvements to libraries</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-1091" style="margin:0">
<h3 style="font-size:18px;" class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Added previous and next buttons for the edit screens of quotes</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-1073" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
updates and improvements to form library</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.7.0 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-1011" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
Emd templates</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-1010" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
updates and improvements to form library</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-1009" style="margin:0">
<h3 style="font-size:18px;" class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Added support for Emd Custom Field Builder when upgraded to premium editions</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.6.0 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-961" style="margin:0">
<h3 style="font-size:18px;" class="fix"><div  style="font-size:110%;color:#c71585"><span class="dashicons dashicons-admin-tools"></span> FIX</div>
Session cleanup workflow by creating a custom table to process records.</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-960" style="margin:0">
<h3 style="font-size:18px;" class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Added Emd form builder support.</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-959" style="margin:0">
<h3 style="font-size:18px;" class="fix"><div  style="font-size:110%;color:#c71585"><span class="dashicons dashicons-admin-tools"></span> FIX</div>
XSS related issues.</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-958" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
Cleaned up unnecessary code and optimized the library file content.</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.5.1 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-898" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
Misc updates for better compatibility and stability</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.5.0 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-856" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
Emd templating system to match modern web standards</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-855" style="margin:0">
<h3 style="font-size:18px;" class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Created a new shortcode page which displays all available shortcodes. You can access this page under the plugin settings.</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.4.4 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-769" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
Misc updates for better compatibility and stability</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.4.3 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-740" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
library updates for better stability and compatibility</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.4.2 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-665" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
Moved web fonts to local storage - you can still get them from CDN using your functions.php if you need to.</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.4.0 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-593" style="margin:0">
<h3 style="font-size:18px;" class="fix"><div  style="font-size:110%;color:#c71585"><span class="dashicons dashicons-admin-tools"></span> FIX</div>
Search results table when ticket priority is empty</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-592" style="margin:0">
<h3 style="font-size:18px;" class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Ability to limit max size, max number of files and file types of ticket attachments and agent photos</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-591" style="margin:0">
<h3 style="font-size:18px;" class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Ability to limit max size and file types of ticket attachments and agent photos</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-590" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
library updates</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.3.2 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-440" style="margin:0">
<h3 style="font-size:18px;" class="fix"><div  style="font-size:110%;color:#c71585"><span class="dashicons dashicons-admin-tools"></span> FIX</div>
The audio issue in the introduction video</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-439" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
Changed WPAS button in pages to VSB for Visual Shortcode Builder</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.3.1 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-433" style="margin:0">
<h3 style="font-size:18px;" class="fix"><div  style="font-size:110%;color:#c71585"><span class="dashicons dashicons-admin-tools"></span> FIX</div>
Layout of ticket page when priority is not shown.</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.3.0 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-417" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
Extended the session clean up time to 12 hours</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-416" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
Improved Ticket List and Ticket Results table css</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-415" style="margin:0">
<h3 style="font-size:18px;" class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Getting started section</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.2.2 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-414" style="margin:0">
<h3 style="font-size:18px;" class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Getting started section</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.2.0 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-351" style="margin:0">
<h3 style="font-size:18px;" class="tweak"><div  style="font-size:110%;color:#33b5e5"><span class="dashicons dashicons-admin-settings"></span> TWEAK</div>
Updated codemirror libraries for custom CSS and JS options in plugin settings page</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-350" style="margin:0">
<h3 style="font-size:18px;" class="fix"><div  style="font-size:110%;color:#c71585"><span class="dashicons dashicons-admin-tools"></span> FIX</div>
PHP 7 compatibility</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-349" style="margin:0">
<h3 style="font-size:18px;" class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Added container type field in the plugin settings</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-348" style="margin:0">
<h3 style="font-size:18px;" class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Added custom JavaScript option in plugin settings under Tools tab</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<h3 style="font-size: 18px;font-weight:700;color: white;background: #708090;padding:5px 10px;width:155px;border: 2px solid #fff;border-radius:4px;text-align:center">5.1.0 changes</h3>
<div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-219" style="margin:0">
<h3 style="font-size:18px;" class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Added support for EMD Active Directory/LDAP Extension</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-218" style="margin:0">
<h3 style="font-size:18px;" class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Added support for EMD MailChimp extension</h3>
<div ></a></div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog emd-section whats-new whats-new-217" style="margin:0">
<h3 style="font-size:18px;" class="fix"><div  style="font-size:110%;color:#c71585"><span class="dashicons dashicons-admin-tools"></span> FIX</div>
WP Sessions security vulnerability</h3>
<div ></a></div></div></div><hr style="margin:30px 0">
<?php echo '</div>'; ?>
<?php echo '<div class="tab-content" id="tab-resources"';
	if ("resources" != $active_tab) {
		echo 'style="display:none;"';
	}
	echo '>';
?>
<div style="color:white;background:#0000003b;padding:5px 10px;font-size: 1.4em;font-weight: 600;">Extensive documentation is available</div><div class="emd-section changelog resources resources-154" style="margin:0;background-color:white;padding:10px"><div style="height:40px" id="gs-sec-154"></div><div id="gallery" class="wp-clearfix"></div><div class="sec-desc"><a href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation">WP Ticket Community Documentation</a></div></div><div style="margin-top:15px"><a href="#ptop" class="top">Go to top</a></div><hr style="margin-top:40px"><div style="color:white;background:#0000003b;padding:5px 10px;font-size: 1.4em;font-weight: 600;">How to customize WP Ticket Community</div><div class="emd-section changelog resources resources-159" style="margin:0;background-color:white;padding:10px"><div style="height:40px" id="gs-sec-159"></div><div class="emd-yt" data-youtube-id="ktv564QBO4s" data-ratio="16:9">loading...</div><div class="sec-desc"><p><strong><span class="dashicons dashicons-arrow-up-alt"></span> Watch the customization video to familiarize yourself with the customization options. </strong>. The video shows one of our plugins as an example. The concepts are the same and all our plugins have the same settings.</p>
<p>WP Ticket Community is designed and developed using <a href="https://wpappstudio.com">WP App Studio (WPAS) Professional WordPress Development platform</a>. All WPAS plugins come with extensive customization options from plugin settings without changing theme template files. Some of the customization options are listed below:</p>
<ul>
	<li>Enable or disable all fields, taxonomies and relationships from backend and/or frontend</li>
        <li>Use the default EMD or theme templating system</li>
	<li>Set slug of any entity and/or archive base slug</li>
	<li>Set the page template of any entity, taxonomy and/or archive page to sidebar on left, sidebar on right or no sidebar (full width)</li>
	<li>Hide the previous and next post links on the frontend for single posts</li>
	<li>Hide the page navigation links on the frontend for archive posts</li>
	<li>Display or hide any custom field</li>
	<li>Display any sidebar widget on plugin pages using EMD Widget Area</li>
	<li>Set custom CSS rules for all plugin pages including plugin shortcodes</li>
</ul>
<div class="quote">
<p>If your customization needs are more complex, you’re unfamiliar with code/templates and resolving potential conflicts, we strongly suggest you to <a href="https://emdplugins.com/open-a-support-ticket/?pk_campaign=wp-ticket-com-hireme-custom&ticket_topic=pre-sales-questions">hire us</a>, we will get your site up and running in no time.
</p>
</div></div></div><div style="margin-top:15px"><a href="#ptop" class="top">Go to top</a></div><hr style="margin-top:40px"><div style="color:white;background:#0000003b;padding:5px 10px;font-size: 1.4em;font-weight: 600;">How to resolve theme related issues</div><div class="emd-section changelog resources resources-158" style="margin:0;background-color:white;padding:10px"><div style="height:40px" id="gs-sec-158"></div><div id="gallery" class="wp-clearfix"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-158" href="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/emd_templating_system.png"; ?>"><img src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/emd_templating_system.png"; ?>"></a></div></div><div class="sec-desc"><p>If your theme is not coded based on WordPress theme coding standards, does have an unorthodox markup or its style.css is messing up how WP Ticket Community pages look and feel, you will see some unusual changes on your site such as sidebars not getting displayed where they are supposed to or random text getting displayed on headers etc. after plugin activation.</p>
<p>The good news is WP Ticket Community plugin is designed to minimize theme related issues by providing two distinct templating systems:</p>
<ul>
<li>The EMD templating system is the default templating system where the plugin uses its own templates for plugin pages.</li>
<li>The theme templating system where WP Ticket Community uses theme templates for plugin pages.</li>
</ul>
<p>The EMD templating system is the recommended option. If the EMD templating system does not work for you, you need to check "Disable EMD Templating System" option at Settings > Tools tab and switch to theme based templating system.</p>
<p>Please keep in mind that when you disable EMD templating system, you loose the flexibility of modifying plugin pages without changing theme template files.</p>
<p>If none of the provided options works for you, you may still fix theme related conflicts following the steps in <a href="https://docs.emdplugins.com/docs/wp-ticket-community-documentation">WP Ticket Community Documentation - Resolving theme related conflicts section.</a></p>

<div class="quote">
<p>If you’re unfamiliar with code/templates and resolving potential conflicts, <a href="https://emdplugins.com/open-a-support-ticket/?pk_campaign=raq-hireme&ticket_topic=pre-sales-questions"> do yourself a favor and hire us</a>. Sometimes the cost of hiring someone else to fix things is far less than doing it yourself. We will get your site up and running in no time.</p>
</div></div></div><div style="margin-top:15px"><a href="#ptop" class="top">Go to top</a></div><hr style="margin-top:40px">
<?php echo '</div>'; ?>
<?php echo '<div class="tab-content" id="tab-features"';
	if ("features" != $active_tab) {
		echo 'style="display:none;"';
	}
	echo '>';
?>
<h3>Start providing excellent support to your customers</h3>
<p>Explore the full list of features available in the the latest version of WP Ticket. Click on a feature title to learn more.</p>
<table class="widefat features striped form-table" style="width:auto;font-size:16px">
<tr><td><a href="https://emdplugins.com/?p=10567&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/key.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10567&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Assign roles to your customer support staff</a></td><td></td></tr>
<tr><td><a href="https://emdplugins.com/?p=10566&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/client-area.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10566&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Customer support client area</a></td><td></td></tr>
<tr><td><a href="https://emdplugins.com/?p=10659&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/support-agents.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10659&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Create unlimited number of customer support staff</a></td><td></td></tr>
<tr><td><a href="https://emdplugins.com/?p=10658&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/custom-fields.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10658&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Extend your customer support with custom fields</a></td><td></td></tr>
<tr><td><a href="https://emdplugins.com/?p=10657&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/attachment.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10657&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Easy support ticket attachments</a></td><td></td></tr>
<tr><td><a href="https://emdplugins.com/?p=10656&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/no-spam.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10656&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Protect your customer support from spam</a></td><td></td></tr>
<tr><td><a href="https://emdplugins.com/?p=10561&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/customize.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10561&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Customize your customer support form</a></td><td></td></tr>
<tr><td><a href="https://emdplugins.com/?p=10560&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/responsive.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10560&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Fully responsive customer support system</a></td><td></td></tr>
<tr><td><a href="https://emdplugins.com/?p=10559&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/central-location.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10559&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Central location for all your customer support tickets and agents</a></td><td></td></tr>
<tr><td><a href="https://emdplugins.com/?p=12046&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/frontend_edit.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=12046&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Frontend ticket and support agent profile editor - simplify ticket processing for non-technical agents, eliminate the need to access WordPress dashboard.</a></td><td> - Premium feature (Included in Enterprise only)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=12047&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/attribute-access.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=12047&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Restrict access to and editing of specific fields on a user role basis - decide which ticket fields agents, managers view and edit or view-able by customers.</a></td><td> - Premium feature (Included in Enterprise only)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=11191&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/contributors.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=11191&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Allow multiple agents work on the same ticket for faster resolutions</a></td><td> - Premium feature (included in both Pro and Enterprise)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=11190&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/automation.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=11190&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Workflow: Automate what's going to happen to support tickets when a certain period of time has passed</a></td><td> - Premium feature (Included in Enterprise only)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=11189&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/triggers.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=11189&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Workflow: Automate what's going to happen when a support ticket is created or updated</a></td><td> - Premium feature (Included in Enterprise only)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=11188&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/agent_departments.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=11188&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Assign tickets to agent departments</a></td><td> - Premium feature (included in both Pro and Enterprise)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10979&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/empower-users.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10979&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Easy to use, powerful helpdesk permission system</a></td><td> - Premium feature (included in both Pro and Enterprise)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10579&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/clipboard.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10579&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Customer support ticket and agent summary views</a></td><td> - Premium feature (included in both Pro and Enterprise)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10578&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/dashboard.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10578&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Realtime support staff performance reports</a></td><td> - Premium feature (Included in Enterprise only)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10668&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/rgb.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10668&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Relate customer support tickets to each other</a></td><td> - Premium feature (Included in Enterprise only)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10667&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/todo.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10667&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Powerful to-do lists for customer support agents</a></td><td> - Premium feature (Included in Enterprise only)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10666&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/custom-report.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10666&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Create powerful custom support system reports</a></td><td> - Premium feature (Included in both Pro and Enterprise. Enterprise has more powerful features.)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10665&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/easel.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10665&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Powerful, realtime customer service charts and graphs</a></td><td> - Premium feature (Included in both Pro and Enterprise. Enterprise has more powerful features.)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10664&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/easy-widgets.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10664&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Display recent support tickets and comments on your sidebar</a></td><td> - Premium feature included in Starter edition. Pro and Enterprise have more powerful features)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10663&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/settings.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10663&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Powerful tools to customize your helpdesk</a></td><td> - Premium feature included in Starter edition. Pro and Enterprise have more powerful features)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10662&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/search-tickets.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10662&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Fast and easy search for customer support tickets</a></td><td> - Premium feature included in Starter edition. Pro and Enterprise have more powerful features)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10661&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/comments.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10661&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Fast replies to customer support requests</a></td><td> - Premium feature included in Starter edition. Pro and Enterprise have more powerful features)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10660&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/shop.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10660&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Group customer support tickets</a></td><td> - Premium feature included in Starter edition. Pro and Enterprise have more powerful features)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10568&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/megaphone.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10568&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Perfect Helpdesk with fully customizable email notifications</a></td><td> - Premium feature included in Starter edition. Pro and Enterprise have more powerful features)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10587&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/active-directory.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10587&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Sync customer support staff list with Microsoft Active Directory/LDAP servers</a></td><td> - Add-on</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10586&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/eddcom.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10586&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Learn which products cause most customer support requests using Easy Digital Downloads Addon</a></td><td> - Add-on</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10585&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/woocom.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10585&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Learn which products cause most customer support requests using WooCommerce Addon</a></td><td> - Add-on</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10584&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/mailchimp.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10584&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Grow your MailChimp list through customer support tickets</a></td><td> - Add-on</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10583&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/canned-responses.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10583&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Fast replies to common customer support requests with canned responses</a></td><td> - Add-on (Included in Enterprise only)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10669&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/email.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10669&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Accept support tickets from incoming emails</a></td><td> - Add-on (Included in Enterprise only)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10581&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/csv-impexp.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10581&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Import/export addon to migrate from other customer support systems</a></td><td> - Add-on (included both Pro and Enterprise)</td></tr>
<tr><td><a href="https://emdplugins.com/?p=10580&pk_campaign=wp-ticket-com&pk_kwd=getting-started"><img style="width:128px;height:auto" src="<?php echo WP_TICKET_COM_PLUGIN_URL . "assets/img/zoomin.png"; ?>"></a></td><td><a href="https://emdplugins.com/?p=10580&pk_campaign=wp-ticket-com&pk_kwd=getting-started">Advanced smart search for customer support tickets and agents</a></td><td> - Add-on (included both Pro and Enterprise)</td></tr>
</table>
<?php echo '</div>'; ?>
<?php echo '</div>';
}
