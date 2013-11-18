<article <?php post_class(); ?>>
	<div class="journal-post">
		<div class="journal-thumbnail">
		<?php if ( has_post_thumbnail() ) {
				the_post_thumbnail('journal-main-thumb');
		} ?>
		</div>
		<div class="journal-content">
			<h3 class="journal-title"><a href="<?php the_permalink(); ?>" title="Read more"><?php the_title(); ?></a></h3>
			<header>
			    <?php get_template_part('templates/entry-meta'); ?>
			 </header>
			<?php the_excerpt(); ?>
		</div>
	</div>

</article>
