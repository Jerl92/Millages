# Millages Plugin for Wordpress</br>

<img src="https://i.ibb.co/LQX0Xjc/h798g87bjp.png" />

## How does it work
Responsive and interactive table to count the kilometer and worked time</br>

## Screenshot

<p float="left">
  <img width="49%" src="https://i.ibb.co/FsbYC5P/g434g54325431h2.png" />
  <img width="49%" src="https://i.ibb.co/Rh6zBn2/gt435hw.png" />
</p>

## Installation

1. Unzip file in your /wp-content/plugins directory.
2. Activate plugin in wp-admin.
3. Make a new page with the shortcode [show_millage]
4. Go to the page and start new millage.

## Google API KEY

In file /millages/includes/millage-ajax.php.</br>
You need to add the Google Map API KEY to the enqueue script « map ».</br>
wp_enqueue_script( 'map', "https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY_HERE", array( 'jquery' ), '1.0.0', true );</br>
- https://developers.google.com/maps/documentation/javascript/get-api-key/</br>

## Changelog

- 0.1 - Init commit.
