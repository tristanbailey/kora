<?php global $photography; ?>
<?php if ( is_single() && get_post_type() == 'gallery' ) :
global $wp_query;
  $post_id = $wp_query->get_queried_object_id();
  ?>
  <script>
    var photos = [
      <?php
        $photos = get_post_meta( $post_id, 'enable_flickr', true ) ? $photography->get_flickr_images( $post_id ) : $photography->get_gallery_images( $post_id, false );
        echo implode(',', $photos);
      ?>];
    Galleria.loadTheme('<?php echo get_template_directory_uri(); ?>/javascripts/galleria-theme/galleria.classic.js');
    jQuery('#gallery-single').galleria({
      data_source: photos,
      transition: 'slide',
      showInfo: false,
      image_crop: <?php echo get_post_meta( $post_id, 'image_crop', true ) ? 'true' : 'false';?>,
      autoplay: <?php echo get_post_meta( $post_id, 'auto_play', true ) ? 'true' : 'false';?>,
      <?php $gallery_height = intval( get_post_meta( $post_id, 'image_height', true ) ); ?>
      height: <?php echo $gallery_height > 0 ? esc_js( $gallery_height ) : '540'; ?> + 80
    });

    // KEYBOARD SHORTCUTS/NAVIGATION
    jQuery(document).keydown(function(e) {
      if ( ! e.ctrlKey && ! e.altKey && ! e.shiftKey && ! e.metaKey) {
        if (e.which == 37) {  // Left arrow key code
          jQuery('.galleria-image-nav-left').click();
        } else if (e.which == 39) {  // Right arrow key code
          jQuery('.galleria-image-nav-right').click();
        }
      }
    });
  </script>
<?php endif; // single gallery ?>

</div>

<div id="footer" class="row">
          <div class="footer col span_6">
              
<div class="footer-container">
    <div class="footer">
      <div id="footer-links">
      &copy; 2012 kora Ltd. All rights reserved | <a href="/shop">SHOP</a> | <a href="/pages/contact/customer-service/privacy-policy">PRIVACY POLICY</a> | <a href="/pages/contact/customer-service/terms-and-conditions">TERMS &amp; CONDITIONS</a> | <a href="/pages/contact/customer-service">CUSTOMER SERVICE</a> | <a href="/pages/contact">CONTACT</a> | <a href="/pages/contact/faqs">FAQ</a> | <a href="/pages/c ontact/newsletter/">NEWSLETTER</a></div>

      <div id="worldpay-icons" style="margin-top: 25px">
        
        <a href="http://www.worldpay.com/support/index.php?CMP=BA22713" target="_blank"><img src="https://secure.worldpay.com/jsp/shopper/icons/../pictures/poweredByWorldPay.gif" border="0" alt="Powered by WorldPay" height="25px" /></a>
        <a href="http://www.mastercard.com" target="_blank"><img src="https://secure.worldpay.com/jsp/shopper/icons/WP_ECMC.gif" border="0" alt="MasterCard Credit" height="25px"  /></a>
        <a href="http://www.mastercard.com" target="_blank"><img src="https://secure.worldpay.com/jsp/shopper/icons/WP_ECMC_DEBIT.gif" border="0" alt="Debit MasterCard" height="25px"  /></a>
        <a href="http://www.visa.com" target="_blank"><img src="https://secure.worldpay.com/jsp/shopper/icons/WP_VISA.gif" border="0" alt="Visa Credit" height="25px"  /></a>
        <a href="http://www.visa.com" target="_blank"><img src="https://secure.worldpay.com/jsp/shopper/icons/WP_VISA_DELTA.gif" border="0" alt="Visa Debit" height="25px"  /></a>
        
      </div>
    </div>
</div>

          </div>
        </div>
      </div>

 <?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
