<footer class="content-info" role="contentinfo">
  	<div id="more-social">
  		More good things
  		<ul class="social-icons">
  			<li><a href="http://ohgoodthings.tumblr.com" target="_blank">Music</a></li>
  			<li><a href="http://instagram.com/lukelucky" target="_blank">Pictures</a></li>
  			<li><a href="http://github.com/lukesmmr" target="_blank">Code</a></li>
  		</ul>
  		<span class="footer-comment">This website grows while i travel. Come back soon.</span>

  	</div>
  	<div id="legal-stuff">
      <?php dynamic_sidebar('sidebar-footer'); ?>
      <span class="footer-comment">&copy; <?php echo date('Y'); ?></span>
  	</div>
</footer>
<script src="//use.typekit.net/kzp6vsx.js"></script>
<script>try{Typekit.load();}catch(e){}</script>
<script src="http://maps.google.com/maps/api/js?sensor=false&v=3&libraries=geometry"></script>
<?php wp_footer(); ?>
<script src="<?php bloginfo('template_url') ?>/assets/js/main.js"></script>
