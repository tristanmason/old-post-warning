<?php

/**
 * Plugin Name: Old Post Warning
 * Plugin URI: https://tristanmason.com
 * Description: Add a message at the top of a post when it is at least 6 years old.
 * Version: 1.1.2
 * Author: Tristan Mason
 * Author URI: https://tristanmason.com
 */

// Basic security, prevents file from being loaded directly.
defined( 'ABSPATH' ) or die( 'Forbidden' );

add_filter( 'the_content', 'tm_old_post_warning' );
add_action( 'wp_enqueue_scripts', 'tm_old_post_warning_styles' );

function tm_old_post_warning( $content ) {

    // Show on posts of any type, but not pages or archives
    if ( is_single() ) {

        // Set the minumum post age in years for the warning to appear
        $years = (int) 6;

        $offset = $years*365*24*60*60; // Offset in seconds
        $post_age = round( ( date('U') - get_post_time() )/60/60/24/365) ; // Find post age to the nearest year

        // Set the warning message
        $warning = 'Heads up! This post is about ' . $post_age . ' years old.';

        // Build the output
        $output = '<div class="tm-old-post-warning">';
        $output .= $warning;
        $output .= '</div>';

        // If the current post is older than $years, add warning before the post content
        if ( get_post_time() < ( date('U') - $offset ) ) {
             return $output . $content; 
        } else {
            // Exit if not an old post
            return $content;
        }

    } else {
        // Exit if not a single post
        return $content;
    }
}

function tm_old_post_warning_styles() {
    // Change these styles to match your site
    $css = '
        .tm-old-post-warning {
            padding: 4px 8px;
            background: #eee;
            display: inline-block;
            border-radius: 5px;
            margin-bottom: 1rem;
        }';

    wp_register_style( 'tm-old-post-warning', false );
    wp_enqueue_style( 'tm-old-post-warning' );
    wp_add_inline_style( 'tm-old-post-warning', $css );
}
