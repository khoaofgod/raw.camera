<?php

require_once(__DIR__."/core.php");
require_once (ABSPATH."/kbLibs/phpKb/Dom.php");
require_once (ABSPATH."/kbLibs/phpKb/Kb.php");
require_once (ABSPATH."/kbLibs/phpKb/Curl.php");

/*
Plugin Name: KB Compare Camera
Plugin URI: https://www.raw.camera
Description: For Personal User
Version: 0.1.0
Author: kbLibs
Author URI: https://phpfastcache.com
*/

function cptui_register_my_cpts() {

	/**
	 * Post Type: compares.
	 */

	$labels = array(
		"name" => __( "compares", "royal-child" ),
		"singular_name" => __( "compare", "royal-child" ),
		"menu_name" => __( "Compares", "royal-child" ),
	);

	$args = array(
		"label" => __( "compares", "royal-child" ),
		"labels" => $labels,
		"description" => "Compare Camera and Lens",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "compare",
		"has_archive" => true,
		"show_in_menu" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "compare", "with_front" => true ),
		"query_var" => true,
		"menu_position" => 10,
		"supports" => array( "title", "editor", "thumbnail", "excerpt", "trackbacks", "custom-fields", "comments", "author", "page-attributes", "post-formats", "custom_support1" ),
		"taxonomies" => array( "category", "post_tag" ),
	);

	register_post_type( "compare", $args );
}

add_action( 'init', 'cptui_register_my_cpts' );

function compareCamera($post_content) {
	$post_id = get_the_ID();
	$kb_type = get_post_custom_values("kb_type",$post_id);
	$kb_type = is_null($kb_type) ? "empty" : $kb_type[0];
	$core = new compareCamera\core();
	$core->setPostId($post_id);
	if(method_exists($core,$kb_type)) {
		$core->$kb_type($post_content);
	}
}



// add_action('the_content','compareCamera');


/**
 * Register a custom menu page.
 */
function wpdocs_register_my_custom_menu_page() {
	$splug = 'compareCamera/admin/';
	$parent = $splug.'admin.php';
	add_menu_page(
		 "Server Hook",
		'Camera Servers',
		'manage_options',
			$parent,
		'',
		null,6
	);
	add_submenu_page($parent, "Run Import",
		"Run Import", "manage_options", $splug.'runImport.php');
	add_submenu_page($parent, "Testing",
		"Testing", "manage_options", $splug.'runTest.php');
	add_submenu_page($parent, "Create Class",
		"Create Class", "manage_options", $splug.'createClass.php');
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

