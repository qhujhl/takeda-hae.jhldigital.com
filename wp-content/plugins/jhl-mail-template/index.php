<?php
/**
 * JHL Mail Template
 *
 * @copyright Copyright (C) 2018-2021, JHL Digital - hello@jhldigital.com
 * @license   JHL INTERNAL ONLY
 *
 * @wordpress-plugin
 * Plugin Name: JHL Mail Template
 * Version:     1.1
 * Plugin URI:  N/A
 * Description: Create mail templates and sending emails, seamless integration with WP user data
 * Author:      JHL Digital
 * Author URI:  N/A
 * License:     JHL INTERNAL ONLY
 *
 */
define( 'MT_SITE_URL', site_url() );
define( 'MAIL_TEMPLATE','Mail Template' );
function custom_post_type() {

        $labels = array(
            'name' => MAIL_TEMPLATE,
            'singular_name' => MAIL_TEMPLATE,
            'add_new' => 'Add Template',
            'add_new_item' => 'Add Mail Template',
            'edit_item' => 'Edit Template',
            'new_item' => 'New Template',
            'all_items' => __('All Templates'),
            'view_item' => 'View Template',
            'search_items' => 'Search Template',
            'not_found' =>  'No template found',
            'not_found_in_trash' => 'There are no templates in the recycling yard.',
            'parent_item_colon' => '',
            'menu_name' => MAIL_TEMPLATE

        );
        $args = array(
            'labels' => $labels,
            'description'=> MAIL_TEMPLATE,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 5,
            'supports' => array('title','editor','author','thumbnail','excerpt','comments')
        );
        register_post_type('mail_template',$args);
}
add_action('init', 'custom_post_type');

function send_email_register_meta_box() {
    add_meta_box( 'meta-box-id', 'Mail template testing' ,'send_email_callback', 'mail_template','advanced');
}
add_action( 'add_meta_boxes', 'send_email_register_meta_box' );
?>
<?php
function send_email_callback() {?>
    <div class="ajaxtag hide-if-no-js">
        <input name="send_email" type="text" id="send_email" value=""  class="regular-text">
        <button type="button" name="send" id="send" class="button button-primary button-large">
            Send test email
        </button>
        <div class="jhl-info error-info"></div>
        <div class="jhl-info success-info"></div>
    </div>
    <?php
}?>
<?php
function send_email_scripts() {
    wp_enqueue_script('send-email-js', plugins_url( 'jhl-mail-template.js',__FILE__ ), array('jquery'), '0.1.0', true);
    wp_enqueue_style('send-email_style', plugins_url('jhl-mail-template-style.css', __FILE__));
    wp_localize_script( 'send-email-js', 'Ajax', array(
        'ajaxurl'  => admin_url( 'admin-ajax.php' )
    ));
}
add_action( 'admin_enqueue_scripts','send_email_scripts' );
add_action( 'wp_ajax_send_email', 'send_email' );
add_action( 'wp_ajax_nopriv_send_email', 'send_email' );

function send_email() {
    $email = $_POST['email'];
    $template = $_POST['template'];
    if (!empty($email) && !empty($template)){
        $res = jhl_send_email( $template, $email );
        if ($res){
            echo json_encode(['code'=>1,'msg'=>'Test mail has been sent successfully.']);
        }else{
            echo json_encode(['code'=>0,'msg'=>'Test mail was send failure.']);
        }
    }elseif(empty($email)){
        echo json_encode(['code'=>0,'msg'=>'email account is incorrect!']);
    }elseif (empty($template)){
        echo json_encode(['code'=>0,'msg'=>'Please save the template first!']);
    }
    wp_die();
}


/**
 * @param string $mail_template   Email template
 * @param array  $emails          email
 * @param array  $replace_fields  Replace fields
 */

function jhl_send_email( $mail_template='', $emails ) {
    $content = get_page_by_path('/'.$mail_template.'/',OBJECT,'mail_template');
    $mapping = get_post_meta( $content->ID, '_replace_fields', true );
    if(isset($mapping) && !empty($mapping)){
        $mapping = explode("\r\n", $mapping);
        $i=0;
        foreach ($mapping as $value){
            $value=explode('|',$value);
            $replace_fields[$i]['search_str']=trim($value[0]);
            $replace_fields[$i]['search_table']=trim($value[1]);
            $replace_fields[$i]['replace_str']=trim($value[2]);
            $i++;
        }
    }
    $subject = $content->post_title;
    $content = do_shortcode($content->post_content);
    $headers = array('Content-Type: text/html; charset=UTF-8');
    if (!is_array( $emails )){
        if (is_array($replace_fields)){
            foreach ($replace_fields as $key=>$value){
                $content = str_replace($value['search_str'],get_table_value($value['search_table'],$value['replace_str'],$emails), $content);
            }
        }
        $res = wp_mail( $emails, $subject, $content, $headers );
    }else{
        foreach ($emails as $email){
            $contents[$email]=$content;
            foreach ($replace_fields as $key=>$value){
                $contents[$email] = str_replace($value['search_str'], get_table_value($value['search_table'],$value['replace_str'],$email), $contents[$email]);
            }
            $res = wp_mail( $email, $subject, $contents[$email], $headers );
        }
    }
    return $res;
}

function get_table_value( $table, $fields, $emails ) {
        $userData = get_user_by('email', $emails);
        if( $table == 'UMETA' ){
            $value=get_user_meta( $userData->ID, $fields,true );
            return $value;
        }else if( $table == 'LOGIN_TOKEN' ){
            global $wp_hasher;
            if ( empty( $wp_hasher ) ) {
                require_once ABSPATH . WPINC . '/class-phpass.php';
                $wp_hasher = new PasswordHash( 12, true );
            }
            $edm_login_token = $wp_hasher->HashPassword($userData->user_login);
            $fields = json_decode( $fields );
            update_user_meta( $userData->ID, 'edm_login_token', $edm_login_token );
            update_user_meta( $userData->ID, 'edm_login_token_expires',format_time($fields->expire_hrs) );
            $url = parse_const_value($fields->url).'?action=edm_token_login&token='.get_user_meta($userData->ID,'edm_login_token',true).'&uid='.$userData->ID;
            return $url;
        }else if( $table == 'FORGET_PASSWORD_URL' ){
            $fields = json_decode( $fields );
            $key = get_password_reset_key($userData);
            if (!is_wp_error($key)){
                $resetlink = parse_const_value( $fields->url )."?action=edm_forgot_password&key=".$key.'&u_id='.$userData->ID;
            }
            return $resetlink;
        }else if( $table == 'DEFAULT' ){
            if ( $fields == 'MT_SITE_URL' ){
                $fields = MT_SITE_URL;
            }
            return $fields;
        }
}
add_action( 'add_meta_boxes', 'add_replace_fields_meta' );
function add_replace_fields_meta() {
    add_meta_box(
        'replace_fields_id',
        'Replace fields',
        'replace_fields_callback',
        'mail_template',
        'advanced',
        'low'
    );
}
function replace_fields_callback( $post ) {
    wp_nonce_field( 'replace_fields_callback', 'replace_fields_callback_nonce' );
    $value = get_post_meta( $post->ID, '_replace_fields', true );
    ?>
    <label for="replace_fields"></label>
    <textarea  rows="4" id="replace_fields" name="replace_fields" style="width: 100%;"><?php echo esc_attr( $value ); ?></textarea>
    <?php
}
add_action( 'save_post', 'replace_fields_save' );
function replace_fields_save( $post_id ) {
    if ( ! isset( $_POST['replace_fields_callback_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['replace_fields_callback_nonce'], 'replace_fields_callback' ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( ! isset( $_POST['replace_fields'] ) ) {
        return;
    }
    $replace_fields_arr = $_POST['replace_fields'];
    update_post_meta( $post_id, '_replace_fields', $replace_fields_arr );
}
add_action("manage_posts_custom_column",  "replace_field_custom_columns");

function replace_field_custom_columns( $column ) {
    global $post;
    switch ( $column ) {
        case "replace_fields":
            echo get_post_meta( $post->ID, '_replace_fields', true );
            break;
    }
}

function format_time($hours = 24) {
    return time()+$hours*60*60;
}

add_action( 'init', 'edm_auto_login' );
function edm_auto_login() {
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    if ($action == 'edm_token_login'){
        $token = $_GET['token'];
        $uid = $_GET['uid'];
        $u_token = get_user_meta( $uid,'edm_login_token',true );
        $u_expires = intval(get_user_meta( $uid,'edm_login_token_expires',true ));
        $nowTime = time();
        $isadmin = get_user_meta( $uid,'wp_user_level',true );
        if ($u_token == $token && $u_expires-$nowTime > 0 && $isadmin !=10){
                wp_set_current_user($uid);
                wp_set_auth_cookie($uid);
                $redirectdata = parse_url(curPageURL());
                $redirectUrl = $redirectdata['scheme'].'://'.$redirectdata['host'];
                $port = isset($redirectdata['port']) && !empty($redirectdata['port'])?':'.$redirectdata['port']:'';
                $path = $redirectdata['path'];
                $url = $redirectUrl.$port.$path;
                if ( is_user_logged_in() ) {
                    wp_redirect($url);exit();
                }
        }else{
            exit( 'Token has expired.' );
        }
    }
}

function curPageURL() {
    $pageURL = 'http';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80"){
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    }
    else{
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function parse_const_value($str){
    $parsed_str = $str;

    if (strstr( $str, 'MT_SITE_URL' )){
        $parsed_str = str_replace('MT_SITE_URL', MT_SITE_URL, $parsed_str);
    }

    if (strstr( $str, 'MT_ANOTHER_CONST')){
        $parsed_str = str_replace('MT_ANOTHER_CONST', MT_SITE_URL, $parsed_str);
    }

    return $parsed_str;
}