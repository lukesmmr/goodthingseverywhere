<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 *
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace("/\W/", "_", strtolower($themename) );

	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);

	// echo $themename;
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 */

function optionsframework_options() {

	$zoom_level = array(
		'3' => __('Overview (3)', 'options_check'),
		'4' => __('Regional (4)', 'options_check'),
		'5' => __('Close up (5)', 'options_check'),
	);

	$options[] = array(
		'name' => __('Location & Project Details', 'options_check'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Show current project', 'options_check'),
		'desc' => __('Enable current project', 'options_check'),
		'id' => 'show_current',
		'std' => '',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Current project', 'options_check'),
		'desc' => __('Name of current project', 'options_check'),
		'id' => 'current_project',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Current project description', 'options_check'),
		'desc' => __('40 - 60 words.', 'options_check'),
		'id' => 'current_project_desc',
		'std' => '',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Project URL', 'options_check'),
		'desc' => __('Not displayed if current project is empty.', 'options_check'),
		'id' => 'current_url',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Current project duration', 'options_check'),
		'desc' => __('e.g. 1 week, 1 month,etc', 'options_check'),
		'id' => 'current_project_dur',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Current location', 'options_check'),
		'desc' => __('City, Country', 'options_check'),
		'id' => 'current_loc',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Current location (latitude)', 'options_check'),
		'desc' => __('Latitude of current location.', 'options_check'),
		'id' => 'current_loc_lat',
		'std' => '',
		'class' => 'mini',
		'type' => 'text');

	$options[] = array(
		'name' => __('Current location (longitude)', 'options_check'),
		'desc' => __('Longitude of current location.', 'options_check'),
		'id' => 'current_loc_lng',
		'std' => '',
		'class' => 'mini',
		'type' => 'text');

	$options[] = array(
		'name' => __('Next location', 'options_check'),
		'desc' => __('City, Country', 'options_check'),
		'id' => 'next_loc',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Last updated', 'options_check'),
		'desc' => __('mm.yyyy', 'options_check'),
		'id' => 'last_updated',
		'std' => '',
		'class' => 'mini',
		'type' => 'text');

	$options[] = array(
		'name' => __('Good Things Map Settings', 'options_check'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Google Maps Zoom Level', 'options_check'),
		'desc' => __('Select zoom level.', 'options_check'),
		'id' => 'zoom_level_select',
		'std' => '3',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $zoom_level);

		$options[] = array(
		'name' => __('Set Polyline Color', 'options_check'),
		'desc' => __('Default color is #129adb', 'options_check'),
		'id' => 'polyline_color',
		'std' => '#129adb',
		'type' => 'color' );

	return $options;
}