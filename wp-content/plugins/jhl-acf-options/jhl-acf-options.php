<?php
/*
Plugin Name: JHL ACF Options
Description: Define the structure of options
Version: 1.0
Author: JHL Digital
*/

if( function_exists('acf_add_options_page') ) {

    acf_set_options_page_capability('manage_options');

    /**
     * General Options
     */
    acf_add_options_page(array(
        'page_title'    => 'General Settings',
        'menu_title'    => 'General Settings',
        'menu_slug'     => 'jhl-general-settings',
        'capability'    => 'manage_options',
        'redirect'      => false
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'GA Settings',
        'menu_title'    => 'GA Settings',
        'parent_slug'   => 'jhl-general-settings',
    ));

    /**
     * Module Options
     */
    acf_add_options_page(array(
        'page_title'    => 'Module Settings',
        'menu_title'    => 'Module Settings',
        'menu_slug'     => 'jhl-module-settings',
        'capability'    => 'manage_options',
        'redirect'      => false
    ));
    acf_add_options_sub_page(array(
        'page_title'    => 'Registration Module',
        'menu_title'    => 'Registration Module',
        'parent_slug'   => 'jhl-module-settings',
    ));


}