<?php 
$post_loc = get_post_meta($post->ID, 'post_loc', true);
$post_loc_lat = get_post_meta($post->ID, 'post_loc_lat', true);
$post_loc_lng = get_post_meta($post->ID, 'post_loc_lng', true);
?>
<div class="post-meta">
	<time class="published journal-date" datetime="<?php echo get_the_time('c'); ?>">
		<?php echo get_the_date('j. M y'); ?>, 
<!-- 		<a target="_blank" href="http://maps.google.com/?q=<?php echo $post_loc_lat; ?>,<?php echo $post_loc_lng; ?>&z=12" class="maps-link"><?php echo $post_loc; ?></a>
 --></time>
	<span class="comment-count">
  		<?php comments_number( '', '1 Comment', '% Comments' ); ?>
	</span>
	<div class="journal-category">
		<?php the_category(', '); ?>
		<span class="glyphicon glyphicon-globe"></span>
	</div>
</div>
