<?php
$project_year = get_post_meta($post->ID, 'project_year', true);
?>
<div class="project-meta">
  <div class="project-category">
    <?php $category_list = wp_get_post_terms($post->ID, 'project-category' );
      foreach($category_list as $category_single) {
        echo '<span>' . $category_single->name . '</span>';
    } ?>
  </div>
  <div class="project-type">
    <?php $type_list = wp_get_post_terms($post->ID, 'project-type' );
      foreach($type_list as $type_single) {
        echo '<span>' . $type_single->name . '</span>';
    } ?>
    <?php if (is_single()): ?>Project<?php endif; ?>
  </div>
  <?php if ( !is_single() ): ?>
    <div class="published project-date">
        <?php echo $project_year ?>
    </div>
  <?php endif ?>
</div>
