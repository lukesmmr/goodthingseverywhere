<?php 
	$client_list = get_post_meta($post->ID, 'client_list', true);
	$client_title = get_post_meta($post->ID, 'client_title', true);
	$award_list = get_post_meta($post->ID, 'award_list', true);
	$award_title = get_post_meta($post->ID, 'award_title', true);
?>
<div class="about-content">

<?php while (have_posts()) : the_post(); ?>
  <?php the_content(); ?>
  <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
<?php endwhile; ?>

	<div id="clients">
		<h3><?php echo $client_title; ?></h3>
		<div class="client-list">
			<?php echo $client_list; ?>
		</div>
	</div>

	<div id="awards">
		<h3><?php echo $award_title; ?></h3>
		<div class="award-list">
			<?php echo $award_list; ?>
		</div>
	</div>	

</div>