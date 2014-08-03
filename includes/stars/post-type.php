<?php
function pdw_wpc_register_star_post_type() {

	$args = array(
		'public' => true,
		'label'  => 'Stars',
		'show_in_json' => true,
	);

	register_post_type( 'pdw_wpc_gh_star', $args );

}
add_action( 'init', 'pdw_wpc_register_star_post_type' );
