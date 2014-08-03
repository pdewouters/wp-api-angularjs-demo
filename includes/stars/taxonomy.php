<?php
// Register Custom Taxonomy
function pdw_wpc_register_gh_star_tax() {

	$labels = array(
		'name'                       => 'Tags',
		'singular_name'              => 'Tag',
		'menu_name'                  => 'Star Tags',
		'all_items'                  => 'All Tags',
		'parent_item'                => 'Parent Tag',
		'parent_item_colon'          => 'Parent Tag:',
		'new_item_name'              => 'New Tag Name',
		'add_new_item'               => 'Add New Tag',
		'edit_item'                  => 'Edit Tag',
		'update_item'                => 'Update Tag',
		'separate_items_with_commas' => 'Separate tags with commas',
		'search_items'               => 'Search tags',
		'add_or_remove_items'        => 'Add or remove tags',
		'choose_from_most_used'      => 'Choose from the most used tags',
		'not_found'                  => 'Not Found',
	);
	$rewrite = array(
		'slug'                       => 'star-tags',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'pdw_wpc_gh_star_tag', array( 'pdw_wpc_gh_star' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'pdw_wpc_register_gh_star_tax', 0 );