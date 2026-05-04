<?php


/**
 * The PostType class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Listbuildingbot
 * @subpackage Listbuildingbot/includes
 * @author     Veena Prashanth <veena@digitalaccesspass.com>
 */
class Listbuildingbot_PostTypes {

	public function __construct() {
		add_action('init', array($this,'addPostTypes'));
	}


	public function addPostTypes(){

		$labels = array(
	        'name'               => 'Chatflows',
	        'singular_name'      => 'Chatflow',
	        'add_new'            => 'Add New',
	        'add_new_item'       => 'Add New Chatflow',
	        'edit_item'          => 'Edit Chatflow',
	        'new_item'           => 'New Chatflow',
	        'view_item'          => 'View Chatflow',
	        'search_items'       => 'Search Chatflow',
	        'not_found'          => 'No Chatflows found',
	        'not_found_in_trash' => 'No Chatflows found in Trash',
	        'parent_item_colon'  => '',
	        'menu_name'          => 'ListBuildingBot'
	    );

	    $args = array(
	        'labels'              => $labels,
	        'public'              => true,
	        'publicly_queryable'  => true,
	        'show_ui'             => true,
	        'show_in_menu'        => false,
	        'query_var'           => true,
	        'rewrite'             => array('slug' => 'lbb-chatflow'), // Change 'custom-posts' to your desired URL slug
	        'capability_type'     => 'post',
	        'has_archive'         => true,
	        'hierarchical'        => false,
	        'menu_position'       => null,
	        'supports'            => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
	        //'taxonomies'          => array('category', 'post_tag'),
	    );

	    register_post_type('lbb-chatflow', $args);

	    $labels = array(
	        'name'               => 'Actions',
	        'singular_name'      => 'Action',
	        'add_new'            => 'Add New',
	        'add_new_item'       => 'Add New Action',
	        'edit_item'          => 'Edit Action',
	        'new_item'           => 'New Action',
	        'view_item'          => 'View Action',
	        'search_items'       => 'Search Action',
	        'not_found'          => 'No Actions found',
	        'not_found_in_trash' => 'No Actions found in Trash',
	        'parent_item_colon'  => '',
	        'menu_name'          => 'Chatflow Action'
	    );

	    $args = array(
	        'labels'              => $labels,
	        'public'              => true,
	        'publicly_queryable'  => true,
	        'show_ui'             => true,
	        'show_in_menu'        => false,
	        'query_var'           => true,
	        'rewrite'             => array('slug' => 'lbb-chatflow-action'), // Change 'custom-posts' to your desired URL slug
	        'capability_type'     => 'post',
	        'has_archive'         => true,
	        'hierarchical'        => false,
	        'menu_position'       => null,
	        'supports'            => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
	        'exclude_from_search' => true
	        //'taxonomies'          => array('category', 'post_tag'),
	    );

	    register_post_type('lbb-chatflow-action', $args);
	}

}

new Listbuildingbot_PostTypes();