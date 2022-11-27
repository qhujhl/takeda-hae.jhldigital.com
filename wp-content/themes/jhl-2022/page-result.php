<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */
get_header();

global $post;
$uuid = $_GET['token'];

$users = get_users(array(
    'meta_key' => 'uuid',
    'meta_value' => $uuid
));
$user = $users[0];

update_user_meta( $user->ID, 'hcp_login_approval', '');
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
?>
<div class="page-main <?php echo $post->post_name; ?>">
<?php
$post = ju_reload_post_content_from_file($post);
$content = do_shortcode(do_blocks($post->post_content));

foreach ($merge_tokens as $key => $val){
    $content = str_replace( $key, $val,  $content);
}

echo $content;
?>
</div>
<?php
get_footer();
?>