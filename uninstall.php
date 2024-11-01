<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('tabs_rpvsrc_recentposts_title');
delete_option('tabs_rpvsrc_recentposts_cnt');
delete_option('tabs_rpvsrc_recentcomments_title');
delete_option('tabs_rpvsrc_recentcomments_cnt');
delete_option('tabs_rpvsrc_recentcomments_lgt');
 
// for site options in Multisite
delete_site_option('tabs_rpvsrc_recentposts_title');
delete_site_option('tabs_rpvsrc_recentposts_cnt');
delete_site_option('tabs_rpvsrc_recentcomments_title');
delete_site_option('tabs_rpvsrc_recentcomments_cnt');
delete_site_option('tabs_rpvsrc_recentcomments_lgt');