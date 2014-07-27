<?php
/*
Plugin Name: Widget Wrangler - bbPress Compat
Plugin URI: http://www.wranglerplugins.com
Description: Widget Wrangler bbPress Compatibility
Author: Jonathan Daggerhart
Version: 0.1
Author URI: http://daggerhart.com
License: GPL2
*/

/**
 * set page context for bbpress related stuff
 */
function widget_wrangler_bbpress_set_page_context($context){
  // only if bbPress is active
  if ( class_exists( 'bbPress' ) && function_exists( 'bbp_is_forum_archive' ) )
  {
    // We're on the forum archive page
    if ( bbp_is_forum_archive() )
    {
      $page = bbp_get_page_by_path( bbp_get_root_slug() );
      
      // There is a WordPress Page that has the same slug as the forum archive page
      if ( $page ){
        $context['id']      = $page->ID;
        $context['context'] = 'post';
        $context['object']  = $page;
        $context['bbpress'] = true;
      }
    }
  }
  return $context;
}
add_filter( 'widget-wrangler-set-page-context', 'widget_wrangler_bbpress_set_page_context' );

/**
 * Help find widgets on bbpress related pages
 */
function widget_wrangler_bbpress_find_widgets($widgets){
  global $widget_wrangler;
  $page_context = $widget_wrangler->page_context;
  
  if (is_null($widgets) && (isset($page_context['bbpress']) && $page_context['bbpress'])) {
    $post = $page_context['object'];
    
    // single page widgets wrangling on their own
    if (isset($post) && $widgets_string = get_post_meta($post->ID,'ww_post_widgets', TRUE)) {
      $widgets = unserialize( $widgets_string );
    }
  }
  return $widgets;
}
add_filter( 'widget_wrangler_find_all_page_widgets', 'widget_wrangler_bbpress_find_widgets' );
