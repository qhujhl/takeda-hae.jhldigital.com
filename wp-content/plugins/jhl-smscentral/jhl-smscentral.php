<?php
/**
 * JHL SMS Central
 *
 * @copyright Copyright (C) 2018-2022, JHL Digital - hello@jhldigital.com
 * @license   JHL INTERNAL ONLY
 *
 * @wordpress-plugin
 * Plugin Name: JHL SMS Central
 * Version:     1.0
 * Plugin URI:  N/A
 * Description: SMS Central webhooks, utilities
 * Author:      JHL Digital
 * Author URI:  N/A
 * License:     JHL INTERNAL ONLY
 *
 */
/** ================================================================================= */

require_once 'vendor/restclient.php';
require_once 'class-smscentral-webhook.php';
require_once 'class-smscentral-functions.php';

if( function_exists('acf_add_options_page') ) {

    acf_set_options_page_capability('manage_options');

    acf_add_options_sub_page(array(
        'page_title'    => 'SMS Central Settings',
        'menu_title'    => 'SMS Central Settings',
        'parent_slug'   => 'jhl-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'SMS Settings',
        'menu_title'    => 'SMS Settings',
        'parent_slug'   => 'jhl-general-settings',
    ));

}