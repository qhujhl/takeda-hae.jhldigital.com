<?php
/**
 * Functions for SMS Central
 *
 * @author JHL Digital <https://www.jhldigital.com>
 *
 */

if ( ! class_exists( 'SMSCentral_Func' ) ) {
    
    class SMSCentral_Func {

        private $api_endpoint;
        private $api_username;
        private $api_password;
        private $phone_number;
		
		/**
		 * Constructor
		 */
        public function __construct() {
            $this->api_endpoint = get_field('smscentral_api_endpoint', 'option');
            $this->api_username = get_field('smscentral_api_username', 'option');
            $this->api_password = get_field('smscentral_api_password', 'option');
            $this->phone_number = get_field('smscentral_phone_number', 'option');
        }

        public function send( $to, $msg, $msg_key = null, $context = null ) {
            $rest_client = new RestClient();

            $result = $rest_client->post(
                $this->api_endpoint,
                [   'USERNAME'     => $this->api_username,
                    'PASSWORD'     => $this->api_password,
                    'ACTION'       => "send",
                    'ORIGINATOR'   => $this->phone_number,
                    'RECIPIENT'    => $to,
                    'MESSAGE_TEXT' => $msg,
                ]);

            $user = get_user_by('login', $to);

            if ( !empty( $msg_key ) ) {
                update_user_meta( $user->ID, $msg_key . '_sent_dt', current_datetime()->format('Y-m-d H:i:s') );
            }

            if ( !empty( $context ) ) {
                update_user_meta( $user->ID, 'sms_context', $context );
            }

            error_log( print_r( $result, true ) );
            return $result;
        }
    }
}

