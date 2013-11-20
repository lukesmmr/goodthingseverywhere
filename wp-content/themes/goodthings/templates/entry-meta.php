<div class="post-meta">
	<time class="published journal-date" datetime="<?php echo get_the_time('c'); ?>">
		<?php echo get_the_date('j. M y'); ?>
	</time>
	<span class="comment-count">
  		<?php comments_number( '', '1 Comment', '(% comments' ); ?>
	</span>
	<div class="journal-category">
		<?php the_category(', '); ?>
		<span class="glyphicon glyphicon-globe"></span>
	</div>
</div>
