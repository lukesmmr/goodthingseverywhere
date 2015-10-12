<?php
$post_loc = get_post_meta($post->ID, 'post_loc', true);
$post_loc_lat = get_post_meta($post->ID, 'post_loc_lat', true);
$post_loc_lng = get_post_meta($post->ID, 'post_loc_lng', true);
$post_cat_glyph = get_post_meta($post->ID, 'post_cat_glyph', true);
$maps_link = 'http://maps.google.com/?q=' . $post_loc_lat . ',' . $post_loc_lng . '&z=15';
$article_content = $post->post_content;
?>
<div class="post-meta">
	<time class="published journal-date" datetime="<?php echo get_the_time('c'); ?>">
		<?php echo get_the_date('j. M y'); ?>
	</time>
	<?php if ( is_single() ) : if ($post_loc_lat || $post_loc_lng) : ?>
		<a target="_blank" href="<?php echo $maps_link; ?>" class="maps-link"><?php echo $post_loc; ?></a>
		<?php else : ?>
		<?php if ($post_loc ) : ?>
		<span class="maps-link"><?php echo $post_loc; ?></span>
	<?php endif; endif; endif; ?>
	<span class="comment-count">
  		<?php comments_number( '', '1 Comment', '% Comments' ); ?>
	</span>
	<div class="journal-read-time">
		<span class="eta">(<?php echo read_time($article_content); ?>)</span>
	</div>
	<div class="journal-category">
		<?php the_category(', '); ?>
		<?php if($post_cat_glyph) { ?>
			<span class="glyphicon glyphicon-<?php echo $post_cat_glyph; ?>"></span>
		<?php } else { ?>
			<span class="glyphicon glyphicon-globe"></span>
		<?php }?>
	</div>
</div>
