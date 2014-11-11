<button class="recommend"> Recommend article <i class="glyphicon glyphicon-heart"></i></button>
<div class="popover bottom share-options">
	<div class="arrow" style="left: 50%;"></div>
	<div class="popover-content">
		<ul>
		  <li><a data-pocket-label="pocket" data-pocket-count="none" class="pocket-btn" data-lang="en"></a><script type="text/javascript">!function(d,i){if(!d.getElementById(i)){var j=d.createElement("script");j.id=i;j.src="https://widgets.getpocket.com/v1/j/btn.js?v=1";var w=d.getElementById(i);d.body.appendChild(j);}}(document,"pocket-btn-js");</script></li>
		  <li><a href="https://twitter.com/share" class="twitter-share-button" data-via="goodthngs" data-count="none">Tweet</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>
		  <li><div class="fb-like" data-href="<?php the_permalink(); ?>" data-layout="button" data-action="recommend" data-show-faces="false" data-share="false"></div></li>
		</ul> 
	</div>
</div>