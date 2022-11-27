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
?>
<div class="page-main <?php echo $post->post_name; ?>">
<?php
$post = ju_reload_post_content_from_file($post);
echo do_shortcode(do_blocks($post->post_content));
?>
</div>
<?php
get_footer();
?>