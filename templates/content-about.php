<?php
	$about_intro = get_post_meta($post->ID, 'about_intro', true);
  $portrait_pic = get_post_meta($post->ID, 'portrait_pic', true);
  $portrait_caption = get_post_meta($post->ID, 'portrait_caption', true);
	$more_title = get_post_meta($post->ID, 'more_title', true);
  $more_list = get_post_meta($post->ID, 'more_list', true);
?>
<div class="container about-content">
  <div class="about-details">
      <figure class="thumbnail wp-caption">
        <img class="portraic-pic" src="<?php echo $portrait_pic ?>" alt="Lukas Portrait">
        <figcaption class="caption wp-caption-text"><?php echo $portrait_caption; ?></figcaption>
      </figure>
      <?php if ($more_list) : ?>
        <div class="more-content">
          <div class="more-list">
            <?php echo $more_list; ?>
          </div>
        </div>
      <?php endif; ?>
  </div>
  <?php if ($about_intro) : ?>
    <div class="about-intro">
      <?php echo $about_intro; ?>
    </div>
  <?php endif; ?>
  <?php while (have_posts()) : the_post(); ?>
	 <?php the_content(); ?>
	 <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
	<?php endwhile; ?>

  <div class="keyvisual">
    <img src="<?php bloginfo('template_url') ?>/assets/img/good-keyvisual-gfx-940px.jpg" alt="Key Visual by Georgia Hill">
  </div>

</div>