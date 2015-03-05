<?php
  $settings = \Roots\ShareButtons\Admin\get_settings();

  $url    = '';
  $title  = '';
  $shares = '';
  if (!$url)   { $url   = get_permalink(); }
  if (!$title) { $title = get_the_title(); }

  if (in_array('enabled', $settings['share_count'])) {
    $shares           = new \Roots\ShareButtons\ShareCount\shareCount($url);
    $shares_twitter   = $shares->get_tweets();
    $shares_fb        = $shares->get_fb();
    $shares_gplus     = $shares->get_plusones();
    $shares_linkedin  = $shares->get_linkedin();
    $shares_pinterest = $shares->get_pinterest();
  }
?>

<div class="entry-share">
  <ul class="entry-share-btns">
    <?php
      foreach($settings['button_order'] as $setting) {
        switch($setting) {
          case 'twitter':
            if (in_array('twitter', $settings['buttons'])) : ?>
              <li class="entry-share-btn entry-share-btn-twitter">
                <a class="popup" href="https://twitter.com/intent/tweet?text=<?php echo urlencode(html_entity_decode($title, ENT_COMPAT, 'UTF-8')); ?>&url=<?php echo urlencode($url); ?>" title="<?php _e('Share on Twitter', 'roots_share_buttons'); ?>">
                  
                  <img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_twit.svg"/>
                  
                  <b><?php _e('Tweet', 'roots_share_buttons'); ?></b>
                  <?php if ($shares) : ?>
                    <span class="count"><?php echo $shares_twitter; ?></span>
                  <?php endif; ?>
                </a>
              </li>
            <?php endif;
            break;
          case 'facebook':
            if (in_array('facebook', $settings['buttons'])) : ?>
              <li class="entry-share-btn entry-share-btn-facebook">
                <a class="popup" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url); ?>" title="<?php _e('Share on Facebook', 'roots_share_buttons'); ?>">
                  <img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_fb.svg"/>
                  <b><?php _e('Share', 'roots_share_buttons'); ?></b>
                  <?php if ($shares) : ?>
                    <span class="count"><?php echo $shares_fb; ?></span>
                  <?php endif; ?>
                </a>
              </li>
            <?php endif;
            break;
          case 'google_plus':
            if (in_array('google_plus', $settings['buttons'])) : ?>
              <li class="entry-share-btn entry-share-btn-google-plus">
                <a class="popup" href="https://plus.google.com/share?url=<?php echo urlencode($url); ?>" title="<?php _e('Share on Google+', 'roots_share_buttons'); ?>">
                  <img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_gplus.svg"/>                  <b><?php _e('+1', 'roots_share_buttons'); ?></b>
                  <?php if ($shares) : ?>
                    <span class="count"><?php echo $shares_gplus; ?></span>
                  <?php endif; ?>
                </a>
              </li>
            <?php endif;
            break;
          case 'linkedin':
            if (in_array('linkedin', $settings['buttons'])) : ?>
              <li class="entry-share-btn entry-share-btn-linkedin">
                <a class="popup" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($url); ?>&summary=" title="<?php _e('Share on LinkedIn', 'roots_share_buttons'); ?>">
                 <img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_twit.svg"/>                  <b><?php _e('Share', 'roots_share_buttons'); ?></b>
                  <?php if ($shares) : ?>
                    <span class="count"><?php echo $shares_linkedin; ?></span>
                  <?php endif; ?>
                </a>
              </li>
            <?php endif;
            break;
          case 'pinterest':
            if (in_array('pinterest', $settings['buttons'])) : ?>
            
              <li class="entry-share-btn entry-share-btn-pinterest">
                <script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/assets/scripts/pin.js"></script><a href="javascript:selectPinImage()" title="<?php _e('Share on Pinterest', 'roots_share_buttons'); ?>">
                  <img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_pin.svg"/>                  <b><?php _e('Pin it', 'roots_share_buttons'); ?></b>
                  <?php if ($shares) : ?>
                    <span class="count"><?php echo $shares_pinterest; ?></span>
                  <?php endif; ?>
                </a>
              </li>
            <?php endif;
            break;
        }
      }
    ?>
  </ul>
</div>
