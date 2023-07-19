<?php
/**
 * @package cpt_compenion_plugin
 */
/*
Plugin Name: CPT Compenion Plugin
Plugin URI: https://ptc.com/
Description: CPT Compenion Plugin plugin is a light weight for count words.
Version: 1.0
Requires at least: 1.0
Requires PHP: 5.2
Author: HemalRika(HR) Foundation
Author URI: https://hemalrika-hr.com
License: GPLv2 or later
Text Domain: cpt_compenion_plugin
*/
function cpt_plugins_loaded() {
    load_plugin_textdomain('cb_compenion_plugin', false, __FILE__.'/languages');
}
add_action('plugins_loaded', 'cpt_plugins_loaded');




function ccp_register_my_cpts_book() {

	/**
	 * Post Type: books.
	 */

	$labels = [
		"name" => esc_html__( "books", "my-plugin" ),
		"singular_name" => esc_html__( "Book", "my-plugin" ),
		"all_items" => esc_html__( "My Books", "my-plugin" ),
		"add_new" => esc_html__( "New Book", "my-plugin" ),
		"featured_image" => esc_html__( "Book Cover", "my-plugin" ),
	];

	$args = [
		"label" => esc_html__( "books", "my-plugin" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "book", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail", "excerpt" ],
		"show_in_graphql" => false,
	];

	register_post_type( "book", $args );
}

add_action( 'init', 'ccp_register_my_cpts_book' );
