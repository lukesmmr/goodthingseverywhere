<?php
/**
 * Custom meta boxes with the CMB plugin
 *
 * @link https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 * @link https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress/wiki/Basic-Usage
 * @link https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress/wiki/Field-Types
 * @link https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress/wiki/Display-Options
 */

function goodthings_cmb($meta_boxes) {
  /**
   * Page Options meta box
   */
  $meta_boxes[] = array(
    'id'         => 'home_teaser',
    'title'      => 'Home Teaser',
    'pages'      => array('page'),
    'show_on' => array( 'key' => 'page-template', 'value' => 'page-home.php' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true,
    'fields'     => array(
      array(
        'name' => 'Big Intro',
        'desc' => '',
        'id'   => 'home_intro',
        'type' => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5 ),
      ),
      array(
        'name' => 'Giant title',
        'desc' => '',
        'id'   => 'giant_title',
        'type' => 'text'
      ),
      array(
        'name' => 'Current location',
        'desc' => '',
        'id'   => 'current_loc',
        'type' => 'text'
      ),
       array(
        'name' => 'Current location (latitude)',
        'desc' => 'latitude of current location',
        'id'   => 'current_loc_lat',
        'type' => 'text'
      ),
      array(
        'name' => 'Current location (longitude)',
        'desc' => 'longitude of current location',
        'id'   => 'current_loc_lng',
        'type' => 'text'
      ),
      array(
        'name' => 'Next location',
        'desc' => '',
        'id'   => 'next_loc',
        'type' => 'text'
      ),
    ),
  );

$meta_boxes[] = array(
    'id'         => 'post_meta',
    'title'      => 'Journal Additionals',
    'pages'      => array('post'),
    'show_on'    => array('post'),
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true,
    'fields'     => array(
      array(
        'name' => 'Add to Map?',
        'desc' => 'Check to add marker to Journal Map',
        'id'   => 'add_marker',
        'type' => 'checkbox',
      ),
      array(
        'name' => 'Article location',
        'desc' => 'Maps marker title',
        'id'   => 'post_loc',
        'type' => 'text'
      ),
       array(
        'name' => 'Location latitude',
        'desc' => 'Maps marker latitude',
        'id'   => 'post_loc_lat',
        'type' => 'text'
      ),
      array(
        'name' => 'Location longitude',
        'desc' => 'Maps marker longitude',
        'id'   => 'post_loc_lng',
        'type' => 'text'
      ),
      array(
        'name' => 'Category Glyphicon',
        'desc' => 'Paste ending of glyphicon class',
        'id'   => 'post_cat_glyph',
        'type' => 'text'
      ),
    ),
  );

  return $meta_boxes;
}
add_filter('cmb_meta_boxes', 'goodthings_cmb');