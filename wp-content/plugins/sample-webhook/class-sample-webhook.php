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

if ( ! class_exists( 'Sample_Webhook' ) ) {
    
    class Sample_Webhook {

    	private static $_instance = null;
        
        // These parameters are for custom webhook
		// Endpoint will look like https://site.url/sample-api/sample-webhook
		// network_site_url( self::$webhook . DIRECTORY_SEPARATOR . self::$webhook_tag ); //this helps return the full url
   
		/**
		 * Parent webhook
		 * replace with a unique value you want
		 * 
		 * @var string
		 */
        private static $webhook = 'sample-api';
		
		/**
		 * webhook tag
		 * replace with a unique value you want
		 * 
		 * @var string
		 */
        private static $webhook_tag = 'sample-webhook';

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
            $input = $_REQUEST;
            //start your payload processing here

            print_r(get_field("jhl_script_version", 'option'));
            //print_r($input);

        }
    }
}

new Sample_Webhook();
