<?php 
	$home_intro = get_post_meta($post->ID, 'home_intro', true);
?>
<div class="home-additionals">
	<div id="keyvisual">
		<img src="<?php bloginfo('template_url') ?>/assets/img/good-keyvisual-gfx-940px.jpg" alt="" />
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

<div class="home-latest-social">
	<header class="home-header">
		<h2>Current project <i class="glyphicon glyphicon-map-marker"></i></h2>
	</header>

	<div class="home-current-project">
	
		<?php if ( of_get_option('current_project') ) : ?>
			<h3 class="project-title"><?php echo of_get_option('current_project') ?></h3>

			<?php if (of_get_option('current_project_thumb') !== '' ) : ?>
				<div class="current-thumb">
					<img src="<?php echo of_get_option('current_project_thumb'); ?>" alt="Current project logo" />
				</div>
			<?php endif; ?>

			<?php if (of_get_option('current_project_desc') !== '' ) : ?>
			<div class="current-project-desc">
				<?php echo of_get_option('current_project_desc'); ?>
			</div>
			<?php endif; ?>
			<div class="current-meta">			
				<ul>
					<li><i class="glyphicon glyphicon-map-marker"></i> <?php echo of_get_option('current_loc'); ?></li>
					<?php if (of_get_option('current_project_dur') !== '' ) : ?>
					<li><i class="glyphicon glyphicon-time"></i> <?php echo of_get_option('current_project_dur'); ?></li>
					<?php endif; ?>
					<?php if (of_get_option('current_url') !== '' ) : ?>
					<li><i class="glyphicon glyphicon-link"></i> <a href="<?php echo of_get_option('current_url'); ?>" target="_blank" title="Project link"><?php echo of_get_option('current_url'); ?></a></li>
					<?php endif; ?>
					<li><small>Updated: <?php echo of_get_option('last_updated'); ?></small></li>
				</ul>
			</div>
		<?php else : ?>

			<p>I'm currently in <?php echo of_get_option('current_loc'); ?> &ndash; making my way to my next project. See my latest work stops <a href="<?php bloginfo("site_url"); ?>/trade-projects" title="See trade projects">here</a>.</p>

		<?php endif; ?>	
	
	</div>

	<header class="home-header">
		<h2>Right now <i class="glyphicon glyphicon-picture"></i></h2>
		<span class="follow">Follow <a href="https://instagram.com/lukelucky" title="">@lukelucky</a></span>
	</header>

	<div class="right-now latest-instagram">


		<div class="insta-pic">
			<img src="<?php bloginfo('template_url') ?>/assets/img/lazy-loader.svg" class="svg-loader" alt="">
		</div>
	</div>
</div>

<!--  latest journal posts -->
<div class="home-journal">
	<header class="home-header">
		<h2>Latest travel journal <i class="glyphicon glyphicon-chevron-down"></i></h2>
		<span class="follow">Subscribe <a href="http://eepurl.com/PAIMP" target="_blank" title="Subscribe to Travel Journal">to Journal</a></span>
	</header>
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
	