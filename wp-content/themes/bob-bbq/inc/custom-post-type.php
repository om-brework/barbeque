<?php
function Additional_Item_post() {
  $labels = array(
    'name'               => _x( 'Sauces', 'post type general name' ),
    'singular_name'      => _x( 'Sauce', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'Sauce' ),
    'add_new_item'       => __( 'Add New Sauce' ),
    'edit_item'          => __( 'Edit Sauce' ),
    'new_item'           => __( 'New Sauce' ),
    'all_items'          => __( 'All Sauces' ),
    'view_item'          => __( 'View Sauce' ),
    'search_items'       => __( 'Search Sauces' ),
    'not_found'          => __( 'No Sauces found' ),
    'not_found_in_trash' => __( 'No Sauces found in the Trash' ),
    'parent_item_colon'  => '',
    'menu_name'          => 'Sauce'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Additional Items such as sauce, etc',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
    'has_archive'   => true,
    'register_meta_box_cb' => 'additional_item_metabox',
  );
  register_post_type( 'sauce', $args );
}
add_action( 'init', 'Additional_Item_post' );

function ingredient_post() {
  $labels = array(
    'name'               => _x( 'Ingredients', 'post type general name' ),
    'singular_name'      => _x( 'Ingredient', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'Ingredient' ),
    'add_new_item'       => __( 'Add New Ingredient' ),
    'edit_item'          => __( 'Edit Ingredient' ),
    'new_item'           => __( 'New Ingredient' ),
    'all_items'          => __( 'All Ingredients' ),
    'view_item'          => __( 'View Ingredient' ),
    'search_items'       => __( 'Search Ingredients' ),
    'not_found'          => __( 'No Ingredients found' ),
    'not_found_in_trash' => __( 'No Ingredients found in the Trash' ),
    'parent_item_colon'  => '',
    'menu_name'          => 'Ingredients'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'add igredients',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
    'has_archive'   => true
  );
  register_post_type( 'ingredient', $args );
}
add_action( 'init', 'ingredient_post' );
