<?php $home_intro = get_post_meta($post->ID, 'home_intro', true); ?>
<div class="home-carousel">
	<?php $slider_count = wp_count_posts( 'carousel' )->publish; ?>

    <div id="goodthings-carousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <?php if ($slider_count > 1 ) : ?>
        <ol class="carousel-indicators">

           <?php
          $i = -1;
          $counter = 0;
          $bullet_query = new WP_Query();
          $bullet_query->query( 'post_type=carousel' );
          while ($bullet_query->have_posts()) : $bullet_query->the_post();
            $i++;
            $counter++;
          ?>
            <li data-target="#goodthings-carousel" data-slide-to="<?php echo $i; ?>" class="carousel-section <?php if($counter == 1) : echo 'active'; endif; ?>"></li>

            <?php endwhile; ?>
            <?php wp_reset_query(); ?>

        </ol>
      <?php endif; ?>
      <div class="carousel-inner" role="listbox">
      	<?php
        $slider_query = new WP_Query();
        $slider_query->query( 'post_type=carousel' );
        $counter = 0;
        while ($slider_query->have_posts()) : $slider_query->the_post();
          $counter++;
          $slide_label = get_post_meta( get_the_ID(), 'slide_label', true);
          $slide_url = get_post_meta( get_the_ID(), 'slide_url', true);
          $slide_url_type = get_post_meta( get_the_ID(), 'slide_url_type', true);
          $slide_excerpt = get_post_meta( get_the_ID(), 'slide_excerpt', true);
          $slide_url_text = get_post_meta( get_the_ID(), 'slide_url_text', true);
					$slide_id = get_post_thumbnail_id();
					$slide_src = wp_get_attachment_image_src($slide_id,'home-carousel', true);
        ?>
            <div class="item <?php if($counter == 1) : echo 'active'; endif; ?>" style="background: transparent url('<?php echo $slide_src[0]; ?>') center top no-repeat scroll;">

              <div class="carousel-caption">
              	<div class="carousel-label">
              		<?php echo $slide_label; ?>
              	</div>
                <div class="carousel-title">
                  <h1><?php the_title(); ?></h1>
                </div>
                <div class="carousel-excerpt">
                  <?php echo $slide_excerpt; ?>
	                <?php if ($slide_url): ?>
	                	<a class="read-more" href="<?php echo $slide_url; ?>"<?php if($slide_url_type) : ?> target="_blank"<?php endif; ?> title="Link to <?php the_title(); ?>">
	                		<?php echo $slide_url_text; ?>
	                	</a>
	                <?php endif ?>
                </div>
              </div>
              <div class="bottom-gradient"></div>
            </div>
          <?php endwhile; ?>
      </div>
    <?php if ($slider_count > 1 ) : ?>
      <!-- Controls -->
      <a class="left carousel-control" href="#goodthings-carousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-triangle-left" aria-hidden="true"></span>
        <span class="sr-only"><?php _e('Previous', 'roots'); ?></span>
      </a>
      <a class="right carousel-control" href="#goodthings-carousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>
        <span class="sr-only"><?php _e('Next', 'roots'); ?></span>
      </a>
   <?php endif; ?>

   </div>

</div>
<div class="container">

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
	<!-- 	<header class="home-header">
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

		</div> -->

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
		<a href="<?php bloginfo('url') ?>/travel-journal" class="read-all-journal">See all <i class="glyphicon glyphicon-chevron-right"></i></a>
	</div>

</div>