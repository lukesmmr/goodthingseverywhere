<?php // get current loc
$page_id = 4;
$page_data = get_page( $page_id ); 
echo '<span id="getloc" class="hidden" data-lat="' . $page_data->current_loc_lat . '" data-lng="' . $page_data->current_loc_lng . '"></span>';
?>

<div id="journal-map">
<div id="map-canvas"><span class="map-loader">Loading map...</span></div>
  <div id="dropdown-pos">
    <div class="btn-group" style="margin-top:-10px">
      <button id="current-loc-btn" type="button" class="btn btn-default">Me</button>
      <button id="user-loc-btn" type="button" class="btn btn-default">You</button>
    </div>
    <select class="marker-coords selectpicker show-tick" data-width="150px" data-live-search="true">
      <option selected="selected" data-post-loc="" data-post-loc-lat="<?php echo $page_data->current_loc_lat ?>" data-post-loc-lng="<?php echo $page_data->current_loc_lng; ?>" data-post-url="">Travel to...</option>
      <?php $wp_query= null;
      $counter = 0;
      $wp_query = new WP_Query(); $wp_query->query(array ('showposts' => '-1', 'orderby' => 'date', 'order' => 'ASC'));
      while ($wp_query->have_posts()) : $wp_query->the_post(); 
      $counter++;
      $post_loc = get_post_meta($post->ID, 'post_loc', true);
      $post_loc_lat = get_post_meta($post->ID, 'post_loc_lat', true);
      $post_loc_lng = get_post_meta($post->ID, 'post_loc_lng', true);
      $excerpt = substr(strip_tags(get_the_excerpt()),0,100) . "...";
      $thumb_id = get_post_thumbnail_id();
      $thumb_url = wp_get_attachment_image_src($thumb_id,'journal-home-thumb', true);
      ?>
      <option id="post-<?php the_ID(); ?>" data-post-loc="<?php echo $post_loc; ?>" data-post-loc-lat="<?php echo $post_loc_lat; ?>" data-post-loc-lng="<?php echo $post_loc_lng; ?>" data-post-url="<?php echo get_permalink(); ?>" data-post-excerpt="<?php echo $excerpt; ?>" data-post-thumb="<?php echo $thumb_url[0]; ?>" data-post-title="<?php the_title(); ?>" data-post-date="<?php echo get_the_date('j. M y'); ?>"><?php echo $counter . ". " . $post_loc; ?></option>
      <?php endwhile; ?>
    </select>      
  </div>
  <?php wp_reset_query(); ?>
  <!--  <span id="resize-icon" class="glyphicon glyphicon-resize-vertical"></span> -->      
  <div class="journal-map-btn-container">
    <div id="journal-map-caption"><i class="glyphicon glyphicon-info-sign"></i> <span class="map-msg"></span></div>
    <div id="arrow-pos">
      <button id="journal-map-toggle">Map</button>
      <div id="map-arrow" class="arrow-down"></div>
    </div>
  </div>
</div>

<header class="banner navbar navbar-default navbar-static-top" role="banner">
  
  <div class="container site-head">
    

   
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <nav class="collapse navbar-collapse navbar-desktop" role="navigation">
        <?php
          if (has_nav_menu('main_navi')) :
            wp_nav_menu(array('theme_location' => 'main_navi', 'menu_class' => 'nav navbar-nav'));
          endif;
        ?>
    </nav>

    <div id="logo" class="logo">
      <a href="<?php echo home_url(); ?>/">
        <?php bloginfo('name'); ?>
      </a>
      <div class="circle"></div>
    </div>    

  </div>
</header>
