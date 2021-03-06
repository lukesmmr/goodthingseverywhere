<?php get_template_part('templates/page', 'header'); ?>
<div class="container">
  <?php if (!have_posts()) : ?>
    <div class="alert alert-warning">
      <?php _e('Sorry, no results were found.', 'roots'); ?>
    </div>
    <?php get_search_form(); ?>
  <?php endif; ?>

  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('templates/content', get_post_format()); ?>
  <?php endwhile; ?>

  <?php if ($wp_query->max_num_pages > 1) : ?>
    <nav class="post-nav">
      <ul class="pager">
        <li class="previous"><?php next_posts_link(__('<i class="glyphicon glyphicon-triangle-left"></i> Older articles', 'roots')); ?></li>
        <li class="next"><?php previous_posts_link(__('Newer articles <i class="glyphicon glyphicon-triangle-right"></i>', 'roots')); ?></li>
      </ul>
    </nav>
  <?php endif; ?>
</div>
