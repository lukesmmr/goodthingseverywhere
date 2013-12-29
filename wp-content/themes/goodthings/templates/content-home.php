<?php 
	$giant_title = get_post_meta($post->ID, 'giant_title', true);
	$current_loc = get_post_meta($post->ID, 'current_loc', true);
	$current_loc_lat = get_post_meta($post->ID, 'current_loc_lat', true);
	$current_loc_lng = get_post_meta($post->ID, 'current_loc_lng', true);
	$next_loc = get_post_meta($post->ID, 'next_loc', true);
	$home_intro = get_post_meta($post->ID, 'home_intro', true);
?>
<div id="journal-map">
	 <div id="map-overlay"></div>			
	<div id="map-canvas"></div>
	<aside class="marker-coords">
		<?php $wp_query= null;
		$wp_query = new WP_Query(); $wp_query->query('showposts=-1');
		while ($wp_query->have_posts()) : $wp_query->the_post(); 
		$post_loc = get_post_meta($post->ID, 'post_loc', true);
		$post_loc_lat = get_post_meta($post->ID, 'post_loc_lat', true);
		$post_loc_lng = get_post_meta($post->ID, 'post_loc_lng', true);
		?>
	<span id="post-<?php the_ID(); ?>" data-post-loc="<?php echo $post_loc; ?>" data-post-loc-lat="<?php echo $post_loc_lat; ?>" data-post-loc-lng="<?php echo $post_loc_lng; ?>" data-post-url="<?php echo get_permalink(); ?>"></span>
		<?php endwhile; ?>
	</aside>
	<?php wp_reset_query(); ?>
</div>
<div class="home-additionals">
	<h1 class="giant-title"><?php echo $giant_title; ?></h1>
	<div class="current-loc">
		<span class="map-icon glyphicon glyphicon-map-marker"></span>
		<div id="getloc" class="display-loc" data-lat="<?php echo $current_loc_lat ?>" data-lng="<?php echo $current_loc_lng; ?>">Current location: <?php echo $current_loc; ?></div>
		<div class="next-loc">Next stop: <?php echo $next_loc ?></div>
	</div>
</div>
<div class="journal-map-btn-container">
	<button id="journal-map-toggle">Toggle journal map</button>
</div>


<div class="home-intro">
	<?php echo $home_intro; ?>
</div>
<div class="home-content">
	<?php while (have_posts()) : the_post(); ?>
	  <?php the_content(); ?>
	  <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
	<?php endwhile; ?>
</div>

<!--  latest journal posts -->
<div class="home-journal">
	<h2>Latest travel journal <i class="glyphicon glyphicon-chevron-down"></i></h2>
	<?php 
	$wp_query= null;
	$wp_query = new WP_Query(); $wp_query->query('showposts=2');
	while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
	<div class="home-journal-post">
		<div class="home-journal-thumbnail">
		<?php if ( has_post_thumbnail() ) {
				the_post_thumbnail('journal-home-thumb');
		} ?>
		</div>
		<div class="home-journal-content">
			<h3 class="home-journal-title"><a href="<?php the_permalink(); ?>" title="Read more"><?php the_title(); ?></a></h3>
			<?php get_template_part('templates/entry-meta'); ?>
			<?php the_excerpt(); ?>
		</div>
	</div>
	<?php endwhile; ?>
	<a href="<?php bloginfo('url') ?>/travel-journal" class="read-more-journal">See all <i class="glyphicon glyphicon-chevron-right"></i></a>
</div>
	