<div class="container">
  <?php get_template_part('templates/page', 'header'); ?>
  <?php while (have_posts()) : the_post(); ?>
    <article <?php post_class(); ?>>
      <header>
        <?php get_template_part('templates/entry-meta'); ?>
      </header>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
      <div class="sharing">
        <?php get_template_part('templates/recommend'); ?>
      </div>
      <div class="comments">
      <?php comments_template('/templates/comments.php'); ?>
      </div>
      <nav class="post-nav">
          <ul class="pager">
            <li class="previous"><?php next_post_link('%link', '<i class="glyphicon glyphicon-triangle-left"></i> Next post'); ?> </li>
            <li class="next"><?php previous_post_link('%link', 'Previous post <i class="glyphicon glyphicon-triangle-right"></i>'); ?></li>
          </ul>
        </nav>
    </article>
  <?php endwhile; ?>
</div>
