<?php
/**
 * Sidebar setup for social area in 4th section of footer.
 *
 * @package understrap
 */
?>

<?php if ( is_active_sidebar( 'footer4' ) ) : ?>
	<?php dynamic_sidebar( 'footer4' ); ?>
<?php endif; ?>