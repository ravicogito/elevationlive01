<?php
/**
 * Template Name: Order Page Only
 *
 * This template can be used to override the default template and sidebar setup
 *
 * @package understrap
 */

get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>

<div class="wrapper" id="page-wrapper">
	<div class="<?php echo esc_html( $container ); ?>" id="content">
		<div class="row">
			<div class="col-md-9 content-area" id="primary">
				<main class="site-main" id="main" role="main" style='position:relative;'>
				
				<div class='waiting-cover' style='position: absolute; left:0px; top:0px; width:100%; height:100%; background-color:rgba(255,255,255,0.8); z-index: 99999; text-align: center; box-sizing:border-box; padding-top: 250px; display: none;'>
					
					<img src='<?php bloginfo('template_url');?>/images/orangespinner.gif' />
					
				</div><!-- END WAITING COVER -->
				
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'loop-templates/content', 'pageorder' ); ?>
					<?php endwhile; // end of the loop. ?>
				</main><!-- #main -->
			</div><!-- #primary -->
			<?php get_sidebar( 'order' ); ?>
		</div><!-- .row -->
	</div><!-- Container end -->
</div><!-- Wrapper end -->
<?php get_footer(); ?>