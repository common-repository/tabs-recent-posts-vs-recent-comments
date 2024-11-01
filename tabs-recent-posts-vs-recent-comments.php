<?php
/*
Plugin Name: Tabs recent posts vs recent comments
Description: This is a jquery based lightweight plugin to create a new wordpress tabbed widget to display recent posts and recent comments.
Author: Gopi Ramasamy
Version: 3.0
Plugin URI: http://www.gopiplus.com/work/2013/08/04/wordpress-plugin-recent-posts-vs-recent-comments-tabs/
Author URI: http://www.gopiplus.com/work/2013/08/04/wordpress-plugin-recent-posts-vs-recent-comments-tabs/
Donate link: http://www.gopiplus.com/work/2013/08/04/wordpress-plugin-recent-posts-vs-recent-comments-tabs/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: tabs-recent-posts-comments
Domain Path: /languages
*/

global $wpdb;

// Main method to load tabber widget
function tabs_rpvsrc()
{
	global $wpdb;
	$tabs_rpvsrc_recentposts_cnt = get_option('tabs_rpvsrc_recentposts_cnt');
	$tabs_rpvsrc_recentcomments_cnt = get_option('tabs_rpvsrc_recentcomments_cnt');
	$tabs_rpvsrc_recentposts_title = get_option('tabs_rpvsrc_recentposts_title');
	$tabs_rpvsrc_recentcomments_title = get_option('tabs_rpvsrc_recentcomments_title');
	if(!is_numeric($tabs_rpvsrc_recentposts_cnt)) { $tabs_rpvsrc_recentposts_cnt = 5 ;}
	if(!is_numeric($tabs_rpvsrc_recentcomments_cnt)) { $tabs_rpvsrc_recentcomments_cnt = 5 ;}
	?>
	<div id="TabsRecentPostsRecentCmts">
		<ul class="TabsPostsTabsUi">
			<li><a href="#TabsPostsLeft"><?php echo $tabs_rpvsrc_recentposts_title; ?></a></li>
			<li><a href="#TabsPostsRight"><?php echo $tabs_rpvsrc_recentcomments_title; ?></a></li>
		</ul>
		<div class="clear"></div>
		<div class="TabsPostsInsideContents">
			<div id="TabsPostsLeft">
				<?php tabs_rpvsrc_recentposts_load($tabs_rpvsrc_recentposts_cnt); ?>
			</div>
			<div id="TabsPostsRight">
				<?php tabs_rpvsrc_recentcomments_load($tabs_rpvsrc_recentcomments_cnt); ?>    
			</div>
			<div class="clear" style="display: none;"></div>
		</div>
		<div class="clear"></div>
	</div>
	<?php
}

add_shortcode( 'tabs-rpvsrc', 'tabs_rpvsrc_shortcode' );

/*Method to load widget using the short code*/
function tabs_rpvsrc_shortcode( $atts ) 
{
	global $wpdb;
	//[tabs-rpvsrc show="1"]
	//return tabs_rpvsrc();
	
	$tabs_rpvsrc_recentposts_cnt = get_option('tabs_rpvsrc_recentposts_cnt');
	$tabs_rpvsrc_recentcomments_cnt = get_option('tabs_rpvsrc_recentcomments_cnt');
	$tabs_rpvsrc_recentposts_title = get_option('tabs_rpvsrc_recentposts_title');
	$tabs_rpvsrc_recentcomments_title = get_option('tabs_rpvsrc_recentcomments_title');
	if(!is_numeric($tabs_rpvsrc_recentposts_cnt)) { $tabs_rpvsrc_recentposts_cnt = 5 ;}
	if(!is_numeric($tabs_rpvsrc_recentcomments_cnt)) { $tabs_rpvsrc_recentcomments_cnt = 5 ;}
	
	$tabs = "";
	$tabs .= '<div id="TabsRecentPostsRecentCmts">';
		$tabs .= '<ul class="TabsPostsTabsUi">';
			$tabs .= '<li><a href="#TabsPostsLeft">'.$tabs_rpvsrc_recentposts_title.'</a></li>';
			$tabs .= '<li><a href="#TabsPostsRight">'.$tabs_rpvsrc_recentcomments_title.'</a></li>';
		$tabs .= '</ul>';
		$tabs .= '<div class="clear"></div>';
		$tabs .= '<div class="TabsPostsInsideContents">';
			$tabs .= '<div id="TabsPostsLeft">';
				
				$popular = new WP_Query('showposts='. $tabs_rpvsrc_recentposts_cnt .'&orderby=post_date&order=desc');
				while ($popular->have_posts()) : $popular->the_post();
					$tabs .= '<div><a title="'.get_the_title().'" href="'.get_the_permalink().'">'.get_the_title().'</a></div>';
				endwhile; 
	
			$tabs .= '</div>';
			$tabs .= '<div id="TabsPostsRight">';
			
			
				$comments = get_comments('status=approve&number=5'.$tabs_rpvsrc_recentcomments_cnt);
				$tabs_rpvsrc_recentcomments_lgt = get_option('tabs_rpvsrc_recentcomments_lgt');
				if(!is_numeric($tabs_rpvsrc_recentcomments_lgt)) { $tabs_rpvsrc_recentcomments_lgt = 100 ;}
				foreach($comments as $cmt)
				{
					$recentcomments_clean = tabs_rpvsrc_clean($cmt->comment_content , $tabs_rpvsrc_recentcomments_lgt);
					$tabs .= '<div><a title="" href="'.get_permalink($cmt->comment_post_ID).'#comment-'. $cmt->comment_ID.'">'. $recentcomments_clean.'...</a></div>';
				}
			  
			$tabs .= '</div>';
			$tabs .= '<div class="clear" style="display: none;"></div>';
		$tabs .= '</div>';
		$tabs .= '<div class="clear"></div>';
	$tabs .= '</div>';
	
	return $tabs;
}

/*Function to call when plugin get activated*/
function tabs_rpvsrc_install() 
{
	global $wpdb, $wp_version;
	add_option('tabs_rpvsrc_recentposts_title', "Posts");
	add_option('tabs_rpvsrc_recentposts_cnt', "5");
	add_option('tabs_rpvsrc_recentcomments_title', "Comments");
	add_option('tabs_rpvsrc_recentcomments_cnt', "5");
	add_option('tabs_rpvsrc_recentcomments_lgt', "100");
}

/*Function to Call when plugin get deactivated*/
function tabs_rpvsrc_deactivation() 
{
	// No action on plugin deactivation
}

/*Load javascript files for plugins*/
function tabs_rpvsrc_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script('jquery');
		wp_enqueue_style( 'tabs_rpvsrc_style', get_option('siteurl').'/wp-content/plugins/tabs-recent-posts-vs-recent-comments/inc/style.css');
		wp_enqueue_script( 'tabs_rpvsrc_script', get_option('siteurl').'/wp-content/plugins/tabs-recent-posts-vs-recent-comments/inc/script.js', '', '1.0', true);
	}
}   

/*Tabber plugin widget control*/
function tabs_rpvsrc_control() 
{
	$tabs_rpvsrc_recentposts_cnt = get_option('tabs_rpvsrc_recentposts_cnt');
	$tabs_rpvsrc_recentcomments_cnt = get_option('tabs_rpvsrc_recentcomments_cnt');
	$tabs_rpvsrc_recentposts_title = get_option('tabs_rpvsrc_recentposts_title');
	$tabs_rpvsrc_recentcomments_title = get_option('tabs_rpvsrc_recentcomments_title');
	$tabs_rpvsrc_recentcomments_lgt = get_option('tabs_rpvsrc_recentcomments_lgt');
	
	if (isset($_POST['tplp_submit'])) 
	{
		$tabs_rpvsrc_recentposts_cnt 		= sanitize_text_field($_POST['tabs_rpvsrc_recentposts_cnt']);
		$tabs_rpvsrc_recentcomments_cnt 	= sanitize_text_field($_POST['tabs_rpvsrc_recentcomments_cnt']);
		$tabs_rpvsrc_recentposts_title 		= sanitize_text_field($_POST['tabs_rpvsrc_recentposts_title']);
		$tabs_rpvsrc_recentcomments_title 	= sanitize_text_field($_POST['tabs_rpvsrc_recentcomments_title']);
		$tabs_rpvsrc_recentcomments_lgt 	= sanitize_text_field($_POST['tabs_rpvsrc_recentcomments_lgt']);
		
		update_option('tabs_rpvsrc_recentposts_cnt', $tabs_rpvsrc_recentposts_cnt );
		update_option('tabs_rpvsrc_recentcomments_cnt', $tabs_rpvsrc_recentcomments_cnt );
		update_option('tabs_rpvsrc_recentposts_title', $tabs_rpvsrc_recentposts_title );
		update_option('tabs_rpvsrc_recentcomments_title', $tabs_rpvsrc_recentcomments_title );
		update_option('tabs_rpvsrc_recentcomments_lgt', $tabs_rpvsrc_recentcomments_lgt );
	}
	echo '<p>'.__('Recent posts tab title:', 'tabs-recent-posts-comments').'<br><input  style="width: 200px;" type="text" value="';
	echo $tabs_rpvsrc_recentposts_title . '" name="tabs_rpvsrc_recentposts_title" id="tabs_rpvsrc_recentposts_title" /></p>';
	echo '<p>'.__('Number of recent posts to show:', 'tabs-recent-posts-comments').'<br><input  style="width: 200px;" type="text" value="';
	echo $tabs_rpvsrc_recentposts_cnt . '" name="tabs_rpvsrc_recentposts_cnt" id="tabs_rpvsrc_recentposts_cnt" /></p>';
	echo '<p>'.__('Recent comments tab title:', 'tabs-recent-posts-comments').'<br><input  style="width: 200px;" type="text" value="';
	echo $tabs_rpvsrc_recentcomments_title . '" name="tabs_rpvsrc_recentcomments_title" id="tabs_rpvsrc_recentcomments_title" /></p>';
	echo '<p>'.__('Number of recent comments to show:', 'tabs-recent-posts-comments').'<br><input  style="width: 200px;" type="text" value="';
	echo $tabs_rpvsrc_recentcomments_cnt . '" name="tabs_rpvsrc_recentcomments_cnt" id="tabs_rpvsrc_recentcomments_cnt" /></p>';
	echo '<p>'.__('Comments excerpt length:', 'tabs-recent-posts-comments').'<br><input  style="width: 200px;" type="text" value="';
	echo $tabs_rpvsrc_recentcomments_lgt . '" name="tabs_rpvsrc_recentcomments_lgt" id="tabs_rpvsrc_recentcomments_lgt" /></p>';
	
	echo '<input type="hidden" id="tplp_submit" name="tplp_submit" value="1" />';
	
	echo '<p>';
	_e('Check official website for more information', 'tabs-recent-posts-comments');
	?> 
	<a target="_blank" href="http://www.gopiplus.com/work/2013/08/04/wordpress-plugin-recent-posts-vs-recent-comments-tabs/">
	<?php _e('click here', 'tabs-recent-posts-comments'); ?></a></p><?php
}

/*Method to load tabber widget*/
function tabs_rpvsrc_widget($args) 
{
	tabs_rpvsrc();
}

/*Method to load recent posts*/
function tabs_rpvsrc_recentposts_load( $posts = 5 ) 
{
	$popular = new WP_Query('showposts='. $posts .'&orderby=post_date&order=desc');
	while ($popular->have_posts()) : $popular->the_post();
	?>
	<div><a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a></div>
	<?php
	endwhile; 
}

/*Method to load recent comments*/
function tabs_rpvsrc_recentcomments_load( $posts = 5 ) 
{
	$comments = get_comments('status=approve&number=5');
	$tabs_rpvsrc_recentcomments_lgt = get_option('tabs_rpvsrc_recentcomments_lgt');
	if(!is_numeric($tabs_rpvsrc_recentcomments_lgt)) { $tabs_rpvsrc_recentcomments_lgt = 100 ;}
	foreach($comments as $cmt)
	{
		$recentcomments_clean = tabs_rpvsrc_clean($cmt->comment_content , $tabs_rpvsrc_recentcomments_lgt);
		?>
		<div><a title="" href="<?php echo get_permalink($cmt->comment_post_ID);?>#comment-<?php echo $cmt->comment_ID; ?>"><?php echo $recentcomments_clean; ?>...</a></div>
		<?php
	}
}

/*Clean comment*/
function tabs_rpvsrc_clean($comment, $length = 0) 
{
	$string = strip_tags(str_replace('[...]', '...', $comment));
	if ($length > 0) 
	{
		$string = substr($string, 0, $length);
	}
	return $string;
}

/*Method to initiate sidebar widget & control*/
function tabs_rpvsrc_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget( 'tabs-recent-posts-comments', 
				__('Recent posts vs Recent comments', 'tabs-recent-posts-comments'), 'tabs_rpvsrc_widget');
	}
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control( 'tabs-recent-posts-comments', 
				array( __('Recent posts vs Recent comments', 'tabs-recent-posts-comments'), 'widgets'), 'tabs_rpvsrc_control');
	} 
}

/*Plugin textdomain*/
function tabs_textdomain() 
{
	  load_plugin_textdomain( 'tabs-recent-posts-comments', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/*Plugin hook*/
add_action('plugins_loaded', 'tabs_textdomain');
add_action("plugins_loaded", "tabs_rpvsrc_init");
add_action('wp_enqueue_scripts', 'tabs_rpvsrc_add_javascript_files');
register_activation_hook(__FILE__, 'tabs_rpvsrc_install');
register_deactivation_hook(__FILE__, 'tabs_rpvsrc_deactivation');
?>