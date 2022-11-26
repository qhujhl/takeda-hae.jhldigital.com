<?php
get_header();
global $post;

$p = ju_reload_post_content_from_file($post);
echo $p->post_content;

get_footer();

