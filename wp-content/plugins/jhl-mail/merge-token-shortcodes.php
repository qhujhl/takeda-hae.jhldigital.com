<?php

add_shortcode('TOKEN_USERMETA', 'shortcode_token_usermeta');
function shortcode_token_usermeta( $atts ) {
    $op = shortcode_atts(
        array(
            'uid' => '',
            'key' => ''
        ),
        $atts
    );

    $uid = $op['uid'];
    $key = $op['key'];

    if(empty($uid)){
        $uid = get_current_user_id();
    }

    return get_user_meta( $uid, $key, true );
}