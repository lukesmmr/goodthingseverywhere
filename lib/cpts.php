<?php
// home page slider
add_action( 'init', 'goodthings_carousel' );
function goodthings_carousel() {
  register_post_type( 'carousel',
    array(
      'labels' => array(
	    'name'               => _x( 'Carousel', 'post type general name' ),
	    'singular_name'      => _x( 'Carousel', 'post type singular name' ),
	    'add_new'            => _x( 'Add new', 'slide' ),
	    'add_new_item'       => __( 'Add new Slide' ),
	    'edit_item'          => __( 'Edit Slide' ),
	    'new_item'           => __( 'New Slide' ),
	    'all_items'          => __( 'All Slides' ),
	    'view_item'          => __( 'View Slide' ),
	    'search_items'       => __( 'Search slides' ),
	    'not_found'          => __( 'No slides found' ),
	    'not_found_in_trash' => __( 'No slides in trash' ),
	    'parent_item_colon'  => '',
	    'menu_name'          => 'Carousel'
	  ),
      'public' 				=> true,
      'has_archive' 		=> false,
      'menu_icon' 			=> 'dashicons-images-alt',
      'menu_position'		=> 4,
      'show_ui'				=> true,
      'can_export'			=> true,
      'exclude_from_search'	=> true,
      //'rewrite' 			=> array('slug' => 'slide'),
      'supports'      		=> array( 'title', 'thumbnail', 'revisions')
    )
  );
}

// work section
add_action( 'init', 'goodthings_portfolio' );
function goodthings_portfolio() {
  register_post_type( 'project',
    array(
      'labels' => array(
	    'name'               => _x( 'Projects', 'post type general name' ),
	    'singular_name'      => _x( 'Projects', 'post type singular name' ),
	    'add_new'            => _x( 'Add new', 'project' ),
	    'add_new_item'       => __( 'Add new Project' ),
	    'edit_item'          => __( 'Edit Project' ),
	    'new_item'           => __( 'New Project' ),
	    'all_items'          => __( 'All Projects' ),
	    'view_item'          => __( 'View Project' ),
	    'search_items'       => __( 'Search projects' ),
	    'not_found'          => __( 'No projects found' ),
	    'not_found_in_trash' => __( 'No projects in trash' ),
	    'parent_item_colon'  => '',
	    'menu_name'          => 'Projects'
	  ),
      'public' 					=> true,
      'has_archive' 		=> true,
      'menu_icon' 			=> 'dashicons-format-gallery',
      'taxonomy'      	=> 'project-category, project-type',
      'menu_position'		=> 5,
      'show_ui'					=> true,
      'can_export'			=> true,
      'exclude_from_search'	=> false,
      'rewrite' 				=> array('slug' => 'project'),
      'supports'      	=> array( 'title', 'editor', 'thumbnail', 'revisions' )
    )
  );
}