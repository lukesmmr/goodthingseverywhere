<?php
if (is_singular('project')) {
	get_template_part('templates/content-project', 'single');
} else {
	get_template_part('templates/content', 'single');
}
?>

