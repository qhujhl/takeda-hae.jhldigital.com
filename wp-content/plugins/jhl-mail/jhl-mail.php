<?php
/**
 * JHL Mail
 *
 * @copyright Copyright (C) 2018-2021, JHL Digital - hello@jhldigital.com
 * @license   JHL INTERNAL ONLY
 *
 * @wordpress-plugin
 * Plugin Name: JHL Mail
 * Version:     1.0
 * Plugin URI:  https://www.jhldigital.com
 * Description: Create mail templates and sending emails, seamless integration with WP user data
 * Author:      JHL Digital
 * Author URI:  N/A
 * License:     JHL INTERNAL ONLY
 *
 */

require_once 'merge-token-shortcodes.php';
require_once 'functions.php';

function custom_post_type() {

        $labels = array(
            'name'                  => __('JHL Mail'),
            'singular_name'         => __('JHL Mail'),
            'add_new'               => __('Add New Mail Template'),
            'add_new_item'          => __('Add New Mail Template'),
            'edit_item'             => __('Edit Mail Template'),
            'new_item'              => __('New Mail Template'),
            'all_items'             => __('All Mail Templates'),
            'view_item'             => __('View Mail Template'),
            'search_items'          => __('Search Mail Template'),
            'not_found'             => __('No mail template found'),
            'not_found_in_trash'    => __('There are no templates in the recycling yard.'),
            'parent_item_colon'     => __('Parent Item:'),
            'menu_name'             => __('JHL Mail')

        );
        $args = array(
            'labels'                => $labels,
            'description'           => __('JHL Mail'),
            'public'                => false,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'query_var'             => false,
            'rewrite'               => false,
            'capability_type'       => 'post',
            'has_archive'           => false,
            'hierarchical'          => false,
            'menu_position'         => 5,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt')
        );
        register_post_type('jhl_mail', $args);
}
add_action('init', 'custom_post_type');
