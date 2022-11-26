<?php
add_shortcode('NOWDATE', 'NOWDATE');
function NOWDATE($atts){
    $y = date('Y');
    $m = date('m');
    $d = date('d');
    return $d.'/'.$m.'/'.$y;
}

/**
 * Allow shortcode run in html attribute
 */
add_filter( 'wp_kses_allowed_html', function ( $allowedposttags, $context ) {
    if ( $context == 'post' ) {
        $allowedposttags['form']['action'] = 1;
    }
    return $allowedposttags;
}, 10, 2 );