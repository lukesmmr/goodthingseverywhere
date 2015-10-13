<?php // activities offered
function project_category() {

	$labels = array(
		'name'                       => _x( 'Category', 'Category', 'roots' ),
		'singular_name'              => _x( 'Category', 'Category', 'roots' ),
		'menu_name'                  => __( 'Project category', 'roots' ),
		'all_items'                  => __( 'All categories', 'roots' ),
		'parent_item'                => __( 'Parent category', 'roots' ),
		'parent_item_colon'          => __( 'Parent category:', 'roots' ),
		'new_item_name'              => __( 'New category', 'roots' ),
		'add_new_item'               => __( 'Add category', 'roots' ),
		'edit_item'                  => __( 'Edit category', 'roots' ),
		'update_item'                => __( 'Update category', 'roots' ),
		'view_item'                  => __( 'View category', 'roots' ),
		'separate_items_with_commas' => __( 'Separate types with commas', 'roots' ),
		'add_or_remove_items'        => __( 'Add or remove categories', 'roots' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'roots' ),
		'popular_items'              => __( 'Popular categories', 'roots' ),
		'search_items'               => __( 'Search categories', 'roots' ),
		'not_found'                  => __( 'Not Found', 'roots' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'project-category', array( 'project' ), $args );

}
add_action( 'init', 'project_category', 0 );

function project_type() {

	$labels = array(
		'name'                       => _x( 'Type', 'Type', 'roots' ),
		'singular_name'              => _x( 'Type', 'Type', 'roots' ),
		'menu_name'                  => __( 'Project type', 'roots' ),
		'all_items'                  => __( 'All types', 'roots' ),
		'parent_item'                => __( 'Parent type', 'roots' ),
		'parent_item_colon'          => __( 'Parent type:', 'roots' ),
		'new_item_name'              => __( 'New type', 'roots' ),
		'add_new_item'               => __( 'Add type', 'roots' ),
		'edit_item'                  => __( 'Edit type', 'roots' ),
		'update_item'                => __( 'Update type', 'roots' ),
		'view_item'                  => __( 'View type', 'roots' ),
		'separate_items_with_commas' => __( 'Separate types with commas', 'roots' ),
		'add_or_remove_items'        => __( 'Add or remove types', 'roots' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'roots' ),
		'popular_items'              => __( 'Popular types', 'roots' ),
		'search_items'               => __( 'Search types', 'roots' ),
		'not_found'                  => __( 'Not Found', 'roots' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'project-type', array( 'project' ), $args );

}
add_action( 'init', 'project_type', 0 );