<?php
add_action( 'wp_ajax_nopriv_hcp_query_pt', 'hcp_query_pt' );
add_action( 'wp_ajax_hcp_query_pt', 'hcp_query_pt' );
function hcp_query_pt() : void
{
    // #1. verify nonce, if fail send ajax error and die
    verify_nonce_ajax( $_POST['nonce'], 'jhl-ajax-nonce'  );

    // #2. parse data
    $data = array();
    parse_str( $_POST['data'], $data );

    if( empty($data['pt_number']) ){
        wp_send_json_error("Please enter patient number.");
        wp_die();
    }

    if( strlen($data['pt_number']) < 10 ){
        wp_send_json_error("Invalid patient number.");
        wp_die();
    }

    $pt = get_pt_data( $data['pt_number'] );

    if( $pt === null ){
        wp_send_json_error( 'Invalid patient number.' );
    } else {
        $sms_key = 'sms_hcp_login_approval';
        $sms = get_field( $sms_key, 'option' );

        $sc = new SMSCentral_Func();
        $sc->send( $pt->user_login, $sms, $sms_key, $sms_key );

        wp_send_json_success("Please wait, pending patient approval...");
    }

    wp_die();
}


add_action( 'wp_ajax_nopriv_query_pt_approval', 'query_pt_approval' );
add_action( 'wp_ajax_query_pt_approval', 'query_pt_approval' );
function query_pt_approval() : void
{
    $pt_number = $_POST['data'];

    if( strlen($pt_number) < 10 ){
        wp_send_json_error("Invalid patient number.");
        wp_die();
    }

    $pt = get_pt_data( $pt_number );
    if( $pt === null ){
        wp_send_json_error( 'Invalid patient number.' );

    } else {
        $approval = get_user_meta( $pt->ID, 'sms_hcp_login_approval_answer', true );
        if( strtoupper($approval) === 'YES' ){
            $uuid = get_user_meta( $pt->ID, 'uuid', true );
            if( empty($uuid) ){
                $uuid = wp_generate_uuid4();
                update_user_meta( $pt->ID, 'uuid', $uuid );
            }

            wp_send_json_success("/result/?token=".$uuid);
        }else{
            wp_send_json_error("Waiting");
        }
    }

    wp_die();
}

function verify_nonce_ajax( $nonce, $action = -1 ){
    if ( ! wp_verify_nonce( $nonce, $action ) ) {
        $error = new WP_Error();
        $error->add( 'message_error', "Session check failed, please refresh page and try again." );
        wp_send_json_error( $error );
    }
}

function get_pt_data ( $pt_number ){
    global $wpdb;

    return $wpdb->get_row("SELECT ID, user_login FROM wp_users WHERE user_login LIKE '%" . substr($pt_number, -9)."'" );
}