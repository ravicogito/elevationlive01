<?php
/**
 * The destination page sidebar
 *
 * @package understrap
 */

if ( ! is_active_sidebar( 'destinations-sidebar' ) ) {
	return;
}

// when both sidebars turned on reduce col size to 3 from 4.
$sidebar_pos = get_theme_mod( 'understrap_sidebar_position' );
?>

<?php if ( 'both' === $sidebar_pos ) : ?>
<div class="col-md-3 widget-area destinations-sidebar whitetextbox" id="right-sidebar" role="complementary">
	<?php else : ?>
<div class="col-md-4 widget-area destinations-sidebar whitetextbox" id="right-sidebar" role="complementary">
	<?php endif; ?>
    <?php global $Quote; $places = $Quote->get_places(); ?>
<div id="quote-widget" class="col-md-12 mb-4">
    <div class="row">
       <?php if(get_field('country_code') != ''):?>
       <input type='hidden' name='quote-Destination-CountryIso' id='quote-Destination_CountryIso' value='<?php echo get_field('country_code');?>' />
       <?php endif;?>
        <label for="Origin_CountryIso">Where are you sending from?</label>
         <select id="quote-Origin_CountryIso" name="Origin_CountryIso" style="width:100%" class="custom-select" placeholder="select a country" autocomplete="false" data-bind="value: CountryIso" data-val="true" data-val-required="Country is required." tabindex="-1" title="Send From">
                    <optgroup label="Most Popular">
                       <?php foreach($places as $place):?>
                       	<?php if($place->is_popular == 1):?>
                       		<option value='<?php echo $place->country_code;?>'><?php echo $place->name;?></option>
                       	<?php endif;?>
                       <?php endforeach;?>
                    </optgroup>
                    <optgroup label="A-Z">
                        <?php foreach($places as $place):?>
                      
                       		<option <?php if($from_place->id == $place->id):?>selected='selected'<?php endif;?> value='<?php echo $place->country_code;?>'><?php echo $place->name;?></option>
                       	
                       <?php endforeach;?>
                    </optgroup>
                </select>
            
        <label style="width:100%;">Your Journey</label>
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
    </div>  
    <div class="row greybgheader">
        <div class="col-12">
            Quick Quote
        </div>
        
    </div>
    <div class="row whitetextboxlp" style="border: solid 1px #333; border-top: 0;font-size:0.9rem">	
      <div id='miniload' style='text-align:center; padding:40px 20px; color:#fb3c00; font-size: 20px;'><img src='<?php bloginfo('template_url');?>/images/miniload.gif' /><h4 style='margin-top:20px;'>Finding your prices, please wait...</h4></div>
       <div id="ajax-show-info"></div>
    </div>
  
</div>

<?php dynamic_sidebar( 'destinations-sidebar' ); ?>

</div><!-- #secondary -->
