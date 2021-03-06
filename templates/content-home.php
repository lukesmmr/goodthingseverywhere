<?php
$home_intro = get_post_meta($post->ID, 'home_intro', true);
$link_list = get_post_meta($post->ID, 'link_list', true);
$cta_title = get_post_meta($post->ID, 'cta_title', true);
$cta_content = get_post_meta($post->ID, 'cta_content', true);
$slider_count = wp_count_posts( 'carousel' )->publish; ?>
<?php if ($slider_count > 0 ) : ?>
<div class="home-carousel">

    <div id="goodthings-carousel" class="carousel slide" data-ride="carousel">
      <?php if ($slider_count > 1 ) : ?>
        <ol class="carousel-indicators">

           <?php
          $i = -1; $counter = 0;
          $bullet_query = new WP_Query();
          $bullet_query->query( 'post_type=carousel' );
          while ($bullet_query->have_posts()) : $bullet_query->the_post();
          $i++; $counter++;
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
          $slide_excerpt = get_post_meta( get_the_ID(), 'slide_excerpt', true);
          $slide_url_text = get_post_meta( get_the_ID(), 'slide_url_text', true);
          $slide_url = get_post_meta( get_the_ID(), 'slide_url', true);
          $link_external = get_post_meta( get_the_ID(), 'slide_url_type', true);
          $linked_post_id = get_post_meta( get_the_ID(), 'slide_post_select', true);
					$slide_id = get_post_thumbnail_id();
					$slide_src = wp_get_attachment_image_src($slide_id,'home-carousel', true);
        ?>
            <div class="item <?php if($counter == 1) : echo 'active'; endif; ?>" style="background: transparent url('<?php echo $slide_src[0]; ?>') center top no-repeat scroll;">

              <div class="carousel-caption">
                <?php if ($slide_label): ?>
                  <div class="carousel-label">
                    <?php echo $slide_label; ?>
                  </div>
                <?php endif ?>
                <?php // get read time via post id if it is set
                if ($linked_post_id):
                  $linked_post = get_post($linked_post_id);
                  $post_content = $linked_post->post_content;
                  $post_date = get_the_date('j. M y', $linked_post_id);
                  ?>
                  <span class="carousel-read-time"><?php echo read_time($post_content); ?></span>
                <?php endif ?>
                <?php if ($link_external): ?>
                  <i class="link-external glyphicon glyphicon-link"></i>
                <?php endif ?>
                <div class="carousel-title">
                  <h1><?php the_title(); ?></h1>
                </div>
                <div class="carousel-excerpt">
                  <?php if ($linked_post_id): ?><span class="carousel-post-date"><?php echo $post_date; ?><i class="glyphicon glyphicon-triangle-right"></i></span><?php endif; ?><?php echo $slide_excerpt; ?>
                  <?php if ($slide_url): ?>
  	                	<a class="read-more" href="<?php echo $slide_url; ?>" <?php if($link_external) : ?>target="_blank"<?php endif;?> title="Link to <?php the_title(); ?>">
  	                		<?php echo $slide_url_text; ?>
  	                	</a>
                  <?php elseif($slide_url_text) : ?>
                    <a class="read-more" href="<?php echo get_post_permalink($linked_post_id);  ?>" title="Link to <?php the_title(); ?>">
                      <?php echo $slide_url_text; ?>
                    </a>
                  <?php endif; ?>
                </div>
              </div>
              <div class="bottom-gradient"></div>
            </div>
          <?php endwhile; ?>
      </div>
    <?php if ($slider_count > 1 ) : ?>
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
<?php else : ?>
  <div class="center container keyvisual">
    <img src="<?php bloginfo('template_url') ?>/assets/img/good-keyvisual-gfx-940px.jpg" alt="Key Visual by Georgia Hill">
  </div>
<?php endif; ?>
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

  <div class="home-cta">
    <div class="home-options">
      <?php if (of_get_option('show_current')): ?>

        <div class="cta cta-left col-md-4">
          <h2>Current project</h2>
          <div class="current-project">
            <?php
            $current_project_url = of_get_option('current_url');
            $url_trim = trim($current_project_url, '/');
            // get rid of http or www
            if ($current_project_url) :
              if (!preg_match('#^http(s)?://#', $url_trim)) {
                  $url_trim = 'http://' . $url_trim;
              }
              $urlParts = parse_url($url_trim);
              $current_project_url = preg_replace('/^www\./', '', $urlParts['host']);
            endif;
             ?>
            <?php if (of_get_option('current_project_desc') !== '' ) : ?>
            <p><?php echo of_get_option('current_project_desc'); ?></p>
            <?php endif; ?>
            <ul>
              <?php if (of_get_option('current_project') !== '' ) : ?>
              <li><span>For whom</span> <?php echo of_get_option('current_project'); ?></li>
              <?php endif; ?>
              <li><span>Where</span> <?php echo of_get_option('current_loc'); ?></li>
              <?php if (of_get_option('current_project_dur') !== '' ) : ?>
              <li><span>How long</span> <?php echo of_get_option('current_project_dur'); ?></li>
              <?php endif; ?>
              <?php if (of_get_option('current_url') !== '' ) : ?>
              <li><span>Their website</span> <a href="<?php echo of_get_option('current_url'); ?>" target="_blank" title="Project link"><?php echo $current_project_url ?></a></li>
              <?php endif; ?>
              <!-- <li><small>Last update: <?php echo of_get_option('last_updated'); ?></small></li> -->
            </ul>
          </div>
        </div>
      <?php endif ?>

      <div class="cta <?php if (of_get_option('show_current')) : ?>cta-middle col-md-4<?php else : ?>cta-left col-md-8<?php endif;  ?>">
        <h2>Latest Work</h2>
         <div class="latest-portfolio">
            <?php $latest_project = null;
            $latest_project = new WP_Query(); $latest_project->query( array('showposts' => 1 , 'post_type' => 'project' ) );
            while ($latest_project->have_posts()) : $latest_project->the_post(); ?>
              <h3><?php the_title(); ?></h3>
              <?php get_template_part('templates/project-meta'); ?>
              <?php $thumb_id = get_post_thumbnail_id();
                    $thumb_src = wp_get_attachment_image_src($thumb_id,'journal-main-thumb', true);  ?>
              <a href="<?php the_permalink(); ?>" title="Link to <?php the_title(); ?>" style="background: transparent url('<?php echo $thumb_src[0]; ?>') bottom center no-repeat scroll;">
              </a>
            <?php endwhile; ?>
         </div>
      </div>

      <div class="cta <?php if (of_get_option('show_current')) : ?>cta-right col-md-4<?php else : ?>cta-right col-md-4<?php endif;  ?>">
        <h2>What else?</h2>
        <div class="what-else-list">
          <?php echo $link_list; ?>
        </div>
      </div>

    </div>

  </div>

	<div class="home-latest-social">

		<header class="home-header">
			<h2>Instagram <i class="glyphicon glyphicon-picture"></i></h2>
			<span class="follow"><a href="https://instagram.com/lukelucky" title="Follow me on Instagram" target="_blank">@lukelucky</a></span>
		</header>

		<div class="right-now latest-instagram">
			<div class="insta-pic">
				<img src="<?php bloginfo('template_url') ?>/assets/img/lazy-loader.svg" class="svg-loader" alt="">
			</div>
		</div>

	</div>

	<div class="home-journal">
		<header class="home-header">
			<h2>Latest travel journal <i class="glyphicon glyphicon-triangle-bottom"></i></h2>
			<span class="follow"><a href="http://eepurl.com/PAIMP" target="_blank" title="Subscribe to Travel Journal">Subscribe</a></span>
		</header>
		<?php 
		$wp_query= null;
		$wp_query = new WP_Query(); $wp_query->query('showposts=3');
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
        <div class="home-excerpt">
          <?php the_excerpt(); ?>
        </div>
			</div>
		</div>
		<?php endwhile; ?>
		<a href="<?php bloginfo('url') ?>/travel-journal" class="read-all-journal">More Journal <i class="glyphicon glyphicon-triangle-right"></i></a>
	</div>

</div>