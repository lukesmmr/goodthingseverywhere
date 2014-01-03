<?php get_template_part('templates/page', 'header'); ?>
<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
      <?php get_template_part('templates/entry-meta'); ?>
    </header>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <!-- <div class="entry-location">
      In <a target="_blank" href="http://maps.google.com/?q=<?php //echo $post_loc_lat; ?>,<?php //echo $post_loc_lng; ?>&z=12" class="maps-link"><?php echo $post_loc; ?></a>
    </div> -->
    <div class="comments">
    <?php comments_template('/templates/comments.php'); ?>
    </div>
    <nav class="post-nav">
        <ul class="pager">
          <li class="next"><?php previous_post_link('%link', 'Previous post &rarr;'); ?></li>
          <li class="previous"><?php next_post_link('%link', '&larr; Next post'); ?> </li>
        </ul>
      </nav>
  </article>
<?php endwhile; ?>
