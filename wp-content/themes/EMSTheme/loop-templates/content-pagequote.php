<?php
/**
 * Partial template for content in quotepage.php
 *
 * @package understrap
 */

?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header whitetextbox">
		<?php the_title( '<h1 class="entry-title orangetext">', '</h1>' );
		$subtitle = get_post_meta($post->ID, 'Subtitle', $single = true);
		if($subtitle !== '') echo '<h2>' . $subtitle . '</h2>'; ?>
	</header><!-- .entry-header -->
    	
    	<?php set_time_limit(0); global $Quote;
$places = $Quote->get_places();
	
		if(isset($_POST['Origin_CountryIso'])):
		$from_place = $Quote->get_place($_POST['Origin_CountryIso']);
	
		
		$to_place = $Quote->get_place($_POST['Destination_CountryIso']);

	

	
	
	function currencyConverter($currency_from,$currency_to,$currency_input){
$yql_base_url = "http://query.yahooapis.com/v1/public/yql";
$yql_query = 'select * from yahoo.finance.xchange where pair in ("'.$currency_from.$currency_to.'")';
$yql_query_url = $yql_base_url . "?q=" . urlencode($yql_query);
$yql_query_url .= "&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
$yql_session = curl_init($yql_query_url);
curl_setopt($yql_session, CURLOPT_RETURNTRANSFER,true);
$yqlexec = curl_exec($yql_session);
$yql_json =  json_decode($yqlexec,true);
$currency_output = (float) $currency_input*$yql_json['query']['results']['rate']['Rate'];

return $currency_output;
}

endif;
	
	?>
    
    	
	
    <div class="quick-quote-container">
    	<form action="<?php bloginfo('home');?>/quote" class="form-inline" id="quick-quote" method="post" novalidate>
            <input name="__RequestVerificationToken" type="hidden" >
            <div class="col-md-6">
               
               
                <label for="Origin_CountryIso">Collection Country</label>
                <select id="quote-Origin_CountryIso" name="Origin_CountryIso" style="width:100%" class="custom-select" placeholder="select a country" autocomplete="false" data-bind="value: CountryIso" data-val="true" data-val-required="Country is required." tabindex="-1" title="Send From">
                    <optgroup label="Most Popular">
                       <?php foreach($places as $place):?>
                       	<?php if($place->is_popular == 1):?>
                       		<option value='<?php echo $place->id;?>'><?php echo $place->name;?></option>
                       	<?php endif;?>
                       <?php endforeach;?>
                    </optgroup>
                    <optgroup label="A-Z">
                        <?php $places = $Quote->get_places_by_name();foreach($places as $place):?>
                      
                       		<option <?php if($from_place->id == $place->id):?>selected='selected'<?php endif;?> value='<?php echo $place->id;?>'><?php echo $place->name;?></option>
                       	
                       <?php endforeach;?>
                    </optgroup>
                </select>
            </div>
            <div class="mt-3 mt-md-0 col-md-6">
                <label for="Destination_CountryIso">Destination Country</label>
                <select id="quote-Destination_CountryIso" name="Destination_CountryIso" style="width:100%" class="custom-select" placeholder="select a country" autocomplete="false" data-bind="value: CountryIso" data-val="true" data-val-required="Country is required." tabindex="-1" title="To">
                	<option value="">select a country</option>
                  <optgroup label="Most Popular">
                       <?php $places = $Quote->get_places();foreach($places as $place):?>
                       	<?php if($place->is_popular == 1):?>
                       		<option value='<?php echo $place->id;?>' ><?php echo $place->name;?></option>
                       	<?php endif;?>
                       <?php endforeach;?>
                    </optgroup>
                    <optgroup label="A-Z">
                        <?php $places = $Quote->get_places_by_name();foreach($places as $place):?>
                      
                       		<option <?php if($to_place->id == $place->id):?>selected='selected'<?php endif;?> value='<?php echo $place->id;?>'><?php echo $place->name;?></option>
                       	
                       <?php endforeach;?>
                    </optgroup>
                </select>
            </div>
            <div class="mt-3 col-md-6">
               <input style='width:100%;'  type='hidden' class='form-control' name='quote[from_city]' value='<?php echo $from_city;?>' />
                <label for="Collection_Postcode">Collection Postcode <small class="text-muted">(If Available)</small></label>
                <input style="width:100%;" name="quote[from_postcode]"  value="<?php if(isset($_POST['quote']['from_postcode'])): echo $_POST['quote']['from_postcode']; endif;?>"  type="text" class="form-control" id="Collection_Postcode">
            </div>
            <div class="mt-3 col-md-6">
               <input style='width:100%;'  type='hidden' class='form-control' name='quote[to_city]' value='<?php echo $to_city;?>' />
                <label for="Destination_Postcode">Destination Postcode <small class="text-muted">(If Available)</small></label>
                <input style="width:100%;" name="quote[to_postcode]" value="<?php if(isset($_POST['quote']['to_postcode'])): echo $_POST['quote']['to_postcode']; endif;?>" type="text" class="form-control" id="Destination_Postcode">
            </div>
            <div class="mt-3 col-md-6">
                <!-- <p class="orangetext">Please enter postcodes for a more accurate delivery time and price. Without postcodes the quote assumes capital to capital.</p> -->
                <p class="error_color_collection"></p>
                <p class="success_color_collection"></p>

               
            </div>

             <div class="mt-3 col-md-6">
                <!-- <p class="orangetext">Please enter postcodes for a more accurate delivery time and price. Without postcodes the quote assumes capital to capital.</p> -->
                <p class="error_color_destination"></p>
                <p class="success_color_destination"></p>

               
            </div>
            <div class='clearfix'></div>
            <hr />
            
            <div class='quote-dimensions-box'>
            	
            
            	
            	<div class="a-col" style="width:33.3%; float:left;">
            	<label>Width (cm)</label>
            		<input type='number' class='form-control' id='quote-width' value='5' />
				</div>
           	<div class="a-col" style="width:33.3%; float:left;">
           		<label>Height (cm)</label>
            		<input type='number' class='form-control' id='quote-height' value='5' />
				</div>
           	<div class="a-col" style="width:33.3%; float:left;">
           		<label>Length (cm)</label>
            		<input type='number' class='form-control' id='quote-length' value='5' />
				</div>
           	<div class='clearfix'></div>
            	
            </div>
            
            <input type='hidden' id='quoted-price' name='quote[quoted_price]' />
        </form>
          
            <div class="col-md-12">
            	<h4>Your Journey</h4>
                <div class="form-check form-check-inline">
                    <label class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" name="radioJourney" id="OneWayJourney" value="OneWayJourney" checked>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">One Way</span>
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" name="radioJourney" id="ReturnJourney" value="ReturnJourney">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Return Journey</span>
                    </label>
                </div>
                
                <div id='miniload' style='text-align:center; padding-bottom: 20px; display: none;'>
                	
                	<img src='<?php bloginfo('template_url');?>/images/miniload.gif' />
                	<div class='orangetext'>Calculating prices, please wait...</div>
                	
                </div>
                
                
                <div id='quote-wrap' style='display:none;'>
                <div class="row mt-3 no-gutters greybgheader">
                    <div class="col-3">
                        Service
                    </div>
                    <div class="col-9">
                        Price (Per Item)
                    </div>
                </div>
                <input type='hidden' id='spinner-url' value='<?php bloginfo('template_url');?>/images/spinner.gif' />
                <div id='ajax-show-info' class="row no-gutters whitetextboxlp" style="border: solid 1px #333; border-top: 0;">	
                   
                   
                		 <div class="col-md-3 standard-results">
                        <div class="orangetext">Standard </div>
                       
                    </div>
                    <div class="col-md-9 standard-results">
                                        <div class="row" style="padding-bottom:15px;">
                 
                    <!--	<div class="col-md-5"><span class="quick-quote-price-weight" style="width:105px;">Up to 5kg:</span> <span data-bind="text: Localise.formatCurrency(PriceIncTax())" class="quick-quote-price" id='data-5-standard'><img src='<?php //bloginfo('template_url');?>/images/spinner.gif' /></span></div>
                    	<div class="col-md-7"> <a id="standard-5-button" href="http://www.expressmystuff.co.uk/order?cf=GB&amp;ct=ES&amp;pr=30.38&amp;kg=5&amp;protocol=standard" data-orig="http://www.expressmystuff.co.uk/order?cf=GB&amp;ct=ES&amp;pr=30.38&amp;kg=5&amp;protocol=standard" class="bnbtn btn btn-primary" data-price="">Book Now (5kg)</a></div>-->
                    	</div><!-- END INNER ROW -->
                    	                    	                     	<div class="row" style="padding-bottom:15px;">
                    	<div class="col-md-6"><span class="quick-quote-price-weight" style="width:105px;">Up to 15kg:</span> <span data-bind="text: Localise.formatCurrency(PriceIncTax())" class="quick-quote-price" id='data-15-standard'><img src='<?php bloginfo('template_url');?>/images/spinner.gif' /></span></div>
                    		<div class="col-md-6"> <a id="standard-15-button" href="http://www.expressmystuff.co.uk/order?cf=GB&amp;ct=ES&amp;pr=31.59&amp;kg=15&amp;protocol=standard" data-orig="http://www.expressmystuff.co.uk/order?cf=GB&amp;ct=ES&amp;pr=31.59&amp;kg=15&amp;protocol=standard" class="bnbtn btn btn-primary" data-price="">Book Now (15kg)</a></div>
						</div><!-- END INNER ROW -->
                   	                   	                     	<div class="row" style="padding-bottom:15px;">
                    	                    	<div class="col-md-6"><span class="quick-quote-price-weight" style="width:105px;">Up to 30kg:</span> <span id='data-30-standard' data-bind="text: Localise.formatCurrency(PriceIncTax())" class="quick-quote-price"><img src='<?php bloginfo('template_url');?>/images/spinner.gif' /></span></div>
                    		<div class="col-md-6"> <a  id="standard-30-button" href="http://www.expressmystuff.co.uk/order?cf=GB&amp;ct=ES&amp;pr=31.59&amp;kg=30&amp;protocol=standard" data-orig="http://www.expressmystuff.co.uk/order?cf=GB&amp;ct=ES&amp;pr=31.59&amp;kg=30&amp;protocol=standard" class="bnbtn btn btn-primary" data-price="">Book Now (30kg)</a></div>
                    	  	
						</div>
						<div class="row" style="padding-bottom:15px;">
                    	 	<div class="col-md-6"><span class="quick-quote-price-weight" style="width:105px;">over 30kg:</span> <span id='data-over30-standard' data-bind="text: Localise.formatCurrency(PriceIncTax())" class="quick-quote-price"><img src='<?php bloginfo('template_url');?>/images/spinner.gif' /></span> + <span class="quick-quote-price">£2.20 </span>per additional kg</div>
                    		<div class="col-md-6"> <a  id="standard-over30-button" href="http://www.expressmystuff.co.uk/order?cf=GB&amp;ct=ES&amp;pr=31.59&amp;kg=30&amp;protocol=standard" data-orig="http://www.expressmystuff.co.uk/order?cf=GB&amp;ct=ES&amp;pr=31.59&amp;kg=30&amp;protocol=standard" class="bnbtn btn btn-primary" data-price="">Book Now (over 30kg)</a></div>
							
						</div>
						
						
						<!-- END INNER ROW -->
						
						                      
                    </div>
                    <div class="col-md-3 standard-results">
                        	
                        
                    </div>
                     <div class='standard-results' style="height:1px; background-color:#000; clear:both; width:100%; margin:15px 0;"></div>
		
				    <div class="col-md-3">
                        <div class="orangetext">Express </div>
                        <div>2-3 working days</div>
                       
                    </div>
                    <div class="col-md-9">
                                        <div class="row" style="padding-bottom:15px;">
                 
                    	<div class="col-md-6"><span class="quick-quote-price-weight" style="width:105px;">Up to 5kg:</span> <span data-bind="text: Localise.formatCurrency(PriceIncTax())" class="quick-quote-price" id='data-5-express'><img src='<?php bloginfo('template_url');?>/images/spinner.gif' /></span></div>
                    	<div class="col-md-6"> <a  id="express-5-button" href="http://www.expressmystuff.co.uk/order?cf=GB&amp;ct=ES&amp;pr=44.43&amp;kg=5&amp;protocol=express" data-orig="http://www.expressmystuff.co.uk/order?cf=GB&amp;ct=ES&amp;pr=44.43&amp;kg=5&amp;protocol=express" class="bnbtn btn btn-primary" data-price="">Book Now (5kg)</a></div>
                    	</div><!-- END INNER ROW -->
                    	<div class="row" style="padding-bottom:15px;">
                    	<div class="col-md-6"><span class="quick-quote-price-weight" style="width:105px;">Up to 15kg:</span> <span data-bind="text: Localise.formatCurrency(PriceIncTax())" class="quick-quote-price" id='data-15-express'><img src='<?php bloginfo('template_url');?>/images/spinner.gif' /></span></div>
                    		<div class="col-md-6"> <a id="express-15-button"  href="http://www.expressmystuff.co.uk/order?cf=GB&amp;ct=ES&amp;pr=92.46&amp;kg=15&amp;protocol=express" data-orig="http://www.expressmystuff.co.uk/order?cf=GB&amp;ct=ES&amp;pr=92.46&amp;kg=15&amp;protocol=express" class="bnbtn btn btn-primary" data-price="">Book Now (15kg)</a></div>
						</div><!-- END INNER ROW -->
                  	    <div class="row" style="padding-bottom:15px;">
                    	<div class="col-md-6"><span class="quick-quote-price-weight" style="width:105px;">Up to 30kg:</span> <span data-bind="text: Localise.formatCurrency(PriceIncTax())" class="quick-quote-price" id='data-30-express'><img src='<?php bloginfo('template_url');?>/images/spinner.gif' /></span></div>
                    		<div class="col-md-6"> <a id="express-30-button" href="http://www.expressmystuff.co.uk/order?cf=GB&amp;ct=ES&amp;pr=136.02&amp;kg=30&amp;protocol=express" data-orig="http://www.expressmystuff.co.uk/order?cf=GB&amp;ct=ES&amp;pr=136.02&amp;kg=30&amp;protocol=express" class="bnbtn btn btn-primary" data-price="">Book Now (30kg)</a></div>
						</div>
						<div class="row" style="padding-bottom:15px;">
                    	<div class="col-md-6"><span class="quick-quote-price-weight" style="width:105px;">over 30kg:</span> <span data-bind="text: Localise.formatCurrency(PriceIncTax())" class="quick-quote-price" id='data-over30-express'><img src='<?php bloginfo('template_url');?>/images/spinner.gif' /></span> + <span class="quick-quote-price">£4.40 </span>per additional kg</div>
                    		<div class="col-md-6"> <a id="express-over30-button" href="http://www.expressmystuff.co.uk/order?cf=GB&amp;ct=ES&amp;pr=136.02&amp;kg=30&amp;protocol=express" data-orig="http://www.expressmystuff.co.uk/order?cf=GB&amp;ct=ES&amp;pr=136.02&amp;kg=30&amp;protocol=express" class="bnbtn btn btn-primary" data-price="">Book Now (over 30kg)</a></div>
						</div>
						
						<!-- END INNER ROW -->
						                      
                    </div>
                    <div class="col-md-3">
                        	
                        
                 
		
		</div>
                   
                   
                   
                </div>
                
				</div><!-- END QUOTE WRAP -->
             	
                <div class="mt-2 alert alert-danger" role="alert" id="error_msg" style="display:none">
                    <strong>Error!</strong> Please First Select Collection Country and Destination Country to proceed.
                </div>
				
				<div class="mt-2 alert alert-info" role="alert">
                    <strong>Important!</strong> If sending from an address with a daily DHL Packet collection, do not hand the bag to that driver.
                </div>
            </div>
    </div>

	<div class="entry-content">
    	<div class="container">
            <div class="row">
            	<h3 class="orangebgheader">What You Can Send</h3>
                <table class="table table-responsive table-hover">
                	<tbody>
                     
                        
                        <?php while(have_rows('info_table')):the_row(); $toc = get_sub_field('tick_or_cross'); ?>
                         <tr>
                            <th scope="row" class="hidden-sm-down <?php if($toc == 'Cross'):?>bigredcross<?php else:?>biggreencheck<?php endif;?>"><i class="fa fa-<?php if($toc == 'Cross'):?>times<?php else:?>check<?php endif;?>" aria-hidden="true"></i></th>
                            <td><?php the_sub_field('title');?></td>
                            <td><?php the_sub_field('text');?></td>
                        </tr>
                        
                        <?php endwhile;?>
                        
                	</tbody>
                </table>
            </div>
        </div>
	</div><!-- .entry-content -->
	
	<footer class="entry-footer">
    
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->

<style type="text/css">
    
    .error_color_collection {
        color: #fb3c00;
   
    }
     .success_color_collection {
     color: #29abe2;
    }

    .error_color_destination {
        color: #fb3c00;
   
    }
     .success_color_destination {
     color: #29abe2;
    }
</style>
