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
use Dompdf\Options;

function jhl_gen_pdf( $post_slug, $config = array(), $merge_tokens = array() ) {
    $config = array_merge(
        array(
            'paper_size'        => 'A4',
            'paper_orientation' => 'portrait',
            'output_filename'   => $post_slug . ".pdf",
            'default_font'      => 'sans-serif',
            'output_mode'       => 'file'
        ),
        $config
    );

    $p = get_page_by_path( $post_slug,OBJECT,'component' );
    if( is_null( $p ) ) { return; }

    $pdf_template = do_shortcode( $p->post_content );
    foreach ($merge_tokens as $key => $val){
        $pdf_template = str_replace( $key, $val,  $pdf_template);
    }

    // instantiate and use the dompdf class
    $options = new Options();
    $options->set( 'defaultFont', $config['default_font'] );
    $options->setIsRemoteEnabled(true);
    //$options->setDebugPng(true);

    $dompdf = new Dompdf( $options );
    $dompdf->loadHtml( $pdf_template );

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper( $config['paper_size'], 'portrait' );

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser
    if ( $config['output_mode'] === 'stream' ) {
        $dompdf->stream();
        wp_die();
    } else {
        $uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'dompdf';
        wp_mkdir_p( $uploads_dir );

        $filepath = trailingslashit( $uploads_dir ) . $config['output_filename'];
        $output = $dompdf->output();
        $result = file_put_contents( $filepath, $output );

        if ( $result !== false ) {
            return $filepath;
        } else {
            return false;
        }
    }
}
