<?php
/**
 * Usage: [OPTION key="xxx"]
 */
add_shortcode('OPTION', 'jhl_shortcode_option');
function jhl_shortcode_option( $args ) {
    if( empty( $args['key'] ) ){
        return '';
    }else{
        return get_option( $args['key'], '' );
    }
}

/**
 * Usage: [OPTION_ACF key="xxx"]
 */
add_shortcode('OPTION_ACF', 'jhl_shortcode_option_acf');
function jhl_shortcode_option_acf( $args ) {
    if( empty( $args['key'] ) ){
        return '';
    }else{
        return get_field( $args['key'], 'option' );
    }
}

/**
 * Usage: [GET key="xxx"]
 */
add_shortcode('GET', 'jhl_shortcode_get');
function jhl_shortcode_get( $args ): string
{
    if( empty( $args['key'] ) ){
        return '';
    }else{
        return isset($_GET[$args['key']]) ? $_GET[$args['key']] : '';
    }
}

/**
 * Usage: [POST key="xxx"]
 */
add_shortcode('POST', 'jhl_shortcode_post');
function jhl_shortcode_post( $args ): string
{
    if( empty( $args['key'] ) ){
        return '';
    }else{
        return isset($_POST[$args['key']]) ? $_POST[$args['key']] : '';
    }
}

/**
 * Usage: [REQUEST key="xxx"]
 */
add_shortcode('REQUEST', 'jhl_shortcode_request');
function jhl_shortcode_request( $args ): string
{
    if( empty( $args['key'] ) ){
        return '';
    }else{
        return isset($_REQUEST[$args['key']]) ? $_REQUEST[$args['key']] : '';
    }
}

/**
 * Usage: [AJAX_DATA key="xxx"]
 */
add_shortcode('AJAX_DATA', 'jhl_shortcode_ajax_data');
function jhl_shortcode_ajax_data( $args ): string
{
    if( empty( $args['key'] ) ){
        return '';
    }else{
        $data = array();
        parse_str($_POST['data'], $data);
        return isset($data[$args['key']]) ? $data[$args['key']] : "";
    }
}

/**
 * Usage: [SESSION key="xxx"]
 */
add_shortcode('SESSION', 'jhl_shortcode_session');
function jhl_shortcode_session( $args ): string
{
    if( empty( $args['key'] ) ){
        return '';
    }else{
        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

        return isset($_SESSION[$args['key']]) ? $_SESSION[$args['key']] : '' ;
    }
}

/**
 * Usage: [GLOBAL key="xxx"]
 */
add_shortcode('GLOBAL', 'jhl_shortcode_global');
function jhl_shortcode_global( $args ): string
{
    if( empty( $args['key'] ) ){
        return '';
    }else{

        return isset($GLOBALS[$args['key']]) ? $GLOBALS[$args['key']] : '' ;
    }
}

/**
 * Usage: [THEMEURL]
 */
add_shortcode('THEMEURL', 'jhl_shortcode_theme_url');
function jhl_shortcode_theme_url(): string
{
    return get_stylesheet_directory_uri();
}

/**
 * Usage: [USERINFO key='xxx' uid='']
 */
add_shortcode('USERINFO', 'jhl_shortcode_userinfo');
function jhl_shortcode_userinfo( $args ): string
{
    $atts = shortcode_atts(
        array(
            'key' => '',
            'uid' => get_current_user_id()
        ),
        $args
    );

    $ret = "";
    $user = get_user_by('ID', $atts['uid']);
    switch($args['key']){
        case 'user_email':
            $ret = $user->user_email; break;
        case 'user_login':
            $ret = $user->user_login; break;
        case 'display_name':
            $ret = $user->display_name; break;
        default:
            $ret = "";
    }

    return $ret;
}

/**
 * Usage: [USERMETA key='xxx' uid='']
 */
add_shortcode('USERMETA', 'jhl_shortcode_usermeta');
function jhl_shortcode_usermeta( $args ) {
    $atts = shortcode_atts(
        array(
            'key' => 'nickname',
            'uid' => get_current_user_id()
        ),
        $args
    );

    return get_user_meta( $atts['uid'], $atts['key'], true );
}

/**
 * Usage: [SLUG], get post_name of current post
 */
add_shortcode('SLUG', 'jhl_shortcode_slug');
function jhl_shortcode_slug( $args ) {
    global $post;
    return $post->post_name;
}

add_filter( 'wp_kses_allowed_html', function ( $allowedposttags, $context ) {
    $allowedposttags['a']['data-link']      = 1;
    $allowedposttags['a']['href']           = 1;
    $allowedposttags['button']['data-link'] = 1;
    $allowedposttags['div']['data-link']    = 1;
    $allowedposttags['div']['style']        = 1;
    $allowedposttags['form']['name']        = 1;
    $allowedposttags['form']['action']      = 1;
    $allowedposttags['img']['src']          = 1;
    $allowedposttags['p']                   = 1;
    return $allowedposttags;
}, 10, 2 );
