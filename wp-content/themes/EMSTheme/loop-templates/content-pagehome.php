<?php
/**
 * Partial template for content in homepage.php
 *
 * @package understrap
 */

?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="home-header">

		<?php the_title( '<div class="entry-title text-center">', '</div>' ); 
		$subtitle = get_post_meta($post->ID, 'Subtitle', $single = true);
		$subsubtitle = get_post_meta($post->ID, 'Subsubtitle', $single = true);
		if($subtitle !== '') echo '<h2 class="text-center">' . $subtitle . '</h2>';
		if($subsubtitle !== '') echo '<h2 class="text-center">' . $subsubtitle . '</h2>';
		?>

	</header><!-- .entry-header -->
    <?php global $Quote; $places = $Quote->get_places();?>
    <div class="quick-quote-container">
    	<form action="<?php bloginfo('home');?>/quote" class="form-inline" id="quick-quote" method="post" novalidate>
            <input name="__RequestVerificationToken" type="hidden" >
                <label for="Origin_CountryIso" class="col-md-2">Send From</label>
                <select id="Origin_CountryIso" name="Origin.CountryIso" class="col-md-4 custom-select" placeholder="select a country" autocomplete="false" data-bind="value: CountryIso" data-val="true" data-val-required="Country is required." tabindex="-1" title="Send From">
                    <optgroup label="Most Popular">
                       <?php foreach($places as $place):?>
                       	<?php if($place->is_popular == 1):?>
                       		<option value='<?php echo $place->id;?>' <?php if($place->orderx == 1):?>selected='selected'<?php endif;?>><?php echo $place->name;?></option>
                       	<?php endif;?>
                       <?php endforeach;?>
                    </optgroup>
                    <optgroup label="A-Z">
                        <?php $places = $Quote->get_places_by_name();
						foreach($places as $place):?>
                      
                       		<option value='<?php echo $place->id;?>'><?php echo $place->name;?></option>
                       	
                       <?php endforeach;?>
                    </optgroup>
                </select>
    
                <label class="col-md-1" for="Destination_CountryIso">To</label>
                <select id="Destination_CountryIso" name="Destination.CountryIso" class="col-md-4 custom-select" placeholder="select a country" autocomplete="false" data-bind="value: CountryIso" data-val="true" data-val-required="Country is required." tabindex="-1" title="To">
                	<option value="">select a country</option>
                   <optgroup label="Most Popular">
                       <?php  $places = $Quote->get_places();foreach($places as $place):?>
                       	<?php if($place->is_popular == 1):?>
                       		<option value='<?php echo $place->id;?>'><?php echo $place->name;?></option>
                       	<?php endif;?>
                       <?php endforeach;?>
                    </optgroup>
                    <optgroup label="A-Z">
                        <?php $places = $Quote->get_places_by_name();foreach($places as $place):?>
                      
                       		<option value='<?php echo $place->id;?>'><?php echo $place->name;?></option>
                       	
                       <?php endforeach;?>
                    </optgroup>
                </select>
            <button type="submit" id="btn-quick-quote" class="col-md-1 btn btn-primary">Go</button>
        </form>
    </div>

	<div class="entry-content">
    	<div class="container">
            <div class="row">
            	<div class="col-7">
                    <h3>How Express My Stuff Works</h3>
                </div>
                <div class="col">
                    <div class="text-uppercase text-right"><a href="<?php bloginfo('home');?>/how-it-works/">More Info &gt;&gt;</a></div>
                </div>
            </div>
            <div id="hiw" class="row mt-4">
                <div class="col-md-2">
                	<a href="">
                        <div id="hiw1" class="hiw-icon"></div>
                        <div class="hiw-label mt-4 text-center">Price It Up</div>
                    </a>
                </div>
                <div class="col-md-2">
                	<a href="">
                        <div id="hiw2" class="hiw-icon"></div>
                        <div class="hiw-label mt-4 text-center">Order Online</div>
                    </a>
                </div>
                <div class="col-md-2">
                	<a href="">
                        <div id="hiw3" class="hiw-icon"></div>
                        <div class="hiw-label mt-4 text-center">Pack Your Stuff</div>
                    </a>
                </div>
                <div class="col-md-2">
                	<a href="">
                        <div id="hiw4" class="hiw-icon"></div>
                        <div class="hiw-label mt-4 text-center">DHL Collect</div>
                    </a>
                </div>
                <div class="col-md-2">
                	<a href="">
                        <div id="hiw5" class="hiw-icon"></div>
                        <div class="hiw-label mt-4 text-center">Track Your Stuff</div>
                    </a>
                </div>
                <div class="col-md-2">
                	<a href="">
                        <div id="hiw6" class="hiw-icon"></div>
                        <div class="hiw-label mt-4 text-center">Delivered</div>
                    </a>
                </div>
            </div>
            <div class="row">
            	<div class="col-md-6  mt-4 orangetextbox">
                    <h3>Why we are the first choice....</h3>
                    <p style="margin-bottom:0.6rem;"><i style="font-size:1.5rem;" class="fa fa-check-square-o" aria-hidden="true"></i> Price match plus 10% discount - use code MAT</p>
                    <p style="margin-bottom:0.6rem;"><i style="font-size:1.5rem;" class="fa fa-check-square-o" aria-hidden="true"></i> 2 free label holders with FREE POSTAGE*</p>
                    <p style="margin-bottom:0.6rem;"><i style="font-size:1.5rem;" class="fa fa-check-square-o" aria-hidden="true"></i> 10% STUDENT DISCOUNT - use code STUDXs</p>
                    <p style="margin-bottom:0.6rem;"><i style="font-size:1.5rem;" class="fa fa-check-square-o" aria-hidden="true"></i> Best reviews by a mile</p>
                    <p class="small text-right">*UK addresses only</p>
                    <p class="small text-right" style="margin-bottom:0;">**Only one discount can be used at any one time</p>
                </div>
                <div class="col-md-6  mt-4" style="padding-right:0;">
                    <a href="https://uk.trustpilot.com/review/www.dhl.co.uk">
                        <img class="img-fluid" src="<?php bloginfo('template_url');?>/images/reviews3.jpg" />
                    </a>
                </div>
            </div>
            <div class="row mt-4 hppricebox">
            	<div class="col-md-9">
                    <h3 class="text-uppercase">International Luggage Shipping Services</h3>
                </div>
                <div class="col-md-3">
                    <div class="text-uppercase text-right"><a href="<?php bloginfo('home');?>/baggage-shipping-destinations/">More Info &gt;&gt;</a></div>
                </div>
                <?php global $Quote;
				$currency = $Quote->currency;
				$symbol = $Quote->get_currency_symbol();
				?>
                <div class="col-md-2 shipping-destination mt-2">
                	<a href="order/?cf=GB&ct=GB">
                       <?php $rate = 15;
						if($currency != 'GBP'):
							$rate = $Quote->convert_gbp_currency($currency, $rate);
							$rate = ceil($rate);
						endif;
						?>
                        UK
                        <div class="shipping-price" <?php if($currency == 'AUD' || $currency == 'SEK'):?>style='font-size:54px;'<?php endif;?>><?php echo $symbol;?><?php echo $rate;?></div>
                        <div class="flagicon ukflag"></div>
                    </a>
                </div>
                <div class="col-md-2 shipping-destination mt-2">
                    <a href="order/?cf=GB&ct=ES">
                       <?php $rate = 31;
						if($currency != 'GBP'):
							$rate = $Quote->convert_gbp_currency($currency, $rate);
							$rate = ceil($rate);
						endif;?>
                        Spain
                        <div class="shipping-price" <?php if($currency == 'AUD' || $currency == 'SEK'):?>style='font-size:54px;'<?php endif;?>><?php echo $symbol;?><?php echo $rate;?></div>
                        <div class="flagicon spainflag"></div>
                    </a>
                </div>
                <div class="col-md-2 shipping-destination mt-2">
                    <a href="order/?cf=GB&ct=US">
                       <?php $rate = 35; 
						if($currency != 'GBP'):
							$rate = $Quote->convert_gbp_currency($currency, $rate);
							$rate = ceil($rate);
						endif;?>
                        United States
                         <div class="shipping-price" <?php if($currency == 'AUD' || $currency == 'SEK'):?>style='font-size:54px;'<?php endif;?>><?php echo $symbol;?><?php echo $rate;?></div>
                        <div class="flagicon usflag"></div>
                    </a>
                </div>
                <div class="col-md-2 shipping-destination mt-2">
                    <a href="order/?cf=GB&ct=AU">
                       <?php $rate = 48;if($currency != 'GBP'):
							$rate = $Quote->convert_gbp_currency($currency, $rate);
							$rate = ceil($rate);
						endif;?>
                        Australia
                        <div class="shipping-price" <?php if($currency == 'AUD' || $currency == 'SEK'):?>style='font-size:54px;'<?php endif;?>><?php echo $symbol;?><?php echo $rate;?></div>
                        <div class="flagicon ozflag"></div>
                    </a>
                </div>
                <div class="col-md-2 shipping-destination mt-2">
                    <a href="order/?cf=GB&ct=FR">
                       <?php $rate = 29;if($currency != 'GBP'):
							$rate = $Quote->convert_gbp_currency($currency, $rate);
							$rate = ceil($rate);
						endif;?>
                        France
                        <div class="shipping-price" <?php if($currency == 'AUD' || $currency == 'SEK'):?>style='font-size:54px;'<?php endif;?>><?php echo $symbol;?><?php echo $rate;?></div>
                        <div class="flagicon frenchflag"></div>
                    </a>
                </div>
                <div class="col-md-2 shipping-destination mt-2">
                    <a href="order/?cf=GB&ct=DE">
                    <?php $rate = 29;if($currency != 'GBP'):
							$rate = $Quote->convert_gbp_currency($currency, $rate);
							$rate = ceil($rate);
						endif; ?>
                    	Germany
                    	 <div class="shipping-price" <?php if($currency == 'AUD' || $currency == 'SEK'):?>style='font-size:54px;'<?php endif;?>><?php echo $symbol;?><?php echo $rate;?></div>
                        <div class="flagicon germanflag"></div>
                    </a>
                </div>
            </div>
            <div class="row mt-4">
            	<div class="col-md-12">
                	<a href="<?php bloginfo('home');?>/express-my-shopping/">
                        <img class="img-fluid" src="<?php bloginfo('template_url');?>/images/shopping.jpg" />
                    </a>
                </div>
            </div>
            <!-- <div class="row mt-4">
            	<div class="col-md-6">
                	<div class="hp-widget hp-student bgfadein">
                        <div class="textposition">
                            <div class="whitetextboxlp">
                                <h2 class="text-right"><strong>Shipping For Students</strong></h2>
                                <div class="text-right">Student discount available with #EMSStudents, NUS &amp; UCAS.</div>
                            </div>
                            <a class="float-right whitetextboxlp" href="Read More">Read More >></a>
                        </div>
                    </div>
                </div>
            	<div class="col-md-6">
                	<div class="hp-widget hp-business bgfadein">
                        <div class="textposition">
                            <div class="whitetextboxlp">
                                <h2><strong>Worldwide Destinations</strong></h2>
                                <div>International baggage shipping for business people &amp; holiday makers.</div>
                            </div>
                            <a class="whitetextboxlp" href="Read More">Read More >></a>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="row">
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
            <div class="row mt-4">
            	<?php the_content(); ?>
            </div>
        </div>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
    
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->