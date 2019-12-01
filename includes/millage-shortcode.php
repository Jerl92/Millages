<?php

function show_millage_posts() {

    if ($_GET['post'] == "new") { 

        if ( is_user_logged_in() ) {
        ?>
        
            <div id="content" style="text-align: center;">
                <form action="" method="post">

                    <label for="placename">Place name</label>
                    <input type="text" name="placename" id="placename" required>

                    <label for="geoloc">Geolocation</label>
                    <input type="text" name="geoloc" id="geoloc">

                    <label for="km">How many Km</label>
                    <input type="number" name="km" id="km" required>

                    <label for="arrivaltime">Arrival time</label>
                    <input type="time" name="arrivaltime" id="arrivaltime">

                    <label for="departuretime">Departure time</label>
                    <input type="time" name="departuretime" id="departuretime">

                    <div style="width: 100%;">
                        <input type="button" style="margin-top: 2em; margin-bottom: 1em;" id="geolocation_place_in_post" value="Get geolocation"></input>
                        <input type="button" style="margin-left: auto;margin-right: auto;" name="new_millage_btn" id="new_millage_btn" value="Start new millage"></input>
                    </div>

                </form>
            </div>

        <?php 
    }

    } elseif ($_GET['post'] == "edit") {

        $i = 0;
        $get_millage_places = get_post_meta( intval($_GET['postid']), '_meta_places', true );
        
        if ( is_user_logged_in() ) {

            echo '<button type="button" id="add_place_in_post">Add new place</button>'; 

            ?> <div style="height:auto;overflow:auto;">
                <table style="width:100%" id="post_millage_table">
                        
                    <tr>
                    <th>Place name</th>
                    <th>Geolocation</th>
                    <th>Address</th>
                    <th>Car KM</th> 
                    <th>Arrival time</th>
                    <th>Departure time</th>
                    <th>Edit</th>
                    <th>Remove</th>
                    </tr> <?php

                    foreach ($get_millage_places as $get_millage_place) {
                        ?><tr>
                        <td> <?php echo $get_millage_place['name']; ?></td>
                        <td> <?php echo $get_millage_place['geoloc']; ?></td>
                        <td><a href="http://www.google.com/search?q=<?php echo $get_millage_place['address']; ?>" target="_blank"><?php echo $get_millage_place['address']; ?></a></td>
                        <td> <?php echo $get_millage_place['km']; ?></td>
                        <td> <?php echo $get_millage_place['arrivaltime']; ?></td>
                        <td> <?php echo $get_millage_place['departuretime']; ?></td>
                        <td><button type="button" class="edit_place_in_post" data-id="<?php echo $i ?>" >Edit</button></td>
                        <td><button type="button" class="remove_place_in_post" data-id="<?php echo $i ?>" >Remove</button></td>
                        </tr> <?php
                        $i++;
                    }

            ?> </table>
            </div> <?php

        }

    } elseif ($_GET['post'] == "view") {

        $i = 0;
        $get_millage_places = get_post_meta( intval($_GET['postid']), '_meta_places', true );
        $post   = get_post( intval($_GET['postid']) );
        
        ?> <div style="height:auto; overflow:auto;">
        <div id="map" style="clear: both; width: 100%; height: 500px;"></div>
        <div id="right-panel">
            <p>Total Distance: <span id="total"></span></p>
        </div> <?php

        ?> <table style="width:100%" id="post_millage_table">
        <tr>
            <th><?php echo date("F j, Y", strtotime($post->post_date)); ?></th>
        </tr>
        <tr>
          <th>Place name</th>
          <th>Geolocation</th>
          <th>Address</th>
          <th>Car KM</th> 
          <th>Arrival time</th>
          <th>Departure time</th>
          <th>Traveled time</th>
          <th>Traveled time by Google</th>
          <th>Worked time</th>
          <th>KM</th>
          <th>KM Par Google</th>
        </tr> <?php

        foreach ($get_millage_places as $get_millage_place) {
            $l = $i-1;

            $departuretimeLass  = new DateTime( $get_millage_places[$l]['departuretime'] );
            $arrivaltime = new DateTime( $get_millage_place['arrivaltime'] );
            $departuretime = new DateTime( $get_millage_place['departuretime'] );

            $TraveledCal = $departuretimeLass->diff( $arrivaltime );
            $WorkedCal = $arrivaltime->diff( $departuretime );

            ?><tr>
            <td> <?php echo $get_millage_place['name']; ?></td>
            <td class="latlng"> <?php echo $get_millage_place['geoloc']; ?></td>
            <td id="google-address-<?php echo $i ?>" class="google-address"><?php echo $get_millage_place['address']; ?></td>
            <td> <?php echo $get_millage_place['km']; ?></td>
            <td> <?php echo $get_millage_place['arrivaltime']; ?></td>
            <td> <?php echo $get_millage_place['departuretime']; ?></td>

            <?php if ($get_millage_places[$l]['departuretime'] != 0) { ?>
                <td id="traveled-time-<?php echo $i ?>" class="traveled-time"> <?php echo $TraveledCal->format( '%H:%I' ); ?></td>
            <?php } else { ?>
                <td>00:00</td>
            <?php } ?>

            <td class="time-by-google"></td>
            
            <?php if ($get_millage_place['arrivaltime'] != 0 && $get_millage_place['departuretime'] != 0) { ?>
                <td id="worked-time-<?php echo $i ?>" class="worked-time"> <?php echo $WorkedCal->format( '%H:%I' ); ?></td>
            <?php } else { ?>
                <td>00:00</td>
            <?php } ?>

            <?php if ($get_millage_places[$l]['km'] != 0) { ?>
                <td> <?php echo $get_millage_place['km'] - $get_millage_places[$l]['km']; ?></td>
            <?php } else { ?>
                <td>0</td>
            <?php } ?>

            <td class="km-by-google"></td>

            </tr> <?php
            $i++;
        }

        $count_millage_places = count($get_millage_places) - 1;

        ?> </table></div> <?php

        ?> <table style="width: auto;float: left;">
        <tr>
            <th>Total Traveled time</th>
            <th>Total Traveled time by Google</th>
            <th>Total Worked time</th>
            <th>Total time</th>
        </tr>
        <tr>
            <td id="total-traveled"></td>
            <td id="time-total-by-google"></td>
            <td id="total-worked"></td>
            <td id="total-time"></td>
        </tr>
        </table>
        <table style="width: auto;float: right;">
        <tr>
            <th>Total KM</th>
            <th>Total KM par Google</th>
        </tr>
        <tr>
            <td> <?php echo $get_millage_places[$count_millage_places]['km'] - $get_millage_places[0]['km']; ?></td>
            <td id="total-by-google"></td>
        </tr>
        </table> <?php

    } else {
        if ( is_user_logged_in() ) {
            echo '<button type="button"><a href="?post=new">Start new milage</a></button>';
        } else {
            echo '<button type="button"><a href="https://jerl92.tk/me/wp-login.php?redirect_to=https%3A%2F%2Fjerl92.tk%2Fme%2Fmillages">Login</a></button>';
        }

        if ( is_user_logged_in() ) {

            $args = array(
                'post_type' => 'millage',
                'posts_per_page' => -1,
                'post_author'   => get_current_user_id()
            );
            
            $my_query = new WP_Query( $args );
            
            if ( $my_query->have_posts() ) {
            
                $html[] = '<div style="height:auto;overflow:auto;"> ';
                $html[] .= '<table style="width:100%">';
                $html[] .= '<tr>';
                $html[] .= '<th>Post ID</th>';
                $html[] .= '<th>Date</th>';
                $html[] .= '<th>Edit</th>';
                $html[] .= '</tr>';
                    while ( $my_query->have_posts() ) {                
                        $my_query->the_post();
                        $html[] .= '<tr>';
                        $html[] .= '<td>' . get_the_ID() . '</td>';
                        $html[] .= '<td>' . get_the_title() . '</td>';
                        $html[] .= '<td><button type="button"><a href="?post=edit&postid=' . get_the_ID() . '">Edit</a></button><button type="button"><a href="?post=view&postid=' . get_the_ID() . '">View</a></button></td>';
                        $html[] .= '</tr>';
                    }
                $html[] .= '</table>';
                $html[] .= '</div>';

            }
           
            // Reset the `$post` data to the current post in main query.
            wp_reset_postdata();

            $arr = implode("", $html);

            return $arr;
            
        }
    }
}

add_shortcode('show_millage', 'show_millage_posts');

?>