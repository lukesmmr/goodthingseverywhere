<?php
/**
 * Custom functions
 */
function set_media() {
	update_option('image_default_link_type', 'none' );
	update_option('image_default_size', 'large' );
}
add_action('after_setup_theme', 'set_media');