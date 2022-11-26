<?php
/**
 * JHL Utility
 *
 * @copyright Copyright (C) 2018-2022, JHL Digital - hello@jhldigital.com
 * @license   JHL INTERNAL ONLY
 *
 * @wordpress-plugin
 * Plugin Name: JHL Utility
 * Version:     1.1
 * Description: JHL common functions for global usage.
 * Author:      JHL Digital
 * Author URI:  https://www.jhldigital.com
 * License:     JHL INTERNAL ONLY
 *
 */

require_once 'functions-validation.php';
require_once 'functions-shortcodes.php';

function jhl_utility_scripts() {
    wp_enqueue_script('jhl-utility-js', plugins_url( 'functions-validation.js',__FILE__ ), array('jquery'), '1.0.0', true);
}
add_action( 'wp_enqueue_scripts', 'jhl_utility_scripts' );


/**===========================================
 * @param string $date_time_str required
 * @param string $from_zone
 * @param string $to_zone
 * @param string $format
 * @return string  Date time string｜Empty string
 * @throws Exception
 */
function ju_to_timezone( $date_time_str = '', $format = 'Y-m-d H:i:s', $from_zone = 'UTC', $to_zone = 'Australia/Sydney') {
    if ( !empty($date_time_str) ){
        $date = new DateTime( $date_time_str, timezone_open($from_zone) );
        $date->setTimezone( timezone_open( $to_zone ) );
        return $date->format( $format );
    }else{
        return '';
    }
}


/**
 * @param string $input
 * @param bool $remove_empty_lines
 * @return array
 */
function ju_textarea_to_array(string $input, $remove_empty_lines = false): array
{
    $ret = null;

    if(empty($input)) {
        $ret = array();
    } else {
        $lines = explode(PHP_EOL, $input);
        if ($remove_empty_lines) {
            $lines = array_map('trim', $lines);
            $lines = array_filter($lines, function($value) {
                return $value !== '';
            });
            $lines = array_values($lines);
        }

        $ret = $lines;
    }

    return $ret;
}


/**
 * @param $options_field_name
 * @return false|string[]
 */
function ju_text_to_array( $options_field_name ) {
    if ( !empty($options_field_name) ){
        $str = get_option( $options_field_name );
        $arr = explode( ',' , $str );
        return $arr;
    }else{
        return false;
    }
}


/**
 * Add target="_blank" to <a> tag.
 * Use make_clickable() to add href link to all URLs.
 * A combination usage will be: ju_target_blank(make_clickable($content)).
 *
 * @param $content
 * @return string|string[]|null
 */
function ju_target_blank($content){
    $pattern = '/<a(.*?)?href=[\'"]?[\'"]?(.*?)?>/i';

    $content = preg_replace_callback($pattern, function($m){
        $tpl = array_shift($m);
        $hrf = isset($m[1]) ? $m[1] : null;

        if ( preg_match('/target=[\'"]?(.*?)[\'"]?/i', $tpl) ) {
            return $tpl;
        }

        if ( trim($hrf) && 0 === strpos($hrf, '#') ) {
            return $tpl; // anchor links
        }

        return preg_replace_callback('/href=/i', function($m2){
            return sprintf('target="_blank" %s', array_shift($m2));
        }, $tpl);

    }, $content);

    return $content;
}

/**
 * Filter post content, e.g. load post content from file
 *
 * @param $post_obj object if null, global post will be used.
 * @return object
 */
function ju_reload_post_content_from_file($post_obj = null){
    global $post;

    $post_obj = empty($post_obj) ? $post : $post_obj;

    $file = 'html/'.$post_obj->post_type.'/'.$post_obj->post_name.'.html';
    $file_path = get_theme_file_path($file);
    if(file_exists($file_path)){
        $post_obj->post_content = file_get_contents($file_path);
    }

    return $post_obj;
}
