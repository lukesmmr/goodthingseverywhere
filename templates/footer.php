<footer class="container content-info" role="contentinfo">
    <hr />
  	<div class="more-social">
  		More good things
  		<ul class="social-icons">
        <li><a href="https://twitter.com/goodthngs" target="_blank">News</a></li>
        <li><a href="https://instagram.com/lukelucky" target="_blank">Pics</a></li>
        <li><a href="http://movingthngs.tumblr.com" target="_blank">Gif</a></li>
        <li><a href="http://ohgoodthings.tumblr.com" target="_blank">Tunes</a></li>
        <li><a href="https://github.com/lukesmmr" target="_blank">Code</a></li>
        <li><a href="https://medium.com/@goodthngs" target="_blank">Medium</a></li>
  		</ul>
  		<span class="footer-comment">This website evolves while I travel. Come back soon.</span>

  	</div>
  	<div class="legal-stuff">
      <?php dynamic_sidebar('sidebar-footer'); ?>
      <span class="copyright">&copy; <?php echo date('Y'); ?></span>
  	</div>
</footer>
<?php if (WP_ENV !== 'development') : ?>
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
<?php endif; ?>
<?php wp_footer(); ?>
