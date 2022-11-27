<?php
/**
 * Webhook class to handle custom webhook
 *
 * Helps create the webhook url and also handling whatever is sent to the url :)
 *
 * @author JHL Digital <https://www.jhldigital.com>
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SMSCentral_Webhook' ) ) {
    
    class SMSCentral_Webhook {

    	private static $_instance = null;
        
        // These parameters are for custom webhook
		// Endpoint will look like https://site.url/webhook/smscentral-inbound
		// network_site_url( self::$webhook . DIRECTORY_SEPARATOR . self::$webhook_tag ); //this helps return the full url
   
		/**
		 * Parent webhook
		 * replace with a unique value you want
		 * 
		 * @var string
		 */
        private static $webhook = 'webhook';
		
		/**
		 * webhook tag
		 * replace with a unique value you want
		 * 
		 * @var string
		 */
        private static $webhook_tag = 'smscentral-inbound';

		/**
		 * ini prefix, leave as it is :)
		 *
		 * @var string
		 */
        private static $ini_hook_prefix = 'jhl_';

		/**
		 * Action to be triggered when the url is loaded
		 * replace with a unique value you want
		 * 
		 * @var string
		 */
        private static $webhook_action = 'hook_action';
		
		/**
		 * Constructor
		 */
        public function __construct() {
        	add_action( 'init', array( $this, 'setup' ) );
            add_action( 'parse_request', array( $this, 'parse_request' ) );            
            add_action( self::$ini_hook_prefix.self::$webhook_action, array( $this, 'webhook_handler' ) );
        }

        public function setup() {
            $this->add_rewrite_rules_tags();
            $this->add_rewrite_rules();
        }

        public function parse_request( &$wp ) {
			if( self::$webhook . DIRECTORY_SEPARATOR . self::$webhook_tag == $wp->request){
                do_action( self::$ini_hook_prefix . self::$webhook_action );
                die(0);
            }
        }

        protected function add_rewrite_rules_tags() {
        	add_rewrite_tag( '%' . self::$webhook_tag . '%', '([^&]+)' );
        }

        protected function add_rewrite_rules() {
            add_rewrite_rule( '^' . self::$webhook . '/([^/]*)/?', 'index.php?' .  self::$webhook_tag . '=$matches[1]', 'top' );
        }

        /**
         * Handles the HTTP Request sent to your site's webhook
         */
        public function webhook_handler() {
            $input_raw = file_get_contents('php://input');
            $input_raw = preg_replace("/[\r\n]+/", " ", $input_raw);
            error_log( print_r( $input_raw, true ) );

            $input = json_decode( $input_raw );
            error_log( print_r( $input, true ) );

            if( ! $input ) return;

            $sender   = str_replace ('+', '', $input->sourceAddress);
            $message  = trim ($input->replyContent);

            $user = get_user_by ('login', $sender);
            if ( $user ) {
                error_log( "User already exists, start handling user response." );

                $sms_context = get_user_meta( $user->ID, 'sms_context', true );
                error_log( 'SMS CONTEXT = ' . $sms_context );

                switch( $sms_context ){
                    case 'sms_consent':
                        $this->handle_consent( $user, $message, $sms_context );
                        break;
                    case 'sms_q_a':
                        $this->handle_answer_q_a( $user, $message, $sms_context );
                        break;
                    case 'sms_q_b':
                        $this->handle_answer_q_b( $user, $message, $sms_context );
                        break;
                    case 'sms_q_01':
                    case 'sms_q_02':
                    case 'sms_q_03':
                    case 'sms_q_04':
                    case 'sms_q_05':
                    case 'sms_q_06':
                    case 'sms_q_07':
                    case 'sms_q_08':
                    case 'sms_q_09':
                    case 'sms_q_10':
                    case 'sms_q_11':
                    case 'sms_q_12':
                        $this->handle_q1_12( $user, $message, $sms_context );
                        break;
                    case 'sms_result':
                        $this->handle_answer_result( $user, $message, $sms_context );
                        break;
                }

            } else {
                error_log( "New user, create user and send consent.");

                if ( strtoupper( $message ) === 'START') {
                    $user_id = wp_create_user( $sender, wp_generate_password() );
                    if( !is_wp_error( $user_id ) ){
                        $sms_key = 'sms_consent';
                        $sms = get_field( $sms_key, 'option' );
                        $sc = new SMSCentral_Func();
                        $sc->send( $sender, $sms, $sms_key, $sms_key );
                    }else{
                        error_log( print_r($user_id, true) );
                    }
                }
            }
        }

        private function handle_consent( $user, $message, $context ){
            update_user_meta( $user->ID, $context . '_answer', $message );
            update_user_meta( $user->ID, $context . '_answer_dt', current_datetime()->format('Y-m-d H:i:s') );

            $sc = new SMSCentral_Func();
            $message = strtoupper( $message );
            if ( $message === 'YES' ) {
                $sms = get_field( 'sms_welcome', 'option' );
                $sc->send( $user->user_login, $sms, 'sms_welcome' );

                sleep (8);

                $sms = get_field( 'sms_q_a', 'option' );
                $sc->send( $user->user_login, $sms, 'sms_q_a', 'sms_q_a' );

            } elseif ( $message === 'NO' ) {
                $msg_key = 'sms_bye';
                $sms = get_field( $msg_key, 'option' );
                $sc->send( $user->user_login, $sms, $msg_key, $msg_key );

            } else {
                $msg_key = 'sms_invalid_answer_yes_no';
                $sms = get_field( $msg_key, 'option' );
                $sc->send( $user->user_login, $sms, $msg_key );
            }
        }

        private function handle_answer_q_a( $user, $message, $context ){
            update_user_meta( $user->ID, $context . '_answer', $message );
            update_user_meta( $user->ID, $context . '_answer_dt', current_datetime()->format('Y-m-d H:i:s') );

            $sc = new SMSCentral_Func();
            $message = strtoupper( $message );
            if ( $message === 'YES' ) {
                $msg_key = 'sms_q_b';
                $sms = get_field( $msg_key, 'option' );
                $sc->send( $user->user_login, $sms, $msg_key, $msg_key );
            } elseif ( $message === 'NO' ) {
                $msg_key = 'sms_q_07';
                $sms = get_field( $msg_key, 'option' );
                $sc->send( $user->user_login, $sms, $msg_key, $msg_key );
            } else {
                $msg_key = 'sms_invalid_answer_yes_no';
                $sms = get_field( $msg_key, 'option' );
                $sc->send( $user->user_login, $sms, $msg_key );
            }
        }

        private function handle_answer_q_b( $user, $message, $context ){
            update_user_meta( $user->ID, $context . '_answer', $message );
            update_user_meta( $user->ID, $context . '_answer_dt', current_datetime()->format('Y-m-d H:i:s') );

            $this->send_q1_12( $user );
        }

        private function send_q1_12( $user ) {
            $q_b_answer  = get_user_meta( $user->ID, 'sms_q_b_answer', true );

            $sc = new SMSCentral_Func();
            for( $i = 1; $i < 13; $i++ ){
                $q_num       = substr( "0" . $i, -2 );
                $sms_key     = 'sms_q_' . $q_num ;
                $sms_sent_dt = $sms_key . '_sent_dt';
                if ($i < 7) {
                    if ( mb_strpos( $q_b_answer, "$i" ) !== false ) {
                        if ( empty( get_user_meta( $user->ID, $sms_sent_dt, true ) ) ) {
                            $sms = get_field( $sms_key, 'option' );
                            $sc->send( $user->user_login, $sms, $sms_key, $sms_key );
                            break;
                        }
                    }
                } else {
                    if ( empty( get_user_meta( $user->ID, $sms_sent_dt, true ) ) ) {
                        $sms = get_field( $sms_key, 'option' );
                        $sc->send( $user->user_login, $sms, $sms_key, $sms_key );
                        break;
                    }
                }
            }
        }

        private function handle_q1_12( $user, $message, $context ) {
            update_user_meta( $user->ID, $context . '_answer', $message );
            update_user_meta( $user->ID, $context . '_answer_dt', current_datetime()->format('Y-m-d H:i:s') );

            if( $context === 'sms_q_12' ){
                $sms_key = 'sms_result';
                $sms = get_field( $sms_key, 'option' );

                $sc = new SMSCentral_Func();
                $sc->send( $user->user_login, $sms, $sms_key, $sms_key );
            } else {
                $this->send_q1_12( $user );
            }
        }

        private function handle_answer_result( $user, $message, $context ) {
            update_user_meta( $user->ID, $context . '_answer', $message );
            update_user_meta( $user->ID, $context . '_answer_dt', current_datetime()->format('Y-m-d H:i:s') );

            $GLOBALS['user_email'] = $message;
            wp_update_user( array('ID'=> $user->ID, 'user_email' => esc_attr( $GLOBALS['user_email'] ) ) );

            //Generate PDF
            $style_highlight = "color: red; font-weight: bold;";
            $merge_tokens = array( );
            for( $i = 1; $i < 13; $i++ ) {
                $q_num          = substr( "0" . $i, -2 );
                $sms_key        = 'sms_q_' . $q_num ;
                $sms_key_answer = 'sms_q_' . $q_num . "_answer";
                $answer         = get_user_meta( $user->ID, $sms_key_answer, true);
                $token_key      = "{{".$sms_key."_".$answer."}}";

                $merge_tokens[$token_key] = $style_highlight;
            }
            error_log( print_r($merge_tokens, true) );

            $attachments = array();
            $config = array (
                'output_filename' => 'HAE AS Results ' . current_time('timestamp') . '.pdf',
            );
            $result_pdf = jhl_gen_pdf( 'hae-as-result', $config, $merge_tokens );
            if ( $result_pdf !== false ){
                $attachments = array( $result_pdf );
            }

            jhl_mail_send( 'mail-your-hae-as-results', $attachments );

            update_user_meta( $user->ID, 'sms_context', 'sms_result_sent' );
        }

    }
}

new SMSCentral_Webhook();
