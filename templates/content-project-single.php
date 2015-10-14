<div class="container">
  <?php while (have_posts()) : the_post(); ?>
    <?php // project meta
      $linked_journal_id = get_post_meta( get_the_ID(), 'project_post_select', true);
      // linked post meta
      $barter_name = get_post_meta($linked_journal_id, 'post_project_name', true);
      $barter_location = get_post_meta($linked_journal_id, 'post_loc', true);
      $barter_url = get_post_meta($linked_journal_id, 'post_project_url', true);
      $barter_date = get_post_meta($linked_journal_id, 'post_project_date', true);
      $barter_thumb = get_post_meta($linked_journal_id, 'post_project_thumb', true);
      $barter_task = get_post_meta($linked_journal_id, 'post_project_task', true);
      $barter_trade = get_post_meta($linked_journal_id, 'post_project_trade', true);
      $barter_description = get_post_meta($linked_journal_id, 'post_project_description', true);

      // project meta
      $project_url = get_post_meta( get_the_ID(), 'project_url', true);
      $project_year = get_post_meta( get_the_ID(), 'project_year', true);
      $project_agency = get_post_meta( get_the_ID(), 'project_agency', true);
      $project_tech = get_post_meta( get_the_ID(), 'project_tech', true);
      $url_trim = trim($project_url, '/');
      // get rid of http or www
      if (!preg_match('#^http(s)?://#', $url_trim)) {
          $url_trim = 'http://' . $url_trim;
      }
      $urlParts = parse_url($url_trim);
      $project_url = preg_replace('/^www\./', '', $urlParts['host']);

    ?>
    <div class="container page-header">
      <h1>
        <?php echo roots_title(); ?>
      </h1>
      <div class="project-details">
          <ul>
            <?php if ( $project_agency ) : ?><li><?php echo $project_agency; ?></li><?php endif; ?>
            <?php if ( $project_year ) : ?><li><?php echo $project_year; ?></li><?php endif; ?>
            <?php if ( $project_url ) : ?>
              <li><?php if ($project_url !== 'offline') : ?><a href="http://<?php echo $project_url; ?>" target="_blank" title="External link to <?php echo $the_title ?> Link"><?php echo $project_url ?></a>
              <?php else : ?>
                <?php echo $project_url ?>
              <?php endif; ?>
              </li>
            <?php endif; ?>
          </ul>
          <ul class="project-meta">
          </ul>
      </div>
    </div>
    <article <?php post_class(); ?>>
      <header>
        <?php get_template_part('templates/project-meta'); ?>
        <?php if ($linked_journal_id): ?>
          <div class="barter-project">
            <div class="barter-main col-sm-8">
              <?php if ( $barter_thumb ) : ?>
                <div class="barter-thumbnail">
                  <img src="<?php echo $barter_thumb ?>" alt="<?php echo $barter_name ?> Thumbnail" />
                </div>
              <?php endif;  ?>
            </div>
            <div class="barter-right col-sm-4">
              <div class="barter-details">
                <h3>Barter details</h3>
                <ul>
                  <?php if ( $barter_name ) : ?><li><span>With whom</span> <?php echo $barter_name; ?></li><?php endif; ?>
                  <?php if ( $barter_task ) : ?><li><span>I worked on</span> <?php echo $barter_task; ?></li><?php endif; ?>
                  <?php if ( $barter_trade ) : ?><li><span>What I got in return</span> <?php echo $barter_trade; ?></li><?php endif; ?>
                </ul>
                <ul class="barter-meta">
                  <?php if ( $barter_location ) : ?><li><i class="glyphicon glyphicon-map-marker"></i><?php echo $barter_location; ?></li><?php endif; ?>
                  <?php if ( $barter_date ) : ?><li><i class="glyphicon glyphicon-time"></i><?php echo $barter_date; ?></li><?php endif; ?>
                  <?php if ( $barter_url ) : ?><li class="barter-url"><i class="glyphicon glyphicon-link"></i><a href="http://<?php echo $barter_url; ?>" target="_blank" title="<?php echo $barter_name ?> Link"><?php echo $barter_url ?></a></li><?php endif; ?>
                </ul>
              </div>
              <div class="barter-more read-more">
                <a href="<?php echo get_post_permalink($linked_journal_id);  ?>" title="Link to Journal entry">Read the story <span class="glyphicon glyphicon-triangle-right"></span> </a>
              </div>
            </div>
            <div class="barter-description">
              <?php echo $barter_description; ?>
            </div>
          </div>
        <?php endif; ?>
      </header>

      <div class="entry-content project-content">
        <?php the_content(); ?>
      </div>
      <nav class="post-nav">
          <ul class="pager">
            <li class="next"><?php previous_post_link('%link', 'Previous project &rarr;'); ?></li>
            <li class="previous"><?php next_post_link('%link', '&larr; Next project'); ?> </li>
          </ul>
        </nav>
    </article>
  <?php endwhile; ?>
</div>
