<?php /* Template Name: CustomPageT1 */ ?>
 
<?php get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>

    <div id="loop-container" class="loop-container">
        <div class="page type-page status-publish hentry entry">
	        <article>
				<div class="post-container">
                <div class="post-header">
                    <h1 class="post-title"><?php the_title(); ?></h1>
                </div>
                <div class="post-content"> <?php

                    $i = 0;
                    $get_millage_places = get_post_meta( get_the_id(), '_meta_places', true );

                    ?> <div id="post_id"><?php echo get_the_id(); ?></div><div id="map" style="clear: both; width: 100%; height: 500px;"></div> <?php

                    ?> <table style="width:100%" id="post_millage_table">
                        <tr>
                            <th><?php echo date("F j, Y", strtotime(get_the_date())); ?></th>
                        </tr>
                        <tr>
                        <th>Place name</th>
                        <th>Geolocation</th>
                        <th>Car KM</th> 
                        <th>Arrival time</th>
                        <th>Departure time</th>
                        <th>Traveled time</th>
                        <th>Worked time</th>
                        <th>KM</th>
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
                            <td> <?php echo $get_millage_place['geoloc']; ?></td>
                            <td> <?php echo $get_millage_place['km']; ?></td>
                            <td> <?php echo $get_millage_place['arrivaltime']; ?></td>
                            <td> <?php echo $get_millage_place['departuretime']; ?></td>

                            <?php if ($get_millage_places[$l]['departuretime'] != 0) { ?>
                                <td> <?php echo $TraveledCal->format( '%H:%I' ); ?></td>
                            <?php } else { ?>
                                <td>00:00</td>
                            <?php } ?>
                            
                            <?php if ($get_millage_place['arrivaltime'] != 0 && $get_millage_place['departuretime'] != 0) { ?>
                                <td> <?php echo $WorkedCal->format( '%H:%I' ); ?></td>
                            <?php } else { ?>
                                <td>00:00</td>
                            <?php } ?>

                            <?php if ($get_millage_places[$l]['km'] != 0) { ?>
                                <td> <?php echo $get_millage_place['km'] - $get_millage_places[$l]['km']; ?></td>
                            <?php } else { ?>
                                <td></td>
                            <?php } ?>

                            </tr> <?php
                            $i++;
                        }

                        $count_millage_places = count($get_millage_places) - 1;

                        ?> </table> <?php

                        ?> <table style="width:125px">
                        <tr>
                            <th>Total KM</th>
                        </tr>
                        <tr>
                            <td> <?php echo $get_millage_places[$count_millage_places]['km'] - $get_millage_places[0]['km']; ?></td>
                        </tr>
                    </table> 
                </div>
            </article>
        </div>
    </div>

	<?php endwhile; // end of the loop. ?>

<?php get_footer();