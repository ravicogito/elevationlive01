<?php

/**

 * Template Name: Home Page Only

 *

 * Template for displaying a page without sidebar even if a sidebar widget is published.

 *

 * @package understrap

 */



get_header();

$container = get_theme_mod( 'understrap_container_type' );

?>



<div class="splash-container">

	<div id="splash-inner-container" class="splash-inner-container">

        <img class='splash-image' src="<?php echo get_template_directory_uri(); ?>/images/hpslide-05.jpg" alt="" />

        <img class='splash-image' src="<?php echo get_template_directory_uri(); ?>/images/hpslide-04.jpg" alt="" />

        <img class='splash-image' src="<?php echo get_template_directory_uri(); ?>/images/hpslide-03.jpg" alt="" />

        <img class='splash-image' src="<?php echo get_template_directory_uri(); ?>/images/hpslide-02.jpg" alt="" />

        <img class='splash-image' src="<?php echo get_template_directory_uri(); ?>/images/hpslide-01.jpg" alt="" />

    </div>

</div>



<div class="wrapper" id="full-width-page-wrapper">



	<div class="<?php echo esc_html( $container ); ?>" id="content">



		<div class="row">



			<div class="col-md-12 content-area" id="primary">



				<main class="site-main" id="main" role="main">



					<?php while ( have_posts() ) : the_post(); ?>



						<?php get_template_part( 'loop-templates/content', 'pagehome' ); ?>



					<?php endwhile; // end of the loop. ?>



				</main><!-- #main -->


			
		

			</div><!-- #primary -->



		</div><!-- .row end -->



	</div><!-- Container end -->



</div><!-- Wrapper end -->



<?php get_footer(); ?>

