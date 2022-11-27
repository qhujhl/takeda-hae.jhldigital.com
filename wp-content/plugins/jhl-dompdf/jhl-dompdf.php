<?php
/**
 * JHL DOMPDF
 *
 * @copyright Copyright (C) 2018-2022, JHL Digital - hello@jhldigital.com
 * @license   JHL INTERNAL ONLY
 *
 * @wordpress-plugin
 * Plugin Name: JHL DOMPDF
 * Version:     1.1
 * Description: JHL common functions for global usage.
 * Author:      JHL Digital
 * Author URI:  https://www.jhldigital.com
 * License:     JHL INTERNAL ONLY
 *
 */

require 'vendor/autoload.php';

use Dompdf\Dompdf;

function jhl_gen_pdf( $post_slug, $filename = '', $merge_token = array() ) {

    $p = get_page_by_path( $post_slug,OBJECT,'component' );
    if( is_null( $p ) ) { return; }

    $content = do_shortcode( $p->post_content );

    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    $dompdf->loadHtml( $content );

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser
    //$dompdf->stream();

    if ( empty($filename) ) {
        $filename = current_time('timestamp') . ".pdf";
    }
    $uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'dompdf';
    wp_mkdir_p( $uploads_dir );

    $filepath = trailingslashit( $uploads_dir ) . $filename;
    $output = $dompdf->output();
    $result = file_put_contents( $filepath, $output );

    if ( $result !== false ) {
        return $filepath;
    } else {
        return false;
    }

}