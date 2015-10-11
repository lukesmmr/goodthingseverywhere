<?php echo '<span id="loc-settings" class="hidden" data-lat="' . of_get_option('current_loc_lat') . '" data-lng="' . of_get_option('current_loc_lng') . '" data-zoom-level="' . of_get_option('zoom_level_select') . '" data-polyline-color="' . of_get_option('polyline_color') . '"></span>'; ?>
<div id="journal-map">
<div id="map-canvas"><span class="map-loader"><img src="<?php bloginfo('template_url') ?>/assets/img/loading-bars.svg" alt="" width="64" height="64" /></span></div>
  <div id="dropdown-pos">
    <span class="distance-calc hidden-xs">
      <i class="map-icon glyphicon glyphicon-info-sign"></i>
    </span>
    <div class="me-you-btn-group btn-group">
      <button id="current-loc-btn" type="button" class="btn btn-default">Me</button>
      <button id="user-loc-btn" type="button" class="btn btn-default">You</button>
    </div>
    <select class="selectpicker show-tick marker-coords" data-width="180px" data-live-search="false">
      <option selected="selected" data-post-loc="current" data-post-loc-lat="<?php echo of_get_option('current_loc_lat') ?>" data-post-loc-lng="<?php echo of_get_option('current_loc_lng') ?>" data-post-url="&nbsp;" data-post-excerpt="&nbsp;" data-post-thumb="&nbsp;" data-post-title="&nbsp;" data-post-date="&nbsp;">I have been to...</option>
      <?php $wp_query= null;
      $counter = 0;
      $wp_query = new WP_Query(); $wp_query->query(array ('showposts' => '-1', 'orderby' => 'date', 'order' => 'ASC'));
      while ($wp_query->have_posts()) : $wp_query->the_post();

        $post_loc = get_post_meta($post->ID, 'post_loc', true);
        $post_loc_lat = get_post_meta($post->ID, 'post_loc_lat', true);
        $post_loc_lng = get_post_meta($post->ID, 'post_loc_lng', true);
        $add_marker = get_post_meta($post->ID, 'add_marker', true);

        $excerpt = substr(strip_tags(get_the_excerpt()),0,100) . "...";
        $thumb_id = get_post_thumbnail_id();
        $thumb_url = wp_get_attachment_image_src($thumb_id,'journal-home-thumb', true);

      if ($add_marker) :
      $counter++; ?>
      <option id="post-<?php the_ID(); ?>" data-marker="<?php echo $counter; ?>" data-post-loc="<?php echo $post_loc; ?>" data-post-loc-lat="<?php echo $post_loc_lat; ?>" data-post-loc-lng="<?php echo $post_loc_lng; ?>" data-post-url="<?php echo get_permalink(); ?>" data-post-excerpt="<?php echo $excerpt; ?>" data-post-thumb="<?php echo $thumb_url[0]; ?>" data-post-title="<?php the_title(); ?>" data-post-date="<?php echo get_the_date('j. M y'); ?>"><?php echo $counter . ". " . $post_loc; ?></option>
      <?php endif; endwhile; ?>
    </select>
  </div>
  <?php wp_reset_query(); ?>
  <div class="journal-location-details">
    <div class="container">
      <ul>
       <li class="display-loc"><i class="glyphicon glyphicon-map-marker"></i><em>I'm in</em> <?php echo of_get_option('current_loc'); ?></li>
        <?php if ( of_get_option('current_project') ) : ?>
        <li class="current-project"> for
          <?php if (of_get_option('current_url') !== '' ) : ?><a href="<?php echo of_get_option('current_url'); ?>" target="_blank" title="Project link"><?php endif; ?><?php echo of_get_option('current_project') ?><?php if( of_get_option('current_url') !== '' ) : ?></a><?php endif; ?>
          <?php //if( of_get_option('last_updated') !== '' ) : echo ' (' . of_get_option('last_updated') . ')'; endif; ?>
        </li>
        <?php endif; ?>
        <li class="next-loc">
        <i class="glyphicon glyphicon-plane"></i>
        <?php if (of_get_option('next_loc')): ?>
          <em>Going to</em> <?php echo of_get_option('next_loc'); ?>
        <?php else : ?>
          <em>Staying put</em>
        <?php endif; ?>
        </li>
      </ul>
    </div>
  </div>
  <div class="journal-map-btn-container">
    <div id="journal-map-notification"><i class="glyphicon glyphicon-info-sign"></i> <span class="map-msg"></span></div>
    <div id="arrow-pos" class="map-btn">
      <button id="journal-map-toggle">Explore Map</button>
      <div id="map-arrow" class="arrow-down"></div>
    </div>
  </div>
</div>


<header class="banner navbar navbar-default navbar-static-top" role="banner">

  <div class="container site-head">

    <div id="logo" class="logo">
      <a href="<?php echo home_url(); ?>/">
        <span class="main">Good</span>
        <span class="subline">Things Everywhere</span>
      </a>
    </div>


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


  </div>
</header>
