<?php
/**
* Map Marker Data JSON
* Write marker array from post data to JSON file and notify in WP Admin
* Author: @lukassommer 2015
*/

add_action('save_post', 'savePost');
add_action('admin_head-post.php', 'notifyMsg'); // called after save_post redirect

function savePost () {
  if (writeJSON()) {
    update_option('json_save_msg', 'Map markers updated');
    update_option('json_save_class', 'updated');
  } else {
    update_option('json_save_msg', 'Error. JSON write failed.');
    update_option('json_save_class', 'error');
  }
  update_option('json_save_status', 1); // turn on the message
}

function notifyMsg() {
  if ( get_option('json_save_status') ) {
    add_action('admin_notices' , create_function( '', "echo '<div class=\"" . get_option('json_save_class'). " is-dismissible\"><p>" . get_option('json_save_msg') . "</p></div>';" ) );
    update_option('json_save_status', 0); // turn off the message
  }
}

function writeJSON() {

    $mapArray = [];
    $counter = 0;

    define('MAP_JSON', get_template_directory() . '/assets/json/markers.json');

    $read_json = json_decode(file_get_contents(MAP_JSON), true);

    $query_args = array (
      'post_type'   => 'post',
      'showposts'   => '-1',
      'orderby'     => 'date',
      'order'       => 'ASC'
    );

    $articles = new WP_Query(); $articles->query($query_args);
    while ($articles->have_posts()) : $articles->the_post();

      $counter++;

      $location = get_post_meta(get_the_ID(), 'post_loc', true);
      $latitude = get_post_meta(get_the_ID(), 'post_loc_lat', true);
      $longitude = get_post_meta(get_the_ID(), 'post_loc_lng', true);
      $post_date = get_the_date('j. M y');
      $add_marker = get_post_meta(get_the_ID(), 'add_marker', true);
      if ($add_marker == '') :
        $add_marker = false;
      else :
        $add_marker = true;
      endif;

      $post_date = get_the_date('j. M y');
      $post_title = get_the_title(get_the_ID());
      $post_url = get_permalink();
      $post_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(),'journal-home-thumb', true);
      $post_excerpt = substr(strip_tags(get_the_excerpt()),0,100) . "...";

      $markerArray = array(
        'id'          => $counter,
        'add_marker'  => $add_marker,
        'info'        => array(
          'latitude'      => $latitude,
          'longitude'     => $longitude,
          'post_id'     => get_the_ID(),
          'post_location'      => $location,
          'post_date'     => $post_date,
          'post_title'    => $post_title,
          'post_url'      => $post_url,
          'post_thumb'       => $post_thumb[0],
          'post_excerpt'       => $post_excerpt
        )
      );
      array_push($mapArray, $markerArray);

    endwhile;

    if ( is_writable(MAP_JSON) ) {
      $write_json = file_put_contents(MAP_JSON, json_encode($mapArray, JSON_PRETTY_PRINT));
      if ($write_json) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }

}