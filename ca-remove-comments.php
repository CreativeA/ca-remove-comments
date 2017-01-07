<?php
/*
Plugin Name: Creative Asset Remove Comments
Plugin URI: https://github.com/CreativeA/ca-remove-comments
Description: Client dashboard
Version: 1.2
Author: Creative Asset
License: GPLv2
GitHub Plugin URI: https://github.com/CreativeA/ca-remove-comments
*/

/*--------------------------------------------------
 * Remove comments upon site load
 --------------------------------------------------*/

function ca_remove_comments(){

    // Comments
    add_action('admin_init', 'df_disable_comments_post_types_support');
    add_filter('comments_open', 'df_disable_comments_status', 20, 2);
    add_filter('pings_open', 'df_disable_comments_status', 20, 2);
    add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);
    add_action('admin_menu', 'df_disable_comments_admin_menu');
    add_action('admin_init', 'df_disable_comments_admin_menu_redirect');
    add_action('admin_init', 'df_disable_comments_dashboard');
    add_action('init', 'df_disable_comments_admin_bar');
    add_action( 'wp_before_admin_bar_render', 'creativea_comments_admin_bar', 0 );

}

add_action('init', 'ca_remove_comments');

/*-----------------------------------------------
 * Completely Remove WordPress Comments
 -----------------------------------------------*/

// Disable support for comments and trackbacks in post types
function df_disable_comments_post_types_support() {
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if(post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}

// Close comments on the front-end
function df_disable_comments_status() {
    return false;
}

// Hide existing comments
function df_disable_comments_hide_existing_comments($comments) {
    $comments = array();
    return $comments;
}

// Remove comments page in menu
function df_disable_comments_admin_menu() {
    remove_menu_page('edit-comments.php');
}

// Redirect any user trying to access comments page
function df_disable_comments_admin_menu_redirect() {
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url()); exit;
    }
}

// Remove comments metabox from dashboard
function df_disable_comments_dashboard() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}

// Remove comments links from admin bar
function df_disable_comments_admin_bar() {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
}

/*-----------------------------------------------
 * Edit the Top Admin Bar
 -----------------------------------------------*/

// Remove WordPress Admin Bar Menu Items
function creativea_comments_admin_bar() {
    global $wp_admin_bar;

// To remove Comments Icon/Menu
    $wp_admin_bar->remove_menu('comments');

}

/*-----------------------------------------------
 * Beaver Builder TESTING THINGS Has MAinWP working
 -----------------------------------------------*/

function ca_add_comment_stylesheet(){

    wp_enqueue_style( 'fend', plugins_url( 'css/fend.css' , __FILE__ ) );
    // Stylesheets

}

add_action('wp_enqueue_scripts', 'ca_add_comment_stylesheet');
