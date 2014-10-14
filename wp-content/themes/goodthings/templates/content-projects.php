<div class="projects-content">

	<?php while (have_posts()) : the_post(); ?>
	  <?php the_content(); ?>
	  <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
	<?php endwhile; ?>

</div>

<?php if ( of_get_option('current_project') ) : ?>

	<div class="projects-current">
		
		<h2>Present <i class="glyphicon glyphicon-chevron-down"></i></h2>

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
				<li><small>Last update: <?php echo of_get_option('last_updated'); ?></small></li>
			</ul>
		</div>
	</div>
	<div class="clearfix"></div>
<?php endif; ?>

<hr />
<!--  latest journal posts -->
<div class="projects-journal">
	<h2>Past <i class="glyphicon glyphicon-chevron-down"></i></h2>
	<?php 
	$wp_query= null;
	$wp_query = new WP_Query(); $wp_query->query('showposts=-1&category_name=project');
	while ($wp_query->have_posts()) : $wp_query->the_post(); 
	$add_project = get_post_meta($post->ID, 'add_project', true);
	$project_name = get_post_meta($post->ID, 'post_project_name', true); 
	$project_location = get_post_meta($post->ID, 'post_loc', true); 
	$project_url = get_post_meta($post->ID, 'post_project_url', true); 
	$project_date = get_post_meta($post->ID, 'post_project_date', true); 
	$project_thumb = get_post_meta($post->ID, 'post_project_thumb', true); 
	$project_task = get_post_meta($post->ID, 'post_project_task', true);
	$project_trade = get_post_meta($post->ID, 'post_project_trade', true); 
	$project_description = get_post_meta($post->ID, 'post_project_description', true); 

	$post_loc_lat = get_post_meta($post->ID, 'post_loc_lat', true);
	$post_loc_lng = get_post_meta($post->ID, 'post_loc_lng', true);
	$maps_link = 'http://maps.google.com/?q=' . $post_loc_lat . ',' . $post_loc_lng . '&z=15';
	if ($add_project) :
	?>
	<div class="project-post">
		<header>		
			<h3 class="project-title"><?php echo $project_name ?></h3>
			<div class="project-meta">
				<ul>
					<?php if ( $project_location ) : ?><li><span><i class="glyphicon glyphicon-map-marker"></i></span> <a target="_blank" href="<?php echo $maps_link; ?>"><?php echo $project_location; ?></a></li><?php endif; ?>
					<?php if ( $project_date ) : ?><li><span><i class="glyphicon glyphicon-time"></i></span> <?php echo $project_date; ?></li><?php endif; ?>
					<?php if ( $project_url ) : ?><li class="project-url"><span><i class="glyphicon glyphicon-link"></i></span> <a href="http://<?php echo $project_url; ?>" target="_blank" title="<?php echo $project_name ?> Link"><?php echo $project_url ?></a></li><?php endif; ?>

				</ul>
			</div>
		</header>

   		<?php if ( $project_thumb ) : ?>
		<div class="project-thumbnail">
			<img src="<?php echo $project_thumb ?>" alt="<?php echo $project_name ?> Thumbnail" /> 			
		</div> 
		<?php endif;  ?>	

		<div class="project-content">
		

			<div class="project-description"><?php echo $project_description; ?></div>
			
			<div class="project-details">	
				<ul>
					<?php if ( $project_task ) : ?><li><span>I worked on</span> <?php echo $project_task; ?></li><?php endif; ?>
					<?php if ( $project_trade ) : ?><li><span>What I got in return</span> <?php echo $project_trade; ?></li><?php endif; ?>
				</ul>
			</div>

			<div class="project-more read-more">
				<a href="<?php the_permalink(); ?>" title="Link to Journal entry">Read the project article <span class="glyphicon glyphicon-flash"></span> </a>
			</div>
		</div>


	</div>

	<?php endif; endwhile; ?>
</div>