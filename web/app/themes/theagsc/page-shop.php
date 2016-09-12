<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>

        <div id="work" class="masonry" data-columns>
          <div class="item">
            <a href="http://www.losttype.com/font/?name=buffon">
              <img src="<?php bloginfo('template_directory'); ?>/assets/images/buffon-shop.png" alt="Buffon"/>
            </a>
          </div>
          <div class="item">
            <script src="https://gumroad.com/js/gumroad-embed.js"></script>
            <div class="gumroad-product-embed" data-gumroad-product-id="kellerscript" data-outbound-embed="true"><a href="https://gumroad.com/l/kellerscript">Loading...</a></div>
      		</div>
          <div class="item">
            <script src="https://gumroad.com/js/gumroad-embed.js"></script>
            <div class="gumroad-product-embed" data-gumroad-product-id="general-texture"><a href="https://gumroad.com/l/general-texture">Loading...</a></div>
      		</div>

          <div class="item">
            <script src="https://gumroad.com/js/gumroad-embed.js"></script>
            <div class="gumroad-product-embed" data-gumroad-product-id="retro-grain"><a href="https://gumroad.com/l/kESuV">Loading...</a></div>
          </div>

          <div class="item">
            <script src="https://gumroad.com/js/gumroad-embed.js"></script>
            <div class="gumroad-product-embed" data-gumroad-product-id="another-grain"><a href="https://gumroad.com/l/YrltQF">Loading...</a></div>
          </div>

          <div class="item">
            <script src="https://gumroad.com/js/gumroad-embed.js"></script>
            <div class="gumroad-product-embed" data-gumroad-product-id="sparse"><a href="https://gumroad.com/l/sparse">Loading...</a></div>
          </div>
        </div>

<?php endwhile; ?>
