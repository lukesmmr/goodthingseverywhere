<?php 
	$home_intro = get_post_meta($post->ID, 'home_intro', true);
?>
<div class="home-additionals">
	<h1 class="giant-title"><?php echo $giant_title; ?></h1>
	<div class="current-loc">
		<div class="distance-calc">
			<span class="map-icon glyphicon glyphicon-map-marker"></span>
		</div>
		<div class="display-loc">Current location: <?php echo of_get_option('current_loc'); ?></div>
		<?php if ( of_get_option('current_project') ) : ?>
			<div class="current-project">Trading work with 

			<?php if (of_get_option('project_url') !== '' ) : ?>
				<a href="<?php echo of_get_option('project_url'); ?>" target="_blank" title="Project link">
			<?php endif; ?>

			<?php echo of_get_option('current_project') ?>

			<?php if( of_get_option('project_url') !== '' ) : ?>
				</a>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<div class="next-loc">
		Next stop: <?php echo of_get_option('next_loc'); ?>
		<div class="last-uodate">Location updated on <?php echo of_get_option('last_updated'); ?></div>
		</div>
	</div>
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
	