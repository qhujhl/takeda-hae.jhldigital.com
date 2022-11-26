<?php
/*
 * Plugin Name: JHL WP Components
   Version: 1.1
   Description: Create component for website pages. e.g. header, footer, menu etc.
   Author: JHL Digital
*/

// Register Custom Post Type
function components_post_type() {

    $labels = array(
        'name'                => __( 'Components'),
        'singular_name'       => __( 'Component'),
        'menu_name'           => __( 'Components'),
        'name_admin_bar'      => __( 'Components'),
        'parent_item_colon'   => __( 'Parent Item:'),
        'all_items'           => __( 'All Components'),
        'add_new_item'        => __( 'Add New Component'),
        'add_new'             => __( 'Add New' ),
        'new_item'            => __( 'New Component'),
        'edit_item'           => __( 'Edit Component'),
        'update_item'         => __( 'Update Component'),
        'view_item'           => __( 'View Component'),
        'search_items'        => __( 'Search Components'),
        'not_found'           => __( 'Not found'),
        'not_found_in_trash'  => __( 'Not found in Trash'),
    );
    $args = array(
        'label'               => __( 'Components'),
        'description'         => __( 'Website components'),
        'labels'              => $labels,
        'supports'            => array( ),
        'taxonomies'          => array( 'category', 'post_tag' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
    );
    register_post_type( 'component', $args );
}
add_action( 'init', 'components_post_type', 0 );


function set_component_columns($columns) {
    return array(
        'cb' => '<input type="checkbox" />',
        'title' => __('Title'),
        'author' => __('Author'),
        'categories' => __('Categories'),
        'tags' => __('Tags'),
        'shortcode' =>__( 'Shortcode'),
        'date' => __('Date')
        
    );
}
add_filter('manage_component_posts_columns' , 'set_component_columns');



function component_custom_columns( $column, $post_id ) {
    global $post;
    switch ( $column ) {
        case 'shortcode':
            echo '[COMPONENT slug="'.$post->post_name.'"]';
            break;
        default:

    }
}
add_action( 'manage_component_posts_custom_column' , 'component_custom_columns', 10, 2 );



function shortcode_component($args){
    $defaults = array(
        'numberposts' => 1,
        'post_type' => 'component'
    );
    $r = wp_parse_args( array('name'=>$args['slug']), $defaults );

    $posts = get_posts($r);    
    if($posts){
        $found_post = $posts[0];        
        return do_shortcode($found_post->post_content);
    }else{
        return "";
    }
    
}
add_shortcode('COMPONENT', 'shortcode_component');


