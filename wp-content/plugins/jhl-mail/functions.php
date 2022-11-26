<?php

/**
 * @param string $slug
 */
function jhl_mail_send(string $slug) {
    // Step 1: get original mail template, subject and content
    $mail = get_page_by_path( $slug,OBJECT,'jhl_mail' );
    if( is_null( $mail ) ) { return; }

    $title   = do_shortcode( $mail->post_title );
    $content = do_shortcode( $mail->post_content );

    // Step 2: set mail receivers
    $to_arr  = ju_textarea_to_array( do_shortcode( get_field('jhl_mail_to',  $mail->ID) ) );
    $cc_arr  = ju_textarea_to_array( do_shortcode( get_field('jhl_mail_cc',  $mail->ID) ) );
    $bcc_arr = ju_textarea_to_array( do_shortcode( get_field('jhl_mail_bcc', $mail->ID) ) );

    // Step 3: set mail headers include CC & BCC
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    foreach ($cc_arr as $cc)    { $headers[] = 'Cc: ' . $cc; }
    foreach ($bcc_arr as $bcc)  { $headers[] = 'Bcc: ' . $bcc; }

    // Step 4: process merge tokens
    $merge_tokens = get_field('jhl_mail_tokens', $mail->ID);
    if( $merge_tokens ) {
        foreach( $merge_tokens as $merge_token ) {
            $token = $merge_token['token'];
            $value = do_shortcode( $merge_token['value'] );
            $content = str_replace( $token, $value, $content );
        }
    }

    // Step 5: send email
    foreach ($to_arr as $to){
        wp_mail( $to, $title, $content, $headers );
    }
}