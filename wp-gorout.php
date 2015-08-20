<?php

/*
Plugin Name: Go Rout Custom Posts
Plugin URI: https://gorout.net/plugins/goroutwp
Description: Adds custom post types, settings, options and required fields for extending the gorout.com website.  DO NOT REMOVE THIS PLUGIN!
Version: 1.1.0
Author: Thomas S. Butler - Head Developer for Repetix, LLC.
Author URI: http://www.gorout.com/
Text Domain: goroutwp
*/

/*
	
    * @author Thomas S. Butler (email : tbutler@gorout.com)
    * @copyright 2014-2015 All Rights Reserved. Go Rout, Repetix, LLC.

    This plugin has been developed exclusively for private use within the
    gorout.com/gorout.net websites and is not intended for distribution
    or publication. I you download this or use it, do so at your own risk.
    
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    To get a copy of the GNU General Public License, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
    Or, visit <http://www.gnu.org/licenses/>.
    
*/

/**
 * 
 * gorout Custom WP
 * @since 1.0.0
 *
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

//exit if accessed directly
if(!defined('ABSPATH')) exit;

define('goroutPATH', plugin_dir_path(__FILE__));
define('goroutURL',  plugins_url('', __FILE__));

//register text domain
add_action('admin_init', 'gorout_admin_init');
function gorout_admin_init() {
    load_plugin_textdomain('goroutwp', false, 'goroutwp');
}

//register plugin scripts and styles
add_action( 'admin_enqueue_scripts', 'gorout_admin_scripts' );
function gorout_admin_scripts() {
    wp_enqueue_script('goroutwp_script', plugins_url('/js/jquery.goroutwp.js', __FILE__ ) );
    wp_enqueue_style('goroutwp_fa', plugins_url( '/css/font-awesome.css', __FILE__ ) );
    wp_enqueue_style('goroutwp_style', plugins_url( '/css/admin.css', __FILE__ ) );
}

//add post types and taxonomies
add_action('init', 'goroutwp_cpt_init');
function goroutwp_cpt_init() {
    //add custom post type for faqs
    $labels = array(
    	'name'               => _x( 'FAQs', 'post type general name', 'goroutwp' ),
    	'singular_name'      => _x( 'FAQ', 'post type singular name', 'goroutwp' ),
    	'menu_name'          => _x( 'FAQs', 'admin menu', 'goroutwp' ),
    	'name_admin_bar'     => _x( 'FAQs', 'add new on admin bar', 'goroutwp' ),
        'add_new_item'       => __( 'Add New FAQ', 'goroutwp' ),
    	'new_item'           => __( 'New FAQ', 'goroutwp' ),
    	'edit_item'          => __( 'Edit FAQ', 'goroutwp' ),
    	'view_item'          => __( 'View FAQ', 'goroutwp' ),
    	'all_items'          => __( 'All FAQ', 'goroutwp' ),
    );
    
    $args = array(
    	'labels'             => $labels,
    	'public'             => true,
    	'publicly_queryable' => true,
    	'show_ui'            => true,
    	'show_in_menu'       => true,
    	'query_var'          => true,
    	'rewrite'            => array( 'slug' => 'faqs' ),
    	'taxonomies'         => array('faq-type'),
    	'capability_type'    => 'post',
        'has_archive'        => true,
    	'hierarchical'       => true,
    	'menu_position'      => null,
    	'supports'           => array( 'title', 'editor', 'comments', 'custom-fields', 'thumbnail' )
    );
    register_post_type( 'faqs', $args );
    
    //add custom post type for videos
    $labels = array(
    	'name'               => _x( 'Videos', 'post type general name', 'goroutwp' ),
    	'singular_name'      => _x( 'Video', 'post type singular name', 'goroutwp' ),
    	'menu_name'          => _x( 'Videos', 'admin menu', 'goroutwp' ),
    	'name_admin_bar'     => _x( 'Videos', 'add new on admin bar', 'goroutwp' ),
        'add_new_item'       => __( 'Add New Video', 'goroutwp' ),
    	'new_item'           => __( 'New Video', 'goroutwp' ),
    	'edit_item'          => __( 'Edit Video', 'goroutwp' ),
    	'view_item'          => __( 'View Video', 'goroutwp' ),
    	'all_items'          => __( 'All Video', 'goroutwp' ),	
    );
    
    $args = array(
    	'labels'             => $labels,
    	'public'             => true,
    	'publicly_queryable' => true,
    	'show_ui'            => true,
    	'show_in_menu'       => true,
    	'query_var'          => true,
    	'rewrite'            => array( 'slug' => 'videos' ),
        'taxonomies'         => array('video-type'),
    	'capability_type'    => 'post',
        'has_archive'        => true,
    	'hierarchical'       => true,
    	'menu_position'      => null,
    	'supports'           => array( 'title', 'editor', 'comments', 'custom-fields', 'excerpt', 'thumbnail' )
    );
    register_post_type( 'videos', $args );
    
    //register taxomonies for custom post types
    register_taxonomy("faq-type", array("faqs"), array("hierarchical" => true, "label" => "FAQ Categories", "singular_label" => "FAQ Category", "rewrite" => true));
    register_taxonomy("video-type", array("videos"), array("hierarchical" => true, "label" => "Video Categories", "singular_label" => "Video Category", "rewrite" => true));
}

//add metabox for videos
add_action( 'add_meta_boxes', 'gorout_video_metabox', 0 );
function gorout_video_metabox() {
    add_meta_box('gorout_video_metabox','Add/Edit Video File','gorout_video_content', 'videos', 'normal', 'high');
}

//metabox content for viedeo cpt
function gorout_video_content() {
    
    global $wpdb, $post;    
    $video_type = get_post_meta( $post->ID, 'video_type', true );
    $video_code = get_post_meta( $post->ID, 'video_code', true );
    wp_nonce_field( 'gorout_video_metabox', 'gorout_video_metabox_nonce' );
    
    require_once dirname( __FILE__ ) .'/videoform.php';
    
}

//save metabox content for viedeo cpt
add_action( 'save_post', 'gorout_video_metabox_data' );
function gorout_video_metabox_data( $post_id ) {
   	
    if ( ! isset( $_POST['gorout_video_metabox_nonce'] ) ) { return; }
	if ( ! wp_verify_nonce( $_POST['gorout_video_metabox_nonce'], 'gorout_video_metabox' ) ) { return; }
	
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	
    if ( isset( $_POST['post_type'] ) && 'videos' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) )  { return; }
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) )  { return; }
	}
    
	if ( ! isset( $_POST['video_type'] ) || ! isset( $_POST['video_code'] ) ) { return; }
    
    $type_data = sanitize_text_field( $_POST['video_type'] );
    update_post_meta( $post_id, 'video_type', $type_data );

    $video_id = sanitize_text_field( $_POST['video_code'] );
    
    if ($_POST['video_type'] == "vimeo"){
        $video_data = $video_id;
    } else {
        $video_data = $video_id;
    }
    
    update_post_meta( $post_id, 'video_code', $video_data );
    
}

//current year shortcode
function shorty_thisyear_func() { 
    
    return date('Y'); 
    
}
add_shortcode( 'thisyear', 'shorty_thisyear_func' );

//phone number link code
function shorty_phonenum_func($atts) { 
    
    extract( shortcode_atts(
		array('phone' => ''), $atts )
	);
    
    //replace all non numeric characters
    $num = preg_replace('/\D/ ', '', $phone);
    
    if(wp_is_mobile()) {
        return '<a href="tel:+1' .$num. '">' .$phone. '</a>';
    } else {
        return '<a href="callto:+1' .$num. '">' .$phone. '</a>';
    }
 
}
add_shortcode( 'phonelink', 'shorty_phonenum_func' );

//load hidden divs in the footer file
add_action('admin_footer', 'goroutwp_footer_containers');
function goroutwp_footer_containers() {
    echo '<div id="response-div"></div>';
    echo '<div id="notice-overlay"></div>';    
    echo '<div id="preview-loader"></div>'; 
}

//add filter to return post_meta with API (for videos)
add_filter( 'json_prepare_post', function ($data, $post, $context) {
$data['video_vars'] = array(
    'video_type' => get_post_meta( $post['ID'], '', true ),
    'video_code' => get_post_meta( $post['ID'], '', true )
);
return $data; }, 10, 3 );