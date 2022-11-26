<?php

require_once 'inc/shortcodes.php';
require_once 'inc/ajax.php';


/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function jhl_setup() {
    /*
     * Make theme available for translation.
     * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/twentyseventeen
     * If you're building a theme based on Twenty Seventeen, use a find and replace
     * to change 'twentyseventeen' to the name of your theme in all the template files.
     */
    load_theme_textdomain( 'jhl' );
    
    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );
    
    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'jhl-featured-image', 2000, 1200, true );
    add_image_size( 'jhl-thumbnail-avatar', 100, 100, true );
    
}
add_action( 'after_setup_theme', 'jhl_setup' );


/**
 * Enqueue scripts and styles.
 * 
 * External resources like webfonts, 3rd libraries need be included in header_common.php
 * 
 */
function jhl_scripts() {

    $scripts_version = get_field("scripts_version", "option");;
    
    // Remove useless scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    // Add fonts, used in the main stylesheet.
    wp_enqueue_style( 'jhl-fonts', get_theme_file_uri( '/assets/css/fonts.css' ), null, $scripts_version );
    wp_enqueue_style( 'jhl-color', get_theme_file_uri( '/assets/css/color.css' ), null, $scripts_version );

    // Add webfonts
    wp_enqueue_style( 'jhl-gfonts-saira-extra-condensed', 'https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:500,700', null, $scripts_version );
    wp_enqueue_style( 'jhl-gfonts-saira-muli', 'https://fonts.googleapis.com/css?family=Muli:400,400i,800,800i', null, $scripts_version );

    // Add 3rd css libraries
    wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/vendor/bootstrap-5.2.2/css/bootstrap.min.css' ), null, $scripts_version );
    wp_enqueue_style( 'fontawesome', get_theme_file_uri( '/vendor/fontawesome/css/all.min.css' ), null, $scripts_version );

    // Theme main stylesheet.
    wp_enqueue_style( 'jhl-style', get_stylesheet_uri(), null, $scripts_version );

    ////////////////////////////////////////
    
    // Add 3rd JS libraries
    wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/vendor/bootstrap-5.2.2/js/bootstrap.bundle.js' ), array( 'jquery' ), $scripts_version, true );
    
    // The major JS file
    wp_enqueue_script( 'jhl-global', get_theme_file_uri( '/assets/js/global.js' ), array( 'jquery' ), $scripts_version, true );

    wp_localize_script( 'jhl-global', 'Ajax', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'jhl-ajax-nonce' )
    ));
}
add_action( 'wp_enqueue_scripts', 'jhl_scripts' );

show_admin_bar( false );
