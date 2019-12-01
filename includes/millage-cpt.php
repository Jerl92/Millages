<?php

// Add Music Support
add_theme_support( 'milage' );

if ( ! function_exists('millage_cpt') ) {

	// Register Custom Post Type
	function millage_cpt() {

		//one song = one post, media is other thing.
		$labels = array(
			'name'                  => _x( 'millages', 'Post Type General Name', 'millage' ),
			'singular_name'         => _x( 'millage', 'Post Type Singular Name', 'millage' ),
			'menu_name'             => __( 'millages', 'millage' ),
			'name_admin_bar'        => __( 'millage', 'millage' ),
			'archives'              => __( 'millage Archives', 'millage' ),
			'attributes'            => __( 'millage Attributes', 'millage' ),
			'parent_item_colon'     => __( 'Parent Item:', 'millage' ),
			'all_items'             => __( 'All millages', 'millage' ),
			'add_new_item'          => __( 'Add New millage', 'millage' ),
			'add_new'               => __( 'Add millage', 'millage' ),
			'new_item'              => __( 'New millage', 'millage' ),
			'edit_item'             => __( 'Edit millage', 'millage' ),
			'update_item'           => __( 'Update millage', 'millage' ),
			'view_item'             => __( 'View millage', 'millage' ),
			'view_items'            => __( 'View millages', 'millage' ),
			'search_items'          => __( 'Search millage', 'millage' ),
			'not_found'             => __( 'Not found', 'millage' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'millage' ),
			'featured_image'        => __( 'Featured Image', 'millage' ),
			'set_featured_image'    => __( 'Set featured image', 'millage' ),
			'remove_featured_image' => __( 'Remove featured image', 'millage' ),
			'use_featured_image'    => __( 'Use as featured image', 'millage' ),
			'insert_into_item'      => __( 'Insert into millage', 'millage' ),
			'uploaded_to_this_item' => __( 'Uploaded to this millage', 'millage' ),
			'items_list'            => __( 'millages list', 'millage' ),
			'items_list_navigation' => __( 'millages list navigation', 'millage' ),
			'filter_items_list'     => __( 'Filter millages list', 'millage' ),
		);
		$args = array(
			'label'                 => __( 'millage', 'millage' ),
			'description'           => __( 'millage', 'millage' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'page-attributes',' attachment'),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,		
			'exclude_from_search'   => false,
			'publicly_queryable'    => false,
			'capability_type'       => 'page'
		);

		register_post_type( 'millage', $args );
	}
	add_action( 'init', 'millage_cpt', 0 );

}


// force use of templates from plugin folder
function millages_force_template( $template )
{	
	
	if( is_singular( 'millage' ) ) {
        $template = plugin_dir_path( dirname( __FILE__ ) ) .'/templates/millage-page-template.php';
	}
	
  return $template;
  
}
add_filter( 'template_include', 'millages_force_template' );

?>