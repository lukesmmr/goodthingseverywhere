<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="<?php bloginfo('template_url') ?>/assets/img/favicon.png" />
  <title><?php wp_title('|', true, 'right'); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php wp_head(); ?>
  <script src="https://use.typekit.net/kzp6vsx.js"></script>
  <script>try{Typekit.load({ async: true });}catch(e){}</script>
  <script>var template_dir = "<?php echo get_template_directory_uri(); ?>";</script>
  <link rel="alternate" type="application/rss+xml" title="<?php echo get_bloginfo('name'); ?> Feed" href="<?php echo home_url(); ?>/feed/">
</head>
