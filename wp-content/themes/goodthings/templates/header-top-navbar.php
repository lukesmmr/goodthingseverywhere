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
