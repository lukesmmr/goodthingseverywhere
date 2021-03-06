<?php
/**
 *
 * @category Good Things Everywhere
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

/**
 */
if ( file_exists(  __DIR__ . '/cmb2/init.php' ) ) {
  require_once  __DIR__ . '/cmb2/init.php';
} elseif ( file_exists(  __DIR__ . '/CMB2/init.php' ) ) {
  require_once  __DIR__ . '/CMB2/init.php';
}

/**
 * Conditionally displays a field when used as a callback in the 'show_on_cb' field parameter
 *
 * @param  CMB2_Field object $field Field object
 *
 * @return bool                     True if metabox should show
 */
function cmb2_hide_if_no_cats( $field ) {
  // Don't show this field if not in the cats category
  if ( ! has_tag( 'cats', $field->object_id ) ) {
    return false;
  }
  return true;
}

add_filter( 'cmb2_meta_boxes', 'goodthings_cmb' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */

function populate_post_select() {
  // Pull all the pages into an array
  $select_post = array();
  $args = array('post_type' => 'post', 'orderby'=> 'date', 'post_status' => 'publish', 'posts_per_page' => -1);
  $select_post_obj = get_posts($args);
  foreach ($select_post_obj as $post) {
    $select_post[$post->ID] = $post->post_title;
  }
  return $select_post;
}

function populate_thumb_select() {
  $thumb_sizes = array(
    'medium'    => 'Normal',
    'fullwidth' => 'Full width'
  );
  return $thumb_sizes;
}

function goodthings_cmb( array $meta_boxes) {

  $meta_boxes['home_teaser'] = array(
    'id'         => 'home_teaser',
    'title'      => 'Home Teaser',
    'object_types'      => array('page'),
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
      array(
        'name' => 'Link List',
        'desc' => '',
        'id'   => 'link_list',
        'type' => 'wysiwyg',
        'options' => array( 'textarea_rows' => 10 ),
      ),
    ),
  );

  $meta_boxes['slider_meta'] = array(
    'id'         => 'slider_meta',
    'title'      => 'Slider Meta',
    'object_types'      => array('carousel'),
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true,
    'fields'     => array(
      array(
        'name' => 'Slide Excerpt',
        'desc' => 'What\'s this about?',
        'id'   => 'slide_excerpt',
        'type' => 'textarea_small',
      ),
      array(
        'name' => 'Select linked post',
        'desc' => 'Choose related post for links and read time...',
        'id'   => 'slide_post_select',
        'type' => 'select',
        'show_option_none' => true,
        'default' => 'custom',
        'options' => 'populate_post_select'
      ),
      array(
        'name' => 'Slide Label',
        'desc' => 'Categorize it...',
        'id'   => 'slide_label',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Slide Link Text',
        'desc' => 'What should the button say...',
        'id'   => 'slide_url_text',
        'type' => 'text_medium',
      ),
      array(
        'name' => 'External Slide Link',
        'desc' => 'Post selection overrides this, enter with http://',
        'id'   => 'slide_url',
        'type' => 'text_url',
      ),
      array(
        'name' => 'Is the slide link external?',
        'desc' => 'Inserts target _blank',
        'id'   => 'slide_url_type',
        'type' => 'checkbox'
      ),
    ),
  );


$meta_boxes['project_meta'] = array(
    'id'         => 'project_meta',
    'title'      => 'Project Meta',
    'object_types'      => array('project'),
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true,
    'fields'     => array(
      array(
        'name' => 'Featured image size',
        'desc' => 'Small, medium or full width...',
        'id'   => 'project_thumb_select',
        'type' => 'select',
        'show_option_none' => false,
        'default' => 'project-medium',
        'options' => 'populate_thumb_select'
      ),
      array(
        'name' => 'Select linked post',
        'desc' => 'Choose related post for link and project output',
        'id'   => 'project_post_select',
        'type' => 'select',
        'show_option_none' => true,
        'default' => 'custom',
        'options' => 'populate_post_select'
      ),
      array(
        'name' => 'Year',
        'desc' => 'When did I make this?',
        'id'   => 'project_year',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Link to project',
        'desc' => 'Enter "offline" if the website is not active anymore',
        'id'   => 'project_url',
        'type' => 'text_medium'
      ),
      array(
        'name' => 'Agency',
        'desc' => 'Where I built this...',
        'id'   => 'project_agency',
        'type' => 'text_medium'
      ),
    ),
  );

  $meta_boxes['about_meta'] = array(
    'id'         => 'about_meta',
    'title'      => 'About Meta',
    'object_types'      => array('page'),
    'show_on' => array( 'key' => 'page-template', 'value' => 'page-about.php' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true,
    'fields'     => array(
      array(
        'name' => 'About Intro',
        'desc' => '',
        'id'   => 'about_intro',
        'type' => 'wysiwyg',
        'options' => array( 'textarea_rows' => 4 ),
      ),
      array(
        'name' => 'Portrait Pic',
        'desc' => 'Upload an image or enter an URL.',
        'id'   => 'portrait_pic',
        'type' => 'file',
      ),
      array(
        'name' => 'Portrait Caption',
        'desc' => 'Caption for my portrait',
        'id'   => 'portrait_caption',
        'type' => 'text'
      ),
      array(
        'name' => 'More About List',
        'desc' => '',
        'id'   => 'more_list',
        'type' => 'wysiwyg',
        'options' => array( 'textarea_rows' => 10 ),
      ),
    ),
  );

  $meta_boxes['projects_meta'] = array(
    'id'         => 'projects_meta',
    'title'      => 'Projects Meta',
    'object_types'      => array('page'),
    'show_on' => array( 'key' => 'page-template', 'value' => 'page-projects.php' ),
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

$meta_boxes['post_meta'] = array(
    'id'         => 'post_meta',
    'title'      => 'Journal Additionals',
    'object_types'      => array('post'),
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
        'type' => 'text_url'
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
