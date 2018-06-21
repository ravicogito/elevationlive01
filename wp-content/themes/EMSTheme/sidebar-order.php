<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>
$(document).ready(function(){
   $("#apply").click(function() {
	var promo_code = $("#promo_code").val(); 
	//var price 	   = <?php echo $_GET['pr']; ?>	
	var myString   = $("#ajax-price").text();
	var price =  myString.substr(1);
	$.post(ajax_url, {
    	promo_code: promo_code, 
		price: price, 		
		action: "do_promocode_chk"
	}, function(response) {
		  if(response['process'] == "success") {
	    	$("#ajax-price").html('£'+response['price']); 
	    	$("#package_price").val(response['price']); 
            if(response['discount']!=null)	
			{
				$("#promodisSec").html(response['discount']+'%'+' ');
				$("#promoMsgSec").html(response['msg']);  				
			}
            else{
				$("#promodisSec").html('Wrong Promocode.'); 
                $("#promoMsgSec").html('');  				
			}			
			 
			 
		   } 		
	   }, "json");		   
   })
          
})
</script>

<?php
/**
 * The right sidebar containing the main widget area.
 *
 * @package understrap
 */

	global $Quote;

	if(isset($_POST['quote'])):

	$quotefields = $_POST['quote'];
	
		

		$from_place = $Quote->get_place($quotefields['from_country']);
		$to_place = $Quote->get_place($quotefields['to_country']);

		

		$from_city = $from_place->city;
		$from_postcode = $from_place->postcode;
		$from_country = $from_place->country_code;

		$to_city = $to_place->city;
		$to_postcode = $to_place->postcode;
		$to_country = $to_place->country_code;

endif;

if(isset($_GET['cf'])): $from_country = $_GET['cf']; endif;
if(isset($_GET['ct'])): $to_country = $_GET['ct']; endif;
?>

<div class="col-md-3 widget-area" id="right-sidebar" role="complementary">



	<div id="basket">
    	<h2 class="orangebgheader">Order Summary</h2>
        <div id="ordersummary" class="p-2">
        	<div class="row" id='tofromcc' <?php if(!isset($_POST['quote']) && !isset($_GET['cf'])):?>style='display:none;'<?php endif;?>>
            	<div class="col">
                    <span class="order-summary-value" id="cc"><?php echo $from_country;?> <i class="fa fa-arrow-right orangetext"></i> </span>
                    <span class="order-summary-value" id="dc"><?php echo $to_country;?></span>
                </div>
            </div>
            <div class="row mt-2">
            	<div class="col">
            		<div class="order-summary-label orangetext">Items</div>
                    <div class="order-summary-value pl-2" id="ajax-items-list">
                       
                    </div>
                </div>
            </div>
            <div class="row mt-2">
            	<div class="col">
            		<div class="order-summary-label orangetext">Add-ons</div>
                    <table class="table table-sm order-summary-addons">
                        <tbody>
                            <tr id='list-insurance-amount'>
								<td><span id='selected-insurance-price'>FREE</span></td>
                                <td class="text-muted"><?php echo $Quote->get_currency_symbol();?><span id='selected-insurance-amount'>60.00</span> Cover</td>
                            </tr>
                            
                            <tr id='additional-weight-value' style='display:none;'>
								<td>Addtional Weight:</td>
                                <td class="text-muted"> <span id='additional-weight'></span>kg(s)</td>
                            </tr>
							
							<tr id='additional-weight-amount' style='display:none;'>
								<td>Amount: </td>
                                <td class="text-muted"><?php echo $Quote->get_currency_symbol();?><span id='selected-additional-amount'></span></td>
                            </tr>
                            
                            <tr id='list-cancellation-cover' style='display:none;'>
                            	
                            	<td id='sidebar-cancellation-cover-price'>£4.00</td>
                            	<td class="text-muted">Cancellation and Change Cover</td>
                            	
                            </tr>
                            
                           
                            
                             <tr id='list-post-labels' style='display:none;'>
                            	
                            	<td id='sidebar-post-labels-price'>£5.00</td>
                            	<td class="text-muted">Post Labels</td>
                            	
                            </tr>
                            
                             <tr id='list-insurance-amount'>
								<td><span id='selected-insurance-price'>Promo code</span></td>
                                <td class="text-muted"><input type="text" name="promocode" id="promo_code" size="4" /></td>
                            </tr>
                            
                             <tr id='list-insurance-amount'>
								<td></td>
                                <td class="text-muted"><input type="button" name="apply" id="apply" value="Apply"/></td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row text-center">
            	<div class="col">
            	    <span id="promodisSec"></span><span id="promoMsgSec"></span>
                	<div class="order-summary-label order-summary-total orangetext"><strong>Total <img src='<?php bloginfo('template_url');?>/images/spinner.gif' id='spinner' style='display:none;' /></strong></div>
                    <div class="order-summary-value order-summary-total"><span  id="ajax-price"><?php echo $Quote->get_currency_symbol();?><?php if(isset($_GET['pr'])): echo number_format($_GET['pr'], 2); else: echo '0.00'; endif;?></span> </div>
                    <div class="order-summary-total-to-pay"><?php echo $Quote->get_currency_symbol();?><?php echo number_format($quotefields['quoted_price'], 2);?></div>
                    <div class="order-summary-currency-code text-muted"><?php echo $Quote->currency;?></div>
            	</div>
            </div>
        </div>
    </div>
</div><!-- #secondary -->

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script
	src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
	integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
	crossorigin="anonymous">
</script>
<script>
	$( function() {
		$( "#datepicker" ).datepicker({
			numberOfMonths: 2,
			showButtonPanel: true,
			dateFormat: 'DD dx MM, yy',
		
			minDate: 1,
    maxDate: '+10d',
			beforeShowDay: $.datepicker.noWeekends,
			onSelect: function(dateText, inst) {
				var arrOrd = new Array('0','st','nd','rd','th','th','th','th','th','th','th','th','th','th','th','th','th','th','th','th','th','st','nd','rd','th','th','th','th','th','th','th','st');
				var day = Number(inst.selectedDay);
				var suffix = arrOrd[day];       
				$(this).val($(this).val().replace(inst.selectedDay+"x",inst.selectedDay+suffix));
				$('#collectiondate').text($(this).val().replace(inst.selectedDay+"x",inst.selectedDay+suffix));
				if(inst.selectedMonth < 10){ var smi = "0"; } else { var smi = ""; }
				$('#shipping-date').val(inst.selectedYear+"-"+smi+inst.selectedMonth+"-"+inst.selectedDay);
				
			}
		});
	});
	
	
	$( function() {
		$( "#datepicker2" ).datepicker({
			numberOfMonths: 2,
			showButtonPanel: true,
			dateFormat: 'DD dx MM, yy',
			minDate: 1,
    maxDate: '+10d',
			beforeShowDay: $.datepicker.noWeekends,
			onSelect: function(dateText, inst) {
				var arrOrd = new Array('0','st','nd','rd','th','th','th','th','th','th','th','th','th','th','th','th','th','th','th','th','th','st','nd','rd','th','th','th','th','th','th','th','st');
				var day = Number(inst.selectedDay);
				var suffix = arrOrd[day];       
				$(this).val($(this).val().replace(inst.selectedDay+"x",inst.selectedDay+suffix));
				$('#returndate').text($(this).val().replace(inst.selectedDay+"x",inst.selectedDay+suffix));
				if(inst.selectedMonth < 10){ var smi = "0"; } else { var smi = ""; }
				$('#return-date').val(inst.selectedYear+"-"+smi+inst.selectedMonth+"-"+inst.selectedDay);
				
			}
		});
	});
	

	$=jQuery.noConflict();
	window.onload=function(){
		var sidebar = $('#right-sidebar'),
			basket = $('#basket'),
			startPosition = sidebar.offset().top,
			stopPosition = $('#wrapper-footer').offset().top - basket.outerHeight();
		
		$(document).scroll(function () {
			if ( $(window).width() > 1200) {  
				//stick nav to top of page
				var y = $(this).scrollTop();
			
				if (y > startPosition) {
					basket.addClass('stickysticky');
					if (y > stopPosition) {
						basket.css('top', stopPosition - y);
					} else {
						basket.css('top', 0);
					}
				} else {
					basket.removeClass('stickysticky');
				} 
			}
		});
	}
	
	$(document).ready(function(){
		$('#addressType').on('change', function() {
			if ( this.value == '2' ){
				$("#ifApartment").show();
			}
			else {
				$("#ifApartment").hide();
			}
			if ( this.value == '3' ){
				$("#ifBusiness").show();
			}
			else {
				$("#ifBusiness").hide();
			}
			if ( this.value == '4' ){
				$("#ifUni").show();
			}
			else {
				$("#ifUni").hide();
			}
			if ( this.value == '5' ){
				$("#ifHotel").show();
				$("#ifHotelName").show();
				$("#notHotelName").hide();
			}
			else {
				$("#ifHotel").hide();
				$("#ifHotelName").hide();
				$("#notHotelName").show();
			}
		});
		$('#addressType2').on('change', function() {
			if ( this.value == '2' ){
				$("#ifApartment2").show();
			}
			else {
				$("#ifApartment2").hide();
			}
			if ( this.value == '3' ){
				$("#ifBusiness2").show();
			}
			else {
				$("#ifBusiness2").hide();
			}
			if ( this.value == '4' ){
				$("#ifUni2").show();
			}
			else {
				$("#ifUni2").hide();
			}
			if ( this.value == '5' ){
				$("#ifHotel2").show();
				$("#ifHotelName2").show();
				$("#notHotelName2").hide();
			}
			else {
				$("#ifHotel2").hide();
				$("#ifHotelName2").hide();
				$("#notHotelName2").show();
			}
		});
		$('#addressType3').on('change', function() {
			if ( this.value == '5' ){
				$("#ifHotelName3").show();
				$("#notHotelName3").hide();
			}
			else {
				$("#ifHotelName3").hide();
				$("#notHotelName3").show();
			}
		});
		$('#itemType').on('change', function() {
			if ( this.value == '2' ){
				$("#ifBox").show();
			}
			else {
				$("#ifBox").hide();
			}
		});
		$('#yesBuzzer').on('change', function() {
			$("#ifBuzzer").show();
			$("#radiobuzzer").hide();
		});
		$('#yesBuzzer2').on('change', function() {
			$("#ifBuzzer2").show();
			$("#radiobuzzer2").hide();
		});
		$('#labelHolders').on('change', function() {
			if ( this.value == '2' ){
				$("#labelHoldersAlert").show();
			}
			else {
				$("#labelHoldersAlert").hide();
			}
		});
		$('#postMyLabels').on('change', function() {
			if ( this.value == '2' ){
				$("#postMyLabelsAlert").show();
			}
			else {
				$("#postMyLabelsAlert").hide();
			}
		});
		$('#labelHolders, #postMyLabels').on('change', function() {
			if ( $('#labelHolders').val() == '1' || $('#postMyLabels').val() == '1' ){
				$("#labelPostage").show();
			}
			else {
				$("#labelPostage").hide();
			}
		});
		$('#NewAddy').on('change', function() {
			$("#postageLabelsForm").show();
		});
		$('#ExistingAddy').on('change', function() {
			$("#postageLabelsForm").hide();
		});
		$('#accountYes').on('change', function() {
			$("#accountYesForm").show();
			$("#accountAlreadyForm").hide();
		});
		$('#accountAlready').on('change', function() {
			$("#accountYesForm").hide();
			$("#accountAlreadyForm").show();
		});
		
		
		
		<?php if(isset($_GET['cf'])):?>
		$('#custPhone_CountryCode, #custOrigin_CountryIso').val('<?php echo $_GET['cf'];?>');
		<?php endif;?>
		
		<?php if(isset($_GET['ct'])):?>
		$('#custPhone_CountryCode2, #custOrigin_CountryIso2').val('<?php echo $_GET['ct'];?>');
		<?php endif;?>
		
		<?php if(isset($_GET['fpc'])):?>
		$('#custPostcode').val('<?php echo $_GET['fpc'];?>');
		<?php endif;?>
		
		<?php if(isset($_GET['tpc'])):?>
		$('#custPostcode2').val('<?php echo $_GET['tpc'];?>');
		<?php endif;?>
		
		$('.form-control').keyup(function(){
			
			if($(this).val() != ''){ $(this).removeClass('errors');}
		});
		
		$('.form-control').change(function(){
			
			if($(this).val() != ''){ $(this).removeClass('errors');}
		});
		
		<?php if(isset($_GET['xstep'])):?>
		
			$('#orderStep1, #orderStep2, #orderStep3, #orderStep4').hide();
		
			$('#orderStep<?php echo $_GET['xstep'];?>').show();
		
		<?php endif;?>
		
	});
</script>