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
        'name' => 'Intro Highlight',
        'desc' => '',
        'id'   => 'home_intro',
        'type' => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5 ),
      ),
    ),
  );

  $meta_boxes[] = array(
    'id'         => 'home_teaser',
    'title'      => 'Home Teaser',
    'pages'      => array('page'),
    'show_on' => array( 'key' => 'page-template', 'value' => 'page-about.php' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true,
    'fields'     => array(
      array(
        'name' => 'Clients Title',
        'desc' => 'Title for client list',
        'id'   => 'client_title',
        'type' => 'text'
      ),
      array(
        'name' => 'Client List',
        'desc' => '',
        'id'   => 'client_list',
        'type' => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5 ),
      ),
      array(
        'name' => 'Awards Title',
        'desc' => 'Title for Award list',
        'id'   => 'award_title',
        'type' => 'text'
      ),
      array(
        'name' => 'Award List',
        'desc' => '',
        'id'   => 'award_list',
        'type' => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5 ),
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
        'name' => 'Article Location',
        'desc' => 'Maps marker title',
        'id'   => 'post_loc',
        'type' => 'text'
      ),
       array(
        'name' => 'Location Latitude',
        'desc' => 'Maps marker latitude',
        'id'   => 'post_loc_lat',
        'type' => 'text'
      ),
      array(
        'name' => 'Location Longitude',
        'desc' => 'Maps marker longitude',
        'id'   => 'post_loc_lng',
        'type' => 'text'
      ),
      array(
        'name' => 'Category Glyphicon',
        'desc' => 'Default if empty. Paste ending of glyphicon class (e.g. "camera")',
        'id'   => 'post_cat_glyph',
        'type' => 'text'
      ),
      array(
        'name' => 'Add to Project feed?',
        'desc' => 'Check to add marker to Project page',
        'id'   => 'add_project',
        'type' => 'checkbox',
      ),
      array(
        'name' => 'Project Name',
        'desc' => 'If article is a project description, name project',
        'id'   => 'post_project_name',
        'type' => 'text'
      ),
      array(
        'name' => 'Project Link',
        'desc' => 'Project website URL without http:// (e.g. openmindprojects.org)',
        'id'   => 'post_project_url',
        'type' => 'text_medium'
      ),
      array(
        'name' => 'Project Thumbnail',
        'desc' => 'Upload an image or enter an URL.',
        'id'   => 'post_project_thumb',
        'type' => 'file',
      ),
      array(
        'name' => 'Project Date',
        'desc' => 'Date the trade happened',
        'id'   => 'post_project_date',
        'type' => 'text_medium'
      ),
      array(
        'name' => 'Project tasks',
        'desc' => 'What did I do...',
        'id'   => 'post_project_task',
        'type' => 'text'
      ),
      array(
        'name' => 'Project trade',
        'desc' => 'What did I get...',
        'id'   => 'post_project_trade',
        'type' => 'text'
      ),
      array(
        'name' => 'Project description',
        'desc' => 'Short project description',
        'id'   => 'post_project_description',
        'type' => 'wysiwyg',
      ),
    ),
  );

  return $meta_boxes;
}
add_filter('cmb_meta_boxes', 'goodthings_cmb');