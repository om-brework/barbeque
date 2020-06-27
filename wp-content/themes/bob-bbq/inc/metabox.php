<?php
function additional_item_metabox() {
	add_meta_box(
		'additional_price',
		'Price',
		'additional_price',
		'sauce',
		'normal',
		'default'
	);
}

function additional_price() {
	global $post;
	// Get the location data if it's already been entered
	$price = get_post_meta( $post->ID, 'additional_price', true );
	$custom_class = get_post_meta($post->ID, 'custom_class',true);
	$sauce_name = get_post_meta($post->ID, 'sauce_name',true);
	// Output the field
	echo 'Price : <input type="text" name="price" value="' . esc_textarea( $price )  . '" class="widefat">';
	echo 'Name : <input type="text" name="sauce_name" value="' . esc_textarea( $sauce_name )  . '" class="widefat">';
	echo 'Custom Class : <input type="text" name="custom_class" value="' . esc_textarea( $custom_class )  . '" class="widefat"><i>if special sauce put "special-sauce"</i>';
}

function aditional_item_save_price_meta( $post_id, $post ) {
	update_post_meta($post_id,'additional_price',$_POST['price']);
	update_post_meta($post_id,'custom_class',$_POST['custom_class']);
	update_post_meta($post_id,'sauce_name',$_POST['sauce_name']);
}
add_action( 'save_post', 'aditional_item_save_price_meta', 1, 2 );

// add Ingredient Product metabox
function add_ingredient_meta_boxes( $post_type, $post ) {
    add_meta_box(
        'ingredient',
        'ingredient',
        'ingredient_product_metabox',
        'product',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'add_ingredient_meta_boxes', 10, 2 );

// render metabox
function ingredient_product_metabox( $post ) {
	wp_nonce_field( 'my_metas_nonce', 'my_metas_nonce' );
	$args = array(
		'numberposts'		=> -1, // -1 is for all
		'post_type'		=> 'ingredient', // or 'post', 'page'
		'orderby' 		=> 'title', // or 'date', 'rand'
		'order' 		=> 'ASC', // or 'DESC'
	);
	// Get the posts
	$myposts = get_posts($args);

	// If there are posts
	if($myposts):
		// Loop the posts
		echo "<table><tr><td colspan=3>Please fill the ingredient weight, <b>leave empty if the ingredient is not used</b></td></tr>";
		foreach ($myposts as $mypost):
			$id = $mypost->ID;
			$title = get_the_title($mypost->ID);
			$weight = get_post_meta($post->ID, $mypost->ID,true);
			$checked = '';
			if($weight>0){
				$checked = 'checked';
			}
			?>
			<tr>
				<td><?php echo $title ?></td>
				<td><b><?php echo " Weight :"; ?></b></td>
				<td>
					<input name="weight_<?php echo $id ?>" value="<?php echo $weight ?>" style="width:50px" />
				</td>
				<td>Grams</td>
			</tr>
	<?php endforeach;
		echo "</table>";
		wp_reset_postdata();
	endif;

    ?>
    <?php
}

// save metabox data
function save_post_features_meta( $post_id ){
    if ( ! isset( $_POST['my_metas_nonce'] ) || ! wp_verify_nonce( $_POST['my_metas_nonce'], 'my_metas_nonce' ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
        return;
	}
	$args = array(
		'numberposts'		=> -1, // -1 is for all
		'post_type'		=> 'ingredient', // or 'post', 'page'
		'orderby' 		=> 'title', // or 'date', 'rand'
		'order' 		=> 'ASC', // or 'DESC'
	);
	// Get the posts
	$myposts = get_posts($args);

	// If there are posts
	if($myposts):
		// Loop the posts
		foreach ($myposts as $mypost):
			$idIngredient = $mypost->ID;
			if(!empty($_POST['weight_'.$idIngredient])){
				update_post_meta( $post_id, $idIngredient, $_POST['weight_'.$idIngredient],true );
			}else{
				delete_post_meta( $post_id, $idIngredient, $_POST['weight_'.$idIngredient] );
			}
		endforeach;
	endif;
}
add_action( 'save_post', 'save_post_features_meta');
