<?php
	$award_title = get_post_meta($post->ID, 'award_title', true);
  $award_list = get_post_meta($post->ID, 'award_list', true);
 	$client_title = get_post_meta($post->ID, 'client_title', true);
  $client_list = get_post_meta($post->ID, 'client_list', true);
?>
<div class="container">
	<div class="portfolio-content">

		<?php while (have_posts()) : the_post(); ?>
		  <?php the_content(); ?>
		  <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
		<?php endwhile; ?>

	</div>

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

	<?php if ($award_title || $award_list): ?>
		<div id="awards">
			<h2><?php echo $award_title; ?></h2>
			<div class="award-list">
				<?php echo $award_list; ?>
			</div>
	<?php endif; ?>

	<?php if ($client_title || $client_list): ?>
			<h2><?php echo $client_title; ?></h2>
			<div class="client-list">
				<?php echo $client_list; ?>
			</div>
		</div>
	<?php endif ?>

	</div>
</div>