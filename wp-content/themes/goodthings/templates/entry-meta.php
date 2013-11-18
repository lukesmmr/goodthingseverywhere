<div class="post-meta">
	<time class="published journal-date" datetime="<?php echo get_the_time('c'); ?>">
		<?php echo get_the_date('j. M y'); ?>
	</time>
	<div class="journal-category">
		<?php the_category(', '); ?>
		<span class="glyphicon glyphicon-globe"></span>
	</div>
</div>
