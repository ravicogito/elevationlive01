<?php
/**
 * Partial template for content in shoppinginfopage.php
 *
 * @package understrap
 */
global $Quote; $places = $Quote->get_places();
$currency = $Quote->currency;
$symbol = $Quote->get_currency_symbol();
?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header whitetextbox">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>

	<div class="entry-content">
    <div class="container">
		
			<div class="row mt-4">
            	<?php the_content(); ?>
            </div>
            <div class="row mt-4">
                <div class='shopping-experience-box'>
                    
                    <h3>My Shopping Experience</h3>
                    
                    <input type='hidden' id='price-per-pack' value='<?php echo $Quote->get_shopping_option('price_per_shopping_package');?>' />
                    
                    <div class='shopping-left'>
                        <p>Select from the following packages:</p>
                        
                        <select class='shop-packs form-control custom-select'>
                            
                            <?php $options = $Quote->get_shopping_option('shopping_options');
            $options = unserialize($options);
                            $index = 0; $i = 1;foreach($options as $option): if($i == 1): $pprice = $option['price']; endif;
                            ?>
                            <option data-index='<?php echo $index;?>' data-price='<?php echo $option['price'];?>' value='<?php echo $option['name'];?>'><?php echo $option['name'];?> (£<?php echo number_format($option['price'], 2);?>)</option>
                        <?php $index++; $i++; endforeach;?>
                            
                        </select>
                    </div>
                
                    <div class='shopping-right'>
                    
                        <p>Additional packages required (£<?php echo number_format($Quote->get_shopping_option('price_per_shopping_package'), 2);?>):</p>
                        
                        <input type='number' class='pack-numbers form-control' min='0' value='0' />
                    
                    </div>
                
                    <div class='clearfix'></div>
                    
                    <a class='shopping-buy-now' href='Javascript:void(0);'>Buy Now <span>(£<?php echo number_format($pprice, 2);?>)</span></a>
                    
                    <div class='clearfix'></div>
                    
                </div>
            
			</div>

		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'understrap' ),
			'after'  => '</div>',
		) );
		?>
	</div>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php edit_post_link( __( 'Edit', 'understrap' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
