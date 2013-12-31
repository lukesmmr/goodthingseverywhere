

<header class="banner navbar navbar-default navbar-static-top" role="banner">
  

  <div class="container site-head">
    
   <?php //if ( !is_front_page() ) : ?>
      <!-- <div id="getloc" class="hidden-loc" data-lat="<?php //echo $current_loc_lat ?>" data-lng="<?php //echo $current_loc_lng; ?>"></div>
    <?php //endif; ?> -->
    <div id="journal-map">
       <div id="map-overlay"></div>     
      <div id="map-canvas"></div>
      <select class="marker-coords">
        <?php $wp_query= null;
        $wp_query = new WP_Query(); $wp_query->query(array ('showposts' => '-1', 'orderby' => 'date', 'order' => 'ASC'));
        while ($wp_query->have_posts()) : $wp_query->the_post(); 
        $post_loc = get_post_meta($post->ID, 'post_loc', true);
        $post_loc_lat = get_post_meta($post->ID, 'post_loc_lat', true);
        $post_loc_lng = get_post_meta($post->ID, 'post_loc_lng', true);
        ?>
        <option id="post-<?php the_ID(); ?>" data-post-loc="<?php echo $post_loc; ?>" data-post-loc-lat="<?php echo $post_loc_lat; ?>" data-post-loc-lng="<?php echo $post_loc_lng; ?>" data-post-url="<?php echo get_permalink(); ?>"><?php echo $post_loc; ?></option>
        <?php endwhile; ?>
      </select>
      <?php wp_reset_query(); ?>
      <div class="journal-map-btn-container">
        <button id="journal-map-toggle">Toggle journal map</button>
      </div>
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

    <div id="logo" class="logo">
      <a href="<?php echo home_url(); ?>/">
        <?php bloginfo('name'); ?>
      </a>
      <div class="circle"></div>
    </div>    

  </div>
</header>
