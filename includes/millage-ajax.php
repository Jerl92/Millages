<?php

/* Enqueue Script */
add_action( 'wp_enqueue_scripts', 'wp_millage_ajax_scripts' );

/**
 * Scripts
 */
function wp_millage_ajax_scripts() {
	/* Plugin DIR URL */
	$url = trailingslashit( plugin_dir_url( __FILE__ ) );
    //
    wp_enqueue_script( 'map', "https://maps.googleapis.com/maps/api/js?key=", array( 'jquery' ), '1.0.0', true );

    wp_register_script( 'wp-millage-ajax-get-scripts', $url . "js/ajax.millage.map.js", array( 'jquery' ), '1.0.1', true );
    wp_localize_script( 'wp-millage-ajax-get-scripts', 'get_map_ajax_url', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'wp-millage-ajax-get-scripts' );

    wp_register_script( 'wp-millage-ajax-save-new-post', $url . "js/ajax.millage.new.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-millage-ajax-save-new-post', 'new_post_millage_ajax_url', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'wp-millage-ajax-save-new-post' );
    
    wp_register_script( 'wp-millage-ajax-add-new-place', $url . "js/ajax.millage.add.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-millage-ajax-add-new-place', 'add_post_millage_ajax_url', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'wp-millage-ajax-add-new-place' );
    
    wp_register_script( 'wp-millage-ajax-save-new-place', $url . "js/ajax.millage.save.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-millage-ajax-save-new-place', 'save_post_millage_ajax_url', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'wp-millage-ajax-save-new-place' );
    
    wp_register_script( 'wp-millage-ajax-edit-place', $url . "js/ajax.millage.edit.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-millage-ajax-edit-place', 'edit_post_millage_ajax_url', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'wp-millage-ajax-edit-place' );
    
    wp_register_script( 'wp-millage-ajax-remove-place', $url . "js/ajax.millage.remove.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-millage-ajax-remove-place', 'remove_post_millage_ajax_url', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'wp-millage-ajax-remove-place' );
    	
}

/* AJAX action callback */
add_action( 'wp_ajax_get_map', 'ajax_get_map' );
add_action( 'wp_ajax_nopriv_get_map', 'ajax_get_map' );
function ajax_get_map($post) {
    $posts  = array();
    
    $postid = $_POST['object_id'];

    if ( $postid != null ) {                        
        $get_millage_places = get_post_meta( $postid, '_meta_places', true );
    } else if ( $postid['div'] != null ) {
        $get_millage_places = get_post_meta( $postid['div'], '_meta_places', true );
    }

    foreach ($get_millage_places as $get_millage_place) {
        if ( $get_millage_place['geoloc'] != null ) {	
            $html[] = $get_millage_place['geoloc'];
        }
    }

    return wp_send_json ( $html );
}

add_action( 'wp_ajax_new_post_millage', 'new_post_millage' );
add_action( 'wp_ajax_nopriv_new_post_millage', 'new_post_millage' );

function new_post_millage($posts) {
	$posts  = array();

	if ( is_user_logged_in() ) {

        $from_var = $_POST['object_id'];

        $place = array();

        if ( isset($from_var['name'] ) ){
            $post = array(
                'post_title'    => date("Y-m-d"),
                'post_status'   => 'publish',   // Could be: publish
                'post_type' 	=> 'millage', // Could be: `page` or your CPT
                'post_author'   => get_current_user_id()
            );
            $postid = wp_insert_post($post);
            $place['name'] = $from_var['name'];
        }

        if ( isset($from_var['geoloc'] ) ){
            $place['geoloc'] = $from_var['geoloc'];
        }

        if ( isset($from_var['km'] ) ){
            $place['km'] = $from_var['km'];
        }

        if ( isset($from_var['arrivaltime'] ) ){
            $place['arrivaltime'] = $from_var['arrivaltime'];
        }

        if ( isset($from_var['departuretime'] ) ){
            $place['departuretime'] = $from_var['departuretime'];
        }

        if ($postid) {
            add_post_meta( intval($postid), '_meta_places', [$place] );
        }

		return wp_send_json (intval($postid));

	}
}

/* AJAX action callback */
add_action( 'wp_ajax_update_post_millage', 'update_post_millage' );
add_action( 'wp_ajax_nopriv_update_post_millage', 'update_post_millage' );

function update_post_millage($posts) {
	$posts  = array();

	if ( is_user_logged_in() ) {

        $postid = $_POST['object_id'];
        $i = 0;

        $place = array();
        $place['name'] = null;
        $place['geoloc'] = null;
        $place['address'] = null;
        $place['km'] = null;
        $place['arrivaltime'] = null;
        $place['departuretime'] = null;

        $get_millage_places = get_post_meta( $postid, '_meta_places', true );
        array_push($get_millage_places, $place);
        update_post_meta( intval($postid), '_meta_places', $get_millage_places);
        
        $html = '<table style="width:100%" id="post_millage_table">';
        $html .= '<tr>';
        $html .= '<th>Place name</th>';
        $html .= '<th>Geolocation</th>';
        $html .= '<th>Address</th>';
        $html .= '<th>Car KM</th>';
        $html .= '<th>Arrival time</th>';
        $html .= '<th>Departure time</th>';
        $html .= '<th>Edit</th>';
        $html .= '<th>Remove</th>';
        $html .= '</tr>';

        foreach ($get_millage_places as $get_millage_place) {
            $html .= '<tr>';
                $html .= '<td>';
                    $html .= $get_millage_place['name'];
                $html .= '</td>';
                $html .= '<td>';
                    $html .= $get_millage_place['geoloc'];
                $html .= '</td>';
                $html .= '<td>';
                    $html .= $get_millage_place['address'];
                $html .= '</td>';
                $html .= '<td>';
                    $html .= $get_millage_place['km'];
                $html .= '</td>';
                $html .= '<td>';
                    $html .= $get_millage_place['arrivaltime'];
                $html .= '</td>';
                $html .= '<td>';
                    $html .= $get_millage_place['departuretime'];
                $html .= '</td>';
                $html .= '<td>';
                    $html .= '<button type="button" class="edit_place_in_post" data-id="' . $i . '">Edit</button>';
                $html .= '</td>';
                $html .= '<td>';
                    $html .= '<button type="button" class="remove_place_in_post" data-id="' . $i . '">Remove</button>';
                $html .= '</td>';
            $html .= '</tr>';
            $i++;
        }

        $html .= '</table>';

		return wp_send_json ($html);

	}
}

/* AJAX action callback */
add_action( 'wp_ajax_save_post_millage', 'save_post_millage' );
add_action( 'wp_ajax_nopriv_save_post_millage', 'save_post_millage' );

function save_post_millage($posts) {
	$posts  = array();

	if ( is_user_logged_in() ) {

        $from_var = $_POST['object_id'];
        $millage_places = get_post_meta( $from_var['postid'], '_meta_places', true );

        $place = array();

        if ( isset($from_var['name'] ) ){
            $place['name'] = $from_var['name'];
        }

        if ( isset($from_var['geoloc'] ) ){
            $place['geoloc'] = $from_var['geoloc'];
        }

        if ( isset($from_var['address'] ) ){
            $place['address'] = $from_var['address'];
        }

        if ( $from_var['km'] != 0 ) {
            $place['km'] = $from_var['km'];
        } else {
            $place['km'] = null;
        }

        if ( $from_var['arrivaltime'] != 0 ){
            $origArrivaltime = $from_var['arrivaltime'];
            $newArrivaltime = date("h:i", strtotime($origArrivaltime));
            $place['arrivaltime'] = $from_var['arrivaltime'];
        }

        if ( $from_var['departuretime'] != 0 ){
            $origDeparturetime = $from_var['departuretime'];
            $newDeparturetime = date("h:i", strtotime($origDeparturetime));
            $place['departuretime'] = $from_var['departuretime'];
        }

        if ($from_var['id'] >= 0) {
            $millage_places[intval($from_var['id'])] = $place;
        } else {
            array_push($millage_places, $place);
        }
        update_post_meta( intval($from_var['postid']), '_meta_places', $millage_places );

        $i = 0;
        
        $html = '<table style="width:100%" id="post_millage_table">';
        $html .= '<tr>';
        $html .= '<th>Place name</th>';
        $html .= '<th>Geolocation</th>';
        $html .= '<th>Address</th>';
        $html .= '<th>Car KM</th>';
        $html .= '<th>Arrival time</th>';
        $html .= '<th>Departure time</th>';
        $html .= '<th>Edit</th>';
        $html .= '<th>Remove</th>';
        $html .= '</tr>';

        foreach ($millage_places as $get_millage_place) {
            $html .= '<tr>';
                $html .= '<td>';
                    $html .= $get_millage_place['name'];
                $html .= '</td>';
                $html .= '<td>';
                    $html .= $get_millage_place['geoloc'];
                $html .= '</td>';
                $html .= '<td>';
                    $html .= '<a href="http://www.google.com/search?q=' . $get_millage_place['address'] . '" target="_blank">' . $get_millage_place['address'] . '</a>';
                $html .= '</td>';
                $html .= '<td>';
                    $html .= $get_millage_place['km'];
                $html .= '</td>';
                $html .= '<td>';
                    $html .= $get_millage_place['arrivaltime'];
                $html .= '</td>';
                $html .= '<td>';
                    $html .= $get_millage_place['departuretime'];
                $html .= '</td>';
                $html .= '<td>';
                    $html .= '<button type="button" class="edit_place_in_post" data-id="' . $i . '" >Edit</button>';
                $html .= '</td>';
                $html .= '<td>';
                    $html .= '<button type="button" class="remove_place_in_post" data-id="' . $i . '" >Remove</button>';
                $html .= '</td>';
            $html .= '</tr>';
            $i++;
        }

        $html .= '</table>';

		return wp_send_json ($html);

	}
}

/* AJAX action callback */
add_action( 'wp_ajax_edit_post_millage', 'edit_post_millage' );
add_action( 'wp_ajax_nopriv_edit_post_millage', 'edit_post_millage' );

function edit_post_millage($posts) {
	$posts  = array();

	if ( is_user_logged_in() ) {

        $attr = $_POST['object_id'];

        $i = 0;
        $get_millage_places = get_post_meta( $attr['postid'], '_meta_places', true );
        
        $html = '<table style="width:100%" id="post_millage_table">';
        $html .= '<tr>';
        $html .= '<th>Place name</th>';
        $html .= '<th>Geolocation</th>';
        $html .= '<th>Address</th>';
        $html .= '<th>Car KM</th>';
        $html .= '<th>Arrival time</th>';
        $html .= '<th>Departure time</th>';
        $html .= '<th>Edit</th>';
        $html .= '<th>Remove</th>';
        $html .= '</tr>';

        foreach ($get_millage_places as $get_millage_place) {
            if ($i == $attr['id']) {
                $html .= '<tr>';
                    $html .= '<td><input type="text" name="placename" id="placename" value="' . $get_millage_place['name'] . '" style="width: 250px;"></td>';
                    $html .= '<td><input type="text" name="geoloc" id="geoloc" value="' . $get_millage_place['geoloc'] . '" style="width: 250px;"></td>';
                    $html .= '<td><input type="text" name="address" id="address" value="' . $get_millage_place['address'] . '" style="width: 250px;"></td>';
                    $html .= '<td><input type="number" name="km" id="km" value="' . intval($get_millage_place['km']) . '" style="width: 250px;"></td>';
                    $html .= '<td><input type="time" name="arrivaltime" id="arrivaltime" value="' . $get_millage_place['arrivaltime'] . '" style="width: 150px;"></td>';
                    $html .= '<td><input type="time" name="departuretime" id="departuretime" value="' . $get_millage_place['departuretime'] . '" style="width: 150px;"></td>';
                    $html .= '<td><button type="button" class="edit_place_in_post" data-id="' . $i . '" disabled>Edit</button></td>';
                    $html .= '<td><button type="button" class="remove_place_in_post" data-id="' . $i . '" disabled>Remove</button></td>';
                $html .= '</tr>';
            } else {
                $html .= '<tr>';
                    $html .= '<td>';
                        $html .= $get_millage_place['name'];
                    $html .= '</td>';
                    $html .= '<td>';
                        $html .= $get_millage_place['geoloc'];
                    $html .= '</td>';
                    $html .= '<td>';
                        $html .= $get_millage_place['address'];
                    $html .= '</td>';
                    $html .= '<td>';
                        $html .= $get_millage_place['km'];
                    $html .= '</td>';
                    $html .= '<td>';
                        $html .= $get_millage_place['arrivaltime'];
                    $html .= '</td>';
                    $html .= '<td>';
                        $html .= $get_millage_place['departuretime'];
                    $html .= '</td>';
                    $html .= '<td>';
                        $html .= '<button type="button" class="edit_place_in_post" data-id="' . $i . '" disabled>Edit</button>';
                    $html .= '</td>';
                    $html .= '<td>';
                        $html .= '<button type="button" class="remove_place_in_post" data-id="' . $i . '" disabled>Remove</button>';
                    $html .= '</td>';
                $html .= '</tr>';
            }
            $i++;
        }        

        $html .= '</table>';

        $html .= '<button type="button" id="save_place_in_post" data-id="' . $attr['id'] . '">Save place</button>';

        $html .= '<button type="button" id="geolocation_place_in_post" data-id="' . $i . '">Get geolocation</button>';

		return wp_send_json ($html);

    }

}
    
    /* AJAX action callback */
add_action( 'wp_ajax_remove_post_millage', 'remove_post_millage' );
add_action( 'wp_ajax_nopriv_remove_post_millage', 'remove_post_millage' );

function remove_post_millage($posts) {
	$posts  = array();

	if ( is_user_logged_in() ) {

        $attr = $_POST['object_id'];

        $i = 0;
        $get_millage_places = get_post_meta( $attr['postid'], '_meta_places', true );

        array_splice($get_millage_places, $attr['id'], 1);

        update_post_meta( intval($attr['postid']), '_meta_places', $get_millage_places );
        
        $html = '<table style="width:100%" id="post_millage_table">';
        $html .= '<tr>';
        $html .= '<th>Place name</th>';
        $html .= '<th>Geolocation</th>';
        $html .= '<th>Address</th>';
        $html .= '<th>Car KM</th>';
        $html .= '<th>Arrival time</th>';
        $html .= '<th>Departure time</th>';
        $html .= '<th>Edit</th>';
        $html .= '<th>Remove</th>';
        $html .= '</tr>';

        foreach ($get_millage_places as $get_millage_place) {
            $html .= '<tr>';
                $html .= '<td>';
                    $html .= $get_millage_place['name'];
                $html .= '</td>';
                $html .= '<td>';
                    $html .= $get_millage_place['geoloc'];
                $html .= '</td>';
                $html .= '<td>';
                    $html .= '<a href="http://www.google.com/search?q=' . $get_millage_place['address'] . '" target="_blank">' . $get_millage_place['address'] . '</a>';
                $html .= '</td>';
                $html .= '<td>';
                    $html .= $get_millage_place['km'];
                $html .= '</td>';
                $html .= '<td>';
                    $html .= $get_millage_place['arrivaltime'];
                $html .= '</td>';
                $html .= '<td>';
                    $html .= $get_millage_place['departuretime'];
                $html .= '</td>';
                $html .= '<td>';
                    $html .= '<button type="button" class="edit_place_in_post" data-id="' . $i . '">Edit</button>';
                $html .= '</td>';
                $html .= '<td>';
                    $html .= '<button type="button" class="remove_place_in_post" data-id="' . $i . '">Remove</button>';
                $html .= '</td>';
            $html .= '</tr>';
            $i++;
        }        

        $html .= '</table>';

		return wp_send_json ($html);

	}
}

?>