<div class="container">
	<div class="portfolio-content">

		<?php while (have_posts()) : the_post(); ?>
		  <?php the_content(); ?>
		  <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
		<?php endwhile; ?>

	</div>

<!-- 		<div class="projects-current">

			<h2 class="current-title">Current <i class="glyphicon glyphicon-chevron-down"></i></h2>

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
						<li><small>Last update: <?php echo of_get_option('last_updated'); ?></small></li>
					</ul>
				</div>
			<?php else : ?>

				<p>I'm currently in <?php echo of_get_option('current_loc'); ?> &ndash; making my way to my next project. See my latest work stops below. <small>(Updated: <?php echo of_get_option('last_updated'); ?>)</small></p>

			<?php endif; ?>
		</div>
		<div class="clearfix"></div> -->

	<div class="portfolio-filter">
		<?php
		$tax_type = 'project-type';
		$tax_cat = 'project-category';
		$tax_type_terms = get_terms($tax_type);
		$tax_cat_terms = get_terms($tax_cat);
		?>
		<span>Filter:</span>
		<ul class="filter-projects filter-type">
		 	<?php foreach ($tax_type_terms as $tax_term) { ?>
				<li class="<?php echo $tax_term->taxonomy ?>-<?php echo $tax_term->slug; ?>"><?php echo $tax_term->name; ?></li>
			<?php } ?>
		</ul>
		<ul class="filter-projects filter-cat">
			<?php foreach ($tax_cat_terms as $tax_term) { ?>
				<li class="<?php echo $tax_term->taxonomy; ?>-<?php echo $tax_term->slug; ?>"><?php echo $tax_term->name; ?></li>
			<?php } ?>
		</ul>
	</div>
	<div class="portfolio-grid">

		<div class="grid-sizer"></div>
		<div class="gutter-sizer"></div>
		<?php
		$args = array(
			'post_type' => 'project',
			'showposts' => -1
		);
		$project_query = new WP_Query(); $project_query->query($args);
		while ($project_query->have_posts()) : $project_query->the_post(); ?>
			<?php
			$thumb_size = get_post_meta( get_the_ID(), 'project_thumb_select', true);
			// set item classes according to selected thumbnail size
			$grid_classes = array(
				'project-item--' . $thumb_size,
				'project-item'
			);
			?>
	    <article <?php post_class($grid_classes); ?>>
	      <a href="<?php echo the_permalink(); ?>" title="Link to <?php the_title(); ?>">
	      	<div class="project-info">
	      		<header class="project-header">
		      		<h3 class="project-title"><?php the_title(); ?></h3>
	        		<?php get_template_part('templates/project-meta'); ?>
	      		</header>
	        	<div class="header-bg"></div>
	      	</div>
		      <?php if ( has_post_thumbnail() ) {
							the_post_thumbnail($thumb_size);
					} ?>
				</a>
	    </article>
	  <?php endwhile; ?>

	</div>

	</div>
</div>