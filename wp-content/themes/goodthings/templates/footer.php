<footer class="content-info" role="contentinfo">
  	<div id="more-social">
  		More good things
  		<ul class="social-icons">
        <li><a href="https://twitter.com/goodthngs" target="_blank">News</a></li>
        <li><a href="http://ohgoodthings.tumblr.com" target="_blank">Music</a></li>
        <li><a href="https://instagram.com/lukelucky" target="_blank">Pictures</a></li>
  			<li><a href="https://movingthngs.tumblr.com" target="_blank">GIF</a></li>
        <li><a href="https://github.com/lukesmmr" target="_blank">Code</a></li>
  		</ul>
  		<span class="footer-comment">This website evolves while i travel. Come back soon.</span>

  	</div>
  	<div id="legal-stuff">
      <?php dynamic_sidebar('sidebar-footer'); ?>
      <span class="footer-comment">&copy; <?php echo date('Y'); ?></span>
  	</div>
</footer>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-69460-22']);
  _gaq.push(['_setDomainName', 'goodthingseverywhere.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script src="http://maps.google.com/maps/api/js?sensor=false&v=3&libraries=geometry"></script>
<?php wp_footer(); ?>
<script src="<?php bloginfo('template_url') ?>/assets/js/main.js"></script>
