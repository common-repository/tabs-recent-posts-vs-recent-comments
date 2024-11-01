/* 
*     tabs recent posts vs recent comments
*     Copyright (C) 2011 - 2019 www.gopiplus.com
*     http://www.gopiplus.com/work/2013/08/04/wordpress-plugin-recent-posts-vs-recent-comments-tabs/
*     License: GPLv2 or later
*/

$j = jQuery.noConflict();

$j(document).ready(function(){

        // UL = .TabsPostsTabsUi
        // Tab contents = .TabsPostsInsideContents
        
       var tag_cloud_class = '#tag-cloud'; 
       
       var tag_cloud_height = jQuery('#tag-cloud').height();

       jQuery('.TabsPostsInsideContents ul li:last-child').css('border-bottom','0px') // remove last border-bottom from list in tab conten
       jQuery('.TabsPostsTabsUi').each(function(){
       	jQuery(this).children('li').children('a:first').addClass('selected'); // Add .selected class to first tab on load
       });
       jQuery('.TabsPostsInsideContents > *').hide();
       jQuery('.TabsPostsInsideContents > *:first-child').show();
       

       jQuery('.TabsPostsTabsUi li a').click(function(evt){ // Init Click funtion on TabsPostsTabsUi
        
            var clicked_tab_ref = jQuery(this).attr('href'); // Strore Href value
            
            jQuery(this).parent().parent().children('li').children('a').removeClass('selected'); //Remove selected from all TabsPostsTabsUi
            jQuery(this).addClass('selected');
            jQuery(this).parent().parent().parent().children('.TabsPostsInsideContents').children('*').hide();
            
            jQuery('.TabsPostsInsideContents ' + clicked_tab_ref).fadeIn(500);
             
             evt.preventDefault();

        })
    
})