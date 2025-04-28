<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Kimsreng
 */

?>

	<footer id="colophon" class="site-footer">
		<!-- Footer -->
<footer class="text-center text-lg-start bg-body-tertiary text-muted">
  <!-- Section: Social media -->
  <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
    <!-- Left -->
    <div class="me-5 d-none d-lg-block">
      <span>Get connected with us on social networks:</span>
    </div>
    <!-- Left -->

    <!-- Right -->
    <div>
      <a href="" class="me-4 text-reset">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="" class="me-4 text-reset">
        <i class="fab fa-twitter"></i>
      </a>
      <a href="" class="me-4 text-reset">
        <i class="fab fa-google"></i>
      </a>
      <a href="" class="me-4 text-reset">
        <i class="fab fa-instagram"></i>
      </a>
      <a href="" class="me-4 text-reset">
        <i class="fab fa-linkedin"></i>
      </a>
      <a href="" class="me-4 text-reset">
        <i class="fab fa-github"></i>
      </a>
    </div>
    <!-- Right -->
  </section>
  <!-- Section: Social media -->

  <!-- Section: Links  -->
  <section class="">
    <div class="container text-center text-md-start mt-5">
      <!-- Grid row -->
      <div class="row mt-3">
        <!-- Grid column -->
        <div class="col-md-4 col-lg-6 col-xl-3 mx-auto mb-4">
        <?php dynamic_sidebar('footer-widget-col-one') ?>
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-4 col-lg-3 col-xl-2 mx-auto mb-4">
          <?php dynamic_sidebar('footer-widget-col-two') ?>
        </div>
        <div class="col-md-4 col-lg-3 col-xl-2 mx-auto mb-4">
          <?php dynamic_sidebar('footer-widget-col-three') ?>
        </div>
        <!-- Grid column -->
      </div>
      <!-- Grid row -->
    </div>
  </section>
  <!-- Section: Links  -->

  <!-- Copyright -->
  <div class="row">
    <div class=" col-6 text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
    <p>&copy;<?php bloginfo(' name '); ?> <?php echo date('Y'); ?> created by <a href="mailto:natkimsreng@gmail.com" target="_blank">KIMSRENG</a></p>
    </div>
    <div class=" col-6 text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
      <img src="<?php echo get_template_directory_uri(); ?>/img/payment-methods.png" alt="">
    </div>
  </div>
  <!-- Copyright -->
</footer>
<!-- Footer -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
