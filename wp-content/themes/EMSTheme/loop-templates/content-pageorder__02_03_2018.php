<?php
/**
 * Partial template for content in pageorder.php
 *
 * @package understrap
 */

$ems_session = get_ems_session();



?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
<input type='hidden' id='ems-base-url' value='<?php bloginfo('home');?>' />
<input type='hidden' name='ems_session' id='ems-session' value='<?php echo $ems_session;?>' />

<?php if(isset($_GET['cf'])):
	?><input type='hidden' id='sent-country-from' value='<?php echo $_GET['cf'];?>' />
	<?php
	endif;?>
	
	<?php if(isset($_GET['ct'])):
	?><input type='hidden' id='sent-country-to' value='<?php echo $_GET['ct'];?>' />
	<?php
	endif;?>

<?php
	
	$quotefields = $_POST['quote'];
	
		global $Quote;

		$from_place = $Quote->get_place($quotefields['from_country']);
		$to_place = $Quote->get_place($quotefields['to_country']);

		

		
		
		if($quotefields['from_postcode'] != ''):
		$from_postcode = $quotefields['from_postcode'];
		else:
		$from_postcode = $from_place->postcode;
		endif;
		
		if($quotefields['to_postcode'] != ''):
		$to_postcode = $quotefields['to_postcode'];
		else:
		$to_postcode = $to_place->postcode;
		endif;
	
		if($quotefields['from_city'] != ''):
		$from_city = $quotefields['from_city'];
		else:
		$from_city = $from_place->city;
		endif;
	
		if($quotefields['to_city'] != ''):
		$to_city = $quotefields['to_city'];
		else:
		$to_city = $to_place->city;
		endif;
	

			
		$from_country = $from_place->country_code;
		$to_country = $to_place->country_code;
	
	?>
	
	<script type='text/javascript'>
		var ajax_url = '<?php bloginfo('home');?>/wp-admin/admin-ajax.php';
		var from_country = '<?php echo $from_country;?>';
		var to_country = '<?php echo $to_country;?>';
		var from_postcode = '<?php echo $from_postcode;?>';
		var to_postcode = '<?php echo $to_postcode;?>';
		var from_city = '<?php echo $from_city;?>';
		var to_city = '<?php echo $to_city;?>';
	
		var units_type = 'metric';
		
		var grand_total = 0;
		
	</script>
	
	
	<?php
	
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
	
	
	if($Quote->currency != 'GBP'):
	
		$currency = $Quote->currency;
	
		$one_pound = currencyConverter('GBP', $currency, 1);
	?>
	<input type='hidden' id='exchange-rate' value='<?php echo $one_pound;?>' />
	<?php
	endif;
	
	?>

	<header class="entry-header whitetextbox">
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item order-progress"><a id='pill-step-1' data-step='1' class="nav-link active"  href="Javascript:void(0);">Step 1</a></li>
            <li class="nav-item order-progress"><a id='pill-step-2' data-step='2' class="nav-link disabled" href="Javascript:void(0);">Step 2</a></li>
            <li class="nav-item order-progress"><a id='pill-step-3' data-step='3' class="nav-link disabled"  href="Javascript:void(0);">Step 3</a></li>
            <li class="nav-item order-progress"><a id='pill-step-4' data-step='4' class="nav-link disabled"  href="Javascript:void(0);">Step 4</a></li>
        </ul>
	</header><!-- .entry-header -->
	<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
	<div class="entry-content">
        <div class="tab-content">
         
        	<section class="tab-pane active" id="orderStep1" role="tabpanel">
               
               <!-- START STEP 1 -->
                 <?php  //echo the_field( "post_labels_price", get_the_ID());   ?>
                
                <div class="lightgreytextboxlp">
                    
                    <h5>Addresses</h5>
                    <div class="row">
                    	<div class="col-sm-6 mt-3">
                       	
                       	<div class='col-1-to-hide'>
                        	<div class="whitetextboxlp">
                        	<h6><strong>Enter The Collection Address</strong></h6>
                            <form id="collectionAddressForm" class="pt-2" action="" method="post">
                            	<div class="form-group">
                                    <label for="addressType">Type Of Address</label>
                                    <select id="addressType" class="form-control custom-select req1">
                                        <option value="" selected>Please Select</option>
                                        <option value="1">House</option>
                                        <option value="2">Apartment/Flat</option>
                                        <option value="3">Business</option>
                                        <option value="4">University Accommodation</option>
                                        <option value="5">Hotel</option>
                                        <option value="6">Other</option>
                                    </select>
                                    <div id="ifApartment" style="display:none;" class="alert alert-info" role="alert">
                                        <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong> <small>Please ensure your buzzer is working and is clearly labelled. When the driver arrives please meet them on the ground floor / lobby to hand over or receive your items.</small>
                                    </div>
                                    <div id="ifBusiness" style="display:none;" class="alert alert-info" role="alert">
                                        <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong> <small>Unless the building is split with different floors for different businesses items will normally be collected from the main ground floor reception.</small>
                                    </div>
                                    <div id="ifUni" style="display:none;" class="alert alert-info" role="alert">
                                        <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong> <small>Please ensure the Uni address provided is a standard address that receives deliveries, drivers cannot access individual dorms or call upon arrival. If you're in secure private halls without a reception, ask your Uni where to have items delivered to or collected from, they will beable to advise you of a reception or post room. If sending to / from a porters' lodge include this in the address.</small>
                                    </div>
                                    <div id="ifHotel" style="display:none;" class="alert alert-info" role="alert">
                                        <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong> <small>If sending to or from a Hotel please ensure the “Guest Name" is in the format: “Mr Smith c/o The Reception” or “Mr Smith c/o The Concierge”. When leaving your bags for collection please inform the hotel that a courier will be collecting them on your behalf.</small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="notHotelName" for="custName">Full Name</label>
                                    <label id="ifHotelName" style="display:none;" for="custName">Contact or Guest Name</label>
                                    <input id="custName" type="text" class="form-control req1">
                                </div>
                                <div class="form-group">
                                	<label for="custPhone">Phone Number of Contact</label>
                                    <div class="row no-gutters">
                                    	<div class="col-3">
                                            <select id="custPhone_CountryCode" name="custPhone_CountryCode" class="form-control custom-select" placeholder="" autocomplete="false" data-bind="value: CountryCode" tabindex="-1" title="">
                                                <option value=""></option>
                                                <option value="AF" data-alt="" data-code="+93">Afghanistan</option>                
                                                <option value="AL" data-alt="" data-code="+355">Albania</option>                
                                                <option value="DZ" data-alt="" data-code="+213">Algeria</option>                
                                                <option value="AS" data-alt="" data-code="+1 684">American Samoa</option>                
                                                <option value="AD" data-alt="" data-code="+376">Andorra</option>                
                                                <option value="AO" data-alt="" data-code="+244">Angola</option>                
                                                <option value="AI" data-alt="" data-code="+1 264">Anguilla</option>                
                                                <option value="AQ" data-alt="" data-code="+672">Antarctica</option>                
                                                <option value="AG" data-alt="" data-code="+1 268">Antigua and Barbuda</option>                
                                                <option value="AR" data-alt="" data-code="+54">Argentina</option>                
                                                <option value="AM" data-alt="" data-code="+374">Armenia</option>                
                                                <option value="AW" data-alt="" data-code="+297">Aruba</option>                
                                                <option value="AU" data-alt="" data-code="+61">Australia</option>                
                                                <option value="AT" data-alt="" data-code="+43">Austria</option>                
                                                <option value="AZ" data-alt="" data-code="+994">Azerbaijan</option>                
                                                <option value="BS" data-alt="" data-code="+1 242">Bahamas</option>                
                                                <option value="BH" data-alt="" data-code="+973">Bahrain</option>                
                                                <option value="BD" data-alt="" data-code="+880">Bangladesh</option>                
                                                <option value="BB" data-alt="" data-code="+1 246">Barbados</option>                
                                                <option value="BY" data-alt="" data-code="+375">Belarus</option>                
                                                <option value="BE" data-alt="" data-code="+32">Belgium</option>                
                                                <option value="BZ" data-alt="" data-code="+501">Belize</option>                
                                                <option value="BJ" data-alt="" data-code="+229">Benin</option>                
                                                <option value="BM" data-alt="" data-code="+1 441">Bermuda</option>                
                                                <option value="BT" data-alt="" data-code="+975">Bhutan</option>                
                                                <option value="BO" data-alt="" data-code="+591">Bolivia</option>                
                                                <option value="BA" data-alt="" data-code="+387">Bosnia and Herzegovina</option>                
                                                <option value="BW" data-alt="" data-code="+267">Botswana</option>                
                                                <option value="BR" data-alt="" data-code="+55">Brazil</option>                
                                                <option value="BN" data-alt="" data-code="+673">Brunei Darussalam</option>                
                                                <option value="BG" data-alt="" data-code="+359">Bulgaria</option>                
                                                <option value="BF" data-alt="" data-code="+226">Burkina Faso</option>                
                                                <option value="BI" data-alt="" data-code="+257">Burundi</option>                
                                                <option value="KH" data-alt="" data-code="+855">Cambodia</option>                
                                                <option value="CM" data-alt="" data-code="+237">Cameroon</option>                
                                                <option value="CA" data-alt="" data-code="+1">Canada</option>                
                                                <option value="CV" data-alt="" data-code="+238">Cape Verde</option>                
                                                <option value="KY" data-alt="" data-code="+1 345">Cayman Islands</option>                
                                                <option value="CF" data-alt="" data-code="+236">Central African Republic</option>                
                                                <option value="TD" data-alt="" data-code="+235">Chad</option>                
                                                <option value="CL" data-alt="" data-code="+56">Chile</option>                
                                                <option value="CN" data-alt="" data-code="+86">China</option>                
                                                <option value="CX" data-alt="" data-code="+61">Christmas Island</option>                
                                                <option value="CC" data-alt="" data-code="+61">Cocos (Keeling) Islands</option>                
                                                <option value="CO" data-alt="" data-code="+57">Colombia</option>                
                                                <option value="KM" data-alt="" data-code="+269">Comoros</option>                
                                                <option value="CG" data-alt="" data-code="+242">Congo</option>                
                                                <option value="CD" data-alt="" data-code="+243">Congo, the Democratic Republic of the</option>                
                                                <option value="CK" data-alt="" data-code="+682">Cook Islands</option>                
                                                <option value="CR" data-alt="" data-code="+506">Costa Rica</option>                
                                                <option value="CI" data-alt="" data-code="+225">Cote D'Ivoire</option>                
                                                <option value="HR" data-alt="" data-code="+385">Croatia</option>                
                                                <option value="CU" data-alt="" data-code="+53">Cuba</option>                
                                                <option value="CY" data-alt="" data-code="+357">Cyprus</option>                
                                                <option value="CZ" data-alt="" data-code="+420">Czech Republic</option>                
                                                <option value="DK" data-alt="" data-code="+45">Denmark</option>                
                                                <option value="DJ" data-alt="" data-code="+253">Djibouti</option>                
                                                <option value="DM" data-alt="" data-code="+1 767">Dominica</option>                
                                                <option value="DO" data-alt="" data-code="+1 809">Dominican Republic</option>                
                                                <option value="EC" data-alt="" data-code="+593">Ecuador</option>                
                                                <option value="EG" data-alt="" data-code="+20">Egypt</option>                
                                                <option value="SV" data-alt="" data-code="+503">El Salvador</option>                
                                                <option value="GQ" data-alt="" data-code="+240">Equatorial Guinea</option>                
                                                <option value="ER" data-alt="" data-code="+291">Eritrea</option>                
                                                <option value="EE" data-alt="" data-code="+372">Estonia</option>                
                                                <option value="ET" data-alt="" data-code="+251">Ethiopia</option>                
                                                <option value="FK" data-alt="" data-code="+500">Falkland Islands (Malvinas)</option>                
                                                <option value="FO" data-alt="" data-code="+298">Faroe Islands</option>                
                                                <option value="FJ" data-alt="" data-code="+679">Fiji</option>                
                                                <option value="FI" data-alt="" data-code="+358">Finland</option>                
                                                <option value="FR" data-alt="" data-code="+33">France</option>                
                                                <option value="PF" data-alt="" data-code="+689">French Polynesia</option>                
                                                <option value="GA" data-alt="" data-code="+241">Gabon</option>                
                                                <option value="GM" data-alt="" data-code="+220">Gambia</option>                
                                                <option value="GE" data-alt="" data-code="+995">Georgia</option>                
                                                <option value="DE" data-alt="" data-code="+49">Germany</option>                
                                                <option value="GH" data-alt="" data-code="+233">Ghana</option>                
                                                <option value="GI" data-alt="" data-code="+350">Gibraltar</option>                
                                                <option value="GR" data-alt="" data-code="+30">Greece</option>                
                                                <option value="GL" data-alt="" data-code="+299">Greenland</option>                
                                                <option value="GD" data-alt="" data-code="+1 473">Grenada</option>                
                                                <option value="GU" data-alt="" data-code="+1 671">Guam</option>                
                                                <option value="GT" data-alt="" data-code="+502">Guatemala</option>                
                                                <option value="GN" data-alt="" data-code="+224">Guinea</option>                
                                                <option value="GW" data-alt="" data-code="+245">Guinea-Bissau</option>                
                                                <option value="GY" data-alt="" data-code="+592">Guyana</option>                
                                                <option value="HT" data-alt="" data-code="+509">Haiti</option>                
                                                <option value="VA" data-alt="" data-code="+39">Holy See (Vatican City State)</option>                
                                                <option value="HN" data-alt="" data-code="+504">Honduras</option>                
                                                <option value="HK" data-alt="" data-code="+852">Hong Kong</option>                
                                                <option value="HU" data-alt="" data-code="+36">Hungary</option>                
                                                <option value="IS" data-alt="" data-code="+354">Iceland</option>                
                                                <option value="IN" data-alt="" data-code="+91">India</option>                
                                                <option value="ID" data-alt="" data-code="+62">Indonesia</option>                
                                                <option value="IR" data-alt="" data-code="+98">Iran, Islamic Republic of</option>                
                                                <option value="IQ" data-alt="" data-code="+964">Iraq</option>                
                                                <option value="IE" data-alt="" data-code="+353">Ireland</option>                
                                                <option value="IL" data-alt="" data-code="+972">Israel</option>                
                                                <option value="IT" data-alt="" data-code="+39">Italy</option>                
                                                <option value="JM" data-alt="" data-code="+1 876">Jamaica</option>                
                                                <option value="JP" data-alt="" data-code="+81">Japan</option>                
                                                <option value="JO" data-alt="" data-code="+962">Jordan</option>                
                                                <option value="KZ" data-alt="" data-code="+7">Kazakhstan</option>                
                                                <option value="KE" data-alt="" data-code="+254">Kenya</option>                
                                                <option value="KI" data-alt="" data-code="+686">Kiribati</option>                
                                                <option value="KW" data-alt="" data-code="+965">Kuwait</option>                
                                                <option value="KG" data-alt="" data-code="+996">Kyrgyzstan</option>                
                                                <option value="LA" data-alt="" data-code="+856">Lao People's Democratic Republic</option>                
                                                <option value="LV" data-alt="" data-code="+371">Latvia</option>                
                                                <option value="LB" data-alt="" data-code="+961">Lebanon</option>                
                                                <option value="LS" data-alt="" data-code="+266">Lesotho</option>                
                                                <option value="LR" data-alt="" data-code="+231">Liberia</option>                
                                                <option value="LY" data-alt="" data-code="+218">Libyan Arab Jamahiriya</option>                
                                                <option value="LI" data-alt="" data-code="+423">Liechtenstein</option>                
                                                <option value="LT" data-alt="" data-code="+370">Lithuania</option>                
                                                <option value="LU" data-alt="" data-code="+352">Luxembourg</option>                
                                                <option value="MO" data-alt="" data-code="+853">Macao</option>                
                                                <option value="MK" data-alt="" data-code="+389">Macedonia, the Former Yugoslav Republic of</option>                
                                                <option value="MG" data-alt="" data-code="+261">Madagascar</option>                
                                                <option value="MW" data-alt="" data-code="+265">Malawi</option>                
                                                <option value="MY" data-alt="" data-code="+60">Malaysia</option>                
                                                <option value="MV" data-alt="" data-code="+960">Maldives</option>                
                                                <option value="ML" data-alt="" data-code="+223">Mali</option>                
                                                <option value="MT" data-alt="" data-code="+356">Malta</option>                
                                                <option value="MH" data-alt="" data-code="+692">Marshall Islands</option>                
                                                <option value="MR" data-alt="" data-code="+222">Mauritania</option>                
                                                <option value="MU" data-alt="" data-code="+230">Mauritius</option>                
                                                <option value="YT" data-alt="" data-code="+262">Mayotte</option>                
                                                <option value="MX" data-alt="" data-code="+52">Mexico</option>                
                                                <option value="FM" data-alt="" data-code="+691">Micronesia, Federated States of</option>                
                                                <option value="MD" data-alt="" data-code="+373">Moldova, Republic of</option>                
                                                <option value="MC" data-alt="" data-code="+377">Monaco</option>                
                                                <option value="MN" data-alt="" data-code="+976">Mongolia</option>                
                                                <option value="MS" data-alt="" data-code="+1 664">Montserrat</option>                
                                                <option value="MA" data-alt="" data-code="+212">Morocco</option>                
                                                <option value="MZ" data-alt="" data-code="+258">Mozambique</option>                
                                                <option value="MM" data-alt="" data-code="+95">Myanmar</option>                
                                                <option value="NA" data-alt="" data-code="+264">Namibia</option>                
                                                <option value="NR" data-alt="" data-code="+674">Nauru</option>                
                                                <option value="NP" data-alt="" data-code="+977">Nepal</option>                
                                                <option value="NL" data-alt="" data-code="+31">Netherlands</option>                
                                                <option value="AN" data-alt="" data-code="+599">Netherlands Antilles</option>                
                                                <option value="NC" data-alt="" data-code="+687">New Caledonia</option>                
                                                <option value="NZ" data-alt="" data-code="+64">New Zealand</option>                
                                                <option value="NI" data-alt="" data-code="+505">Nicaragua</option>                
                                                <option value="NE" data-alt="" data-code="+227">Niger</option>                
                                                <option value="NG" data-alt="" data-code="+234">Nigeria</option>                
                                                <option value="NU" data-alt="" data-code="+683">Niue</option>                
                                                <option value="KP" data-alt="" data-code="+850">North Korea</option>                
                                                <option value="MP" data-alt="" data-code="+1 670">Northern Mariana Islands</option>                
                                                <option value="NO" data-alt="" data-code="+47">Norway</option>                
                                                <option value="OM" data-alt="" data-code="+968">Oman</option>                
                                                <option value="PK" data-alt="" data-code="+92">Pakistan</option>                
                                                <option value="PW" data-alt="" data-code="+680">Palau</option>                
                                                <option value="PA" data-alt="" data-code="+507">Panama</option>                
                                                <option value="PG" data-alt="" data-code="+675">Papua New Guinea</option>                
                                                <option value="PY" data-alt="" data-code="+595">Paraguay</option>                
                                                <option value="PE" data-alt="" data-code="+51">Peru</option>                
                                                <option value="PH" data-alt="" data-code="+63">Philippines</option>                
                                                <option value="PN" data-alt="" data-code="+870">Pitcairn</option>                
                                                <option value="PL" data-alt="" data-code="+48">Poland</option>                
                                                <option value="PT" data-alt="" data-code="+351">Portugal</option>                
                                                <option value="PR" data-alt="" data-code="+1">Puerto Rico</option>                
                                                <option value="QA" data-alt="" data-code="+974">Qatar</option>                
                                                <option value="RO" data-alt="" data-code="+40">Romania</option>                
                                                <option value="RU" data-alt="" data-code="+7">Russian Federation</option>                
                                                <option value="RW" data-alt="" data-code="+250">Rwanda</option>                
                                                <option value="SH" data-alt="" data-code="+290">Saint Helena</option>                
                                                <option value="KN" data-alt="" data-code="+1 869">Saint Kitts and Nevis</option>                
                                                <option value="LC" data-alt="" data-code="+1 758">Saint Lucia</option>                
                                                <option value="PM" data-alt="" data-code="+508">Saint Pierre and Miquelon</option>                
                                                <option value="VC" data-alt="" data-code="+1 784">Saint Vincent and the Grenadines</option>                
                                                <option value="WS" data-alt="" data-code="+685">Samoa</option>                
                                                <option value="SM" data-alt="" data-code="+378">San Marino</option>                
                                                <option value="ST" data-alt="" data-code="+239">Sao Tome and Principe</option>                
                                                <option value="SA" data-alt="" data-code="+966">Saudi Arabia</option>                
                                                <option value="SN" data-alt="" data-code="+221">Senegal</option>                
                                                <option value="SC" data-alt="" data-code="+248">Seychelles</option>                
                                                <option value="SL" data-alt="" data-code="+232">Sierra Leone</option>                
                                                <option value="SG" data-alt="" data-code="+65">Singapore</option>                
                                                <option value="SK" data-alt="" data-code="+421">Slovakia</option>                
                                                <option value="SI" data-alt="" data-code="+386">Slovenia</option>                
                                                <option value="SB" data-alt="" data-code="+677">Solomon Islands</option>                
                                                <option value="SO" data-alt="" data-code="+252">Somalia</option>                
                                                <option value="ZA" data-alt="" data-code="+27">South Africa</option>                
                                                <option value="KR" data-alt="South Korea" data-code="+82">South Korea</option>                
                                                <option value="ES" data-alt="" data-code="+34">Spain</option>                
                                                <option value="LK" data-alt="" data-code="+94">Sri Lanka</option>                
                                                <option value="SD" data-alt="" data-code="+249">Sudan</option>                
                                                <option value="SR" data-alt="" data-code="+597">Suriname</option>                
                                                <option value="SZ" data-alt="" data-code="+268">Swaziland</option>                
                                                <option value="SE" data-alt="" data-code="+46">Sweden</option>                
                                                <option value="CH" data-alt="" data-code="+41">Switzerland</option>                
                                                <option value="SY" data-alt="" data-code="+963">Syrian Arab Republic</option>                
                                                <option value="TW" data-alt="" data-code="+886">Taiwan, Province of China</option>                
                                                <option value="TJ" data-alt="" data-code="+992">Tajikistan</option>                
                                                <option value="TZ" data-alt="" data-code="+255">Tanzania, United Republic of</option>                
                                                <option value="TH" data-alt="" data-code="+66">Thailand</option>                
                                                <option value="TL" data-alt="" data-code="+670">Timor-Leste</option>                
                                                <option value="TG" data-alt="" data-code="+228">Togo</option>                
                                                <option value="TK" data-alt="" data-code="+690">Tokelau</option>                
                                                <option value="TO" data-alt="" data-code="+676">Tonga</option>                
                                                <option value="TT" data-alt="" data-code="+1 868">Trinidad and Tobago</option>                
                                                <option value="TN" data-alt="" data-code="+216">Tunisia</option>                
                                                <option value="TR" data-alt="" data-code="+90">Turkey</option>                
                                                <option value="TM" data-alt="" data-code="+993">Turkmenistan</option>                
                                                <option value="TC" data-alt="" data-code="+1 649">Turks and Caicos Islands</option>                
                                                <option value="TV" data-alt="" data-code="+688">Tuvalu</option>                
                                                <option value="UG" data-alt="" data-code="+256">Uganda</option>                
                                                <option value="GB" data-alt="United Kingdom England Wales Scotland Ireland Northern Ireland NI Great Britain" data-code="+44" selected="selected">UK</option>                
                                                <option value="UA" data-alt="" data-code="+380">Ukraine</option>                
                                                <option value="AE" data-alt="uae" data-code="+971">United Arab Emirates</option>                
                                                <option value="US" data-alt="US USA America" data-code="+1">United States</option>                
                                                <option value="UY" data-alt="" data-code="+598">Uruguay</option>                
                                                <option value="UZ" data-alt="" data-code="+998">Uzbekistan</option>                
                                                <option value="VU" data-alt="" data-code="+678">Vanuatu</option>                
                                                <option value="VE" data-alt="" data-code="+58">Venezuela</option>                
                                                <option value="VN" data-alt="" data-code="+84">Viet Nam</option>                
                                                <option value="VG" data-alt="" data-code="+1 284">Virgin Islands, British</option>                
                                                <option value="VI" data-alt="" data-code="+1 340">Virgin Islands, U.s.</option>                
                                                <option value="WF" data-alt="" data-code="+681">Wallis and Futuna</option>                
                                                <option value="YE" data-alt="" data-code="+967">Yemen</option>                
                                                <option value="ZM" data-alt="" data-code="+260">Zambia</option>                
                                                <option value="ZW" data-alt="" data-code="+263">Zimbabwe</option>                
                                            </select>
                                        </div>
                                        <div class="col-9">
                                            <input id="custPhone" type="text" placeholder="Enter local number" class="form-control">
                                        </div>
                                    </div>
                                    <p><small class="text-muted">Unfortunately drivers cannot telephone upon arrival. Please ensure your address is fully accessible e.g. door bell working</small></p>
                                </div>
                                <div class="form-group">
                                	<div id="radiobuzzer">
                                        <p>Do you have a buzzer (other than your apartment number) or entry code?</p>
                                        <div class="form-check form-check-inline">
                                            <label class="custom-control custom-radio">
                                                <input id="yesBuzzer" name="radioBuzzer" type="radio" class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Yes</span>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="custom-control custom-radio">
                                                <input checked id="noBuzzer" name="radioBuzzer" type="radio" class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">No</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div style="display:none;" id="ifBuzzer">
                                        <label for="custBuzzer">Buzzer/Entry Code</label>
                                        <input id="custBuzzer" type="text" class="form-control" placeholder="Optional">
                                    </div>
                                </div>
                                <div class="form-group">
                                	<label for="custAddress1">Address Line 1</label>
                                    <input id="custAddress1" type="text" class="form-control req1">
                                </div>
                                <div class="form-group">
                                	<label for="custAddress2">Address Line 2</label>
                                    <input id="custAddress2" type="text" class="form-control" placeholder="Optional">
                                </div>
                                <div class="form-group">
                                	<label for="custCity">City</label>
                                    <input id="custCity" type="text" class="form-control" value="<?php echo $_POST['quote']['from_city'];?>">
                                </div>
                                  <div class="form-group">
                                	<label for="custCity">Post/Zip Code</label>
                                    <input id="custPostcode" type="text" class="req1 form-control" <?php if(isset($_GET['fp'])):?>value='<?php echo $_GET['fp'];?>'<?php endif;?> />
                                </div>
                                <div style='display:<?php if($_GET['cf'] == 'US'):?>block;<?php else:?>none;<?php endif;?>' class='form-group' id='state-list-from'>
                                	<label>State:</label>
                                	<select  class='form-control' id='state-from'>
											<option value="AL">Alabama</option>
											<option value="AK">Alaska</option>
											<option value="AZ">Arizona</option>
											<option value="AR">Arkansas</option>
											<option value="CA">California</option>
											<option value="CO">Colorado</option>
											<option value="CT">Connecticut</option>
											<option value="DE">Delaware</option>
											<option value="DC">District Of Columbia</option>
											<option value="FL">Florida</option>
											<option value="GA">Georgia</option>
											<option value="HI">Hawaii</option>
											<option value="ID">Idaho</option>
											<option value="IL">Illinois</option>
											<option value="IN">Indiana</option>
											<option value="IA">Iowa</option>
											<option value="KS">Kansas</option>
											<option value="KY">Kentucky</option>
											<option value="LA">Louisiana</option>
											<option value="ME">Maine</option>
											<option value="MD">Maryland</option>
											<option value="MA">Massachusetts</option>
											<option value="MI">Michigan</option>
											<option value="MN">Minnesota</option>
											<option value="MS">Mississippi</option>
											<option value="MO">Missouri</option>
											<option value="MT">Montana</option>
											<option value="NE">Nebraska</option>
											<option value="NV">Nevada</option>
											<option value="NH">New Hampshire</option>
											<option value="NJ">New Jersey</option>
											<option value="NM">New Mexico</option>
											<option value="NY">New York</option>
											<option value="NC">North Carolina</option>
											<option value="ND">North Dakota</option>
											<option value="OH">Ohio</option>
											<option value="OK">Oklahoma</option>
											<option value="OR">Oregon</option>
											<option value="PA">Pennsylvania</option>
											<option value="RI">Rhode Island</option>
											<option value="SC">South Carolina</option>
											<option value="SD">South Dakota</option>
											<option value="TN">Tennessee</option>
											<option value="TX">Texas</option>
											<option value="UT">Utah</option>
											<option value="VT">Vermont</option>
											<option value="VA">Virginia</option>
											<option value="WA">Washington</option>
											<option value="WV">West Virginia</option>
											<option value="WI">Wisconsin</option>
											<option value="WY">Wyoming</option>
										</select>				
				
                                	
                                </div>
                                <fieldset>
                                    <div class="form-group">
                                      	<label for="Origin_CountryIso">Country</label>
                                      <select id="custOrigin_CountryIso" name="Origin_CountryIso" class="form-control custom-select" placeholder=""  tabindex="-1" title="">
                                                <option value=""></option>
                                                <option value="AF" data-alt="" data-code="+93">Afghanistan</option>                
                                                <option value="AL" data-alt="" data-code="+355">Albania</option>                
                                                <option value="DZ" data-alt="" data-code="+213">Algeria</option>                
                                                <option value="AS" data-alt="" data-code="+1 684">American Samoa</option>                
                                                <option value="AD" data-alt="" data-code="+376">Andorra</option>                
                                                <option value="AO" data-alt="" data-code="+244">Angola</option>                
                                                <option value="AI" data-alt="" data-code="+1 264">Anguilla</option>                
                                                <option value="AQ" data-alt="" data-code="+672">Antarctica</option>                
                                                <option value="AG" data-alt="" data-code="+1 268">Antigua and Barbuda</option>                
                                                <option value="AR" data-alt="" data-code="+54">Argentina</option>                
                                                <option value="AM" data-alt="" data-code="+374">Armenia</option>                
                                                <option value="AW" data-alt="" data-code="+297">Aruba</option>                
                                                <option value="AU" data-alt="" data-code="+61">Australia</option>                
                                                <option value="AT" data-alt="" data-code="+43">Austria</option>                
                                                <option value="AZ" data-alt="" data-code="+994">Azerbaijan</option>                
                                                <option value="BS" data-alt="" data-code="+1 242">Bahamas</option>                
                                                <option value="BH" data-alt="" data-code="+973">Bahrain</option>                
                                                <option value="BD" data-alt="" data-code="+880">Bangladesh</option>                
                                                <option value="BB" data-alt="" data-code="+1 246">Barbados</option>                
                                                <option value="BY" data-alt="" data-code="+375">Belarus</option>                
                                                <option value="BE" data-alt="" data-code="+32">Belgium</option>                
                                                <option value="BZ" data-alt="" data-code="+501">Belize</option>                
                                                <option value="BJ" data-alt="" data-code="+229">Benin</option>                
                                                <option value="BM" data-alt="" data-code="+1 441">Bermuda</option>                
                                                <option value="BT" data-alt="" data-code="+975">Bhutan</option>                
                                                <option value="BO" data-alt="" data-code="+591">Bolivia</option>                
                                                <option value="BA" data-alt="" data-code="+387">Bosnia and Herzegovina</option>                
                                                <option value="BW" data-alt="" data-code="+267">Botswana</option>                
                                                <option value="BR" data-alt="" data-code="+55">Brazil</option>                
                                                <option value="BN" data-alt="" data-code="+673">Brunei Darussalam</option>                
                                                <option value="BG" data-alt="" data-code="+359">Bulgaria</option>                
                                                <option value="BF" data-alt="" data-code="+226">Burkina Faso</option>                
                                                <option value="BI" data-alt="" data-code="+257">Burundi</option>                
                                                <option value="KH" data-alt="" data-code="+855">Cambodia</option>                
                                                <option value="CM" data-alt="" data-code="+237">Cameroon</option>                
                                                <option value="CA" data-alt="" data-code="+1">Canada</option>                
                                                <option value="CV" data-alt="" data-code="+238">Cape Verde</option>                
                                                <option value="KY" data-alt="" data-code="+1 345">Cayman Islands</option>                
                                                <option value="CF" data-alt="" data-code="+236">Central African Republic</option>                
                                                <option value="TD" data-alt="" data-code="+235">Chad</option>                
                                                <option value="CL" data-alt="" data-code="+56">Chile</option>                
                                                <option value="CN" data-alt="" data-code="+86">China</option>                
                                                <option value="CX" data-alt="" data-code="+61">Christmas Island</option>                
                                                <option value="CC" data-alt="" data-code="+61">Cocos (Keeling) Islands</option>                
                                                <option value="CO" data-alt="" data-code="+57">Colombia</option>                
                                                <option value="KM" data-alt="" data-code="+269">Comoros</option>                
                                                <option value="CG" data-alt="" data-code="+242">Congo</option>                
                                                <option value="CD" data-alt="" data-code="+243">Congo, the Democratic Republic of the</option>                
                                                <option value="CK" data-alt="" data-code="+682">Cook Islands</option>                
                                                <option value="CR" data-alt="" data-code="+506">Costa Rica</option>                
                                                <option value="CI" data-alt="" data-code="+225">Cote D'Ivoire</option>                
                                                <option value="HR" data-alt="" data-code="+385">Croatia</option>                
                                                <option value="CU" data-alt="" data-code="+53">Cuba</option>                
                                                <option value="CY" data-alt="" data-code="+357">Cyprus</option>                
                                                <option value="CZ" data-alt="" data-code="+420">Czech Republic</option>                
                                                <option value="DK" data-alt="" data-code="+45">Denmark</option>                
                                                <option value="DJ" data-alt="" data-code="+253">Djibouti</option>                
                                                <option value="DM" data-alt="" data-code="+1 767">Dominica</option>                
                                                <option value="DO" data-alt="" data-code="+1 809">Dominican Republic</option>                
                                                <option value="EC" data-alt="" data-code="+593">Ecuador</option>                
                                                <option value="EG" data-alt="" data-code="+20">Egypt</option>                
                                                <option value="SV" data-alt="" data-code="+503">El Salvador</option>                
                                                <option value="GQ" data-alt="" data-code="+240">Equatorial Guinea</option>                
                                                <option value="ER" data-alt="" data-code="+291">Eritrea</option>                
                                                <option value="EE" data-alt="" data-code="+372">Estonia</option>                
                                                <option value="ET" data-alt="" data-code="+251">Ethiopia</option>                
                                                <option value="FK" data-alt="" data-code="+500">Falkland Islands (Malvinas)</option>                
                                                <option value="FO" data-alt="" data-code="+298">Faroe Islands</option>                
                                                <option value="FJ" data-alt="" data-code="+679">Fiji</option>                
                                                <option value="FI" data-alt="" data-code="+358">Finland</option>                
                                                <option value="FR" data-alt="" data-code="+33">France</option>                
                                                <option value="PF" data-alt="" data-code="+689">French Polynesia</option>                
                                                <option value="GA" data-alt="" data-code="+241">Gabon</option>                
                                                <option value="GM" data-alt="" data-code="+220">Gambia</option>                
                                                <option value="GE" data-alt="" data-code="+995">Georgia</option>                
                                                <option value="DE" data-alt="" data-code="+49">Germany</option>                
                                                <option value="GH" data-alt="" data-code="+233">Ghana</option>                
                                                <option value="GI" data-alt="" data-code="+350">Gibraltar</option>                
                                                <option value="GR" data-alt="" data-code="+30">Greece</option>                
                                                <option value="GL" data-alt="" data-code="+299">Greenland</option>                
                                                <option value="GD" data-alt="" data-code="+1 473">Grenada</option>                
                                                <option value="GU" data-alt="" data-code="+1 671">Guam</option>                
                                                <option value="GT" data-alt="" data-code="+502">Guatemala</option>                
                                                <option value="GN" data-alt="" data-code="+224">Guinea</option>                
                                                <option value="GW" data-alt="" data-code="+245">Guinea-Bissau</option>                
                                                <option value="GY" data-alt="" data-code="+592">Guyana</option>                
                                                <option value="HT" data-alt="" data-code="+509">Haiti</option>                
                                                <option value="VA" data-alt="" data-code="+39">Holy See (Vatican City State)</option>                
                                                <option value="HN" data-alt="" data-code="+504">Honduras</option>                
                                                <option value="HK" data-alt="" data-code="+852">Hong Kong</option>                
                                                <option value="HU" data-alt="" data-code="+36">Hungary</option>                
                                                <option value="IS" data-alt="" data-code="+354">Iceland</option>                
                                                <option value="IN" data-alt="" data-code="+91">India</option>                
                                                <option value="ID" data-alt="" data-code="+62">Indonesia</option>                
                                                <option value="IR" data-alt="" data-code="+98">Iran, Islamic Republic of</option>                
                                                <option value="IQ" data-alt="" data-code="+964">Iraq</option>                
                                                <option value="IE" data-alt="" data-code="+353">Ireland</option>                
                                                <option value="IL" data-alt="" data-code="+972">Israel</option>                
                                                <option value="IT" data-alt="" data-code="+39">Italy</option>                
                                                <option value="JM" data-alt="" data-code="+1 876">Jamaica</option>                
                                                <option value="JP" data-alt="" data-code="+81">Japan</option>                
                                                <option value="JO" data-alt="" data-code="+962">Jordan</option>                
                                                <option value="KZ" data-alt="" data-code="+7">Kazakhstan</option>                
                                                <option value="KE" data-alt="" data-code="+254">Kenya</option>                
                                                <option value="KI" data-alt="" data-code="+686">Kiribati</option>                
                                                <option value="KW" data-alt="" data-code="+965">Kuwait</option>                
                                                <option value="KG" data-alt="" data-code="+996">Kyrgyzstan</option>                
                                                <option value="LA" data-alt="" data-code="+856">Lao People's Democratic Republic</option>                
                                                <option value="LV" data-alt="" data-code="+371">Latvia</option>                
                                                <option value="LB" data-alt="" data-code="+961">Lebanon</option>                
                                                <option value="LS" data-alt="" data-code="+266">Lesotho</option>                
                                                <option value="LR" data-alt="" data-code="+231">Liberia</option>                
                                                <option value="LY" data-alt="" data-code="+218">Libyan Arab Jamahiriya</option>                
                                                <option value="LI" data-alt="" data-code="+423">Liechtenstein</option>                
                                                <option value="LT" data-alt="" data-code="+370">Lithuania</option>                
                                                <option value="LU" data-alt="" data-code="+352">Luxembourg</option>                
                                                <option value="MO" data-alt="" data-code="+853">Macao</option>                
                                                <option value="MK" data-alt="" data-code="+389">Macedonia, the Former Yugoslav Republic of</option>                
                                                <option value="MG" data-alt="" data-code="+261">Madagascar</option>                
                                                <option value="MW" data-alt="" data-code="+265">Malawi</option>                
                                                <option value="MY" data-alt="" data-code="+60">Malaysia</option>                
                                                <option value="MV" data-alt="" data-code="+960">Maldives</option>                
                                                <option value="ML" data-alt="" data-code="+223">Mali</option>                
                                                <option value="MT" data-alt="" data-code="+356">Malta</option>                
                                                <option value="MH" data-alt="" data-code="+692">Marshall Islands</option>                
                                                <option value="MR" data-alt="" data-code="+222">Mauritania</option>                
                                                <option value="MU" data-alt="" data-code="+230">Mauritius</option>                
                                                <option value="YT" data-alt="" data-code="+262">Mayotte</option>                
                                                <option value="MX" data-alt="" data-code="+52">Mexico</option>                
                                                <option value="FM" data-alt="" data-code="+691">Micronesia, Federated States of</option>                
                                                <option value="MD" data-alt="" data-code="+373">Moldova, Republic of</option>                
                                                <option value="MC" data-alt="" data-code="+377">Monaco</option>                
                                                <option value="MN" data-alt="" data-code="+976">Mongolia</option>                
                                                <option value="MS" data-alt="" data-code="+1 664">Montserrat</option>                
                                                <option value="MA" data-alt="" data-code="+212">Morocco</option>                
                                                <option value="MZ" data-alt="" data-code="+258">Mozambique</option>                
                                                <option value="MM" data-alt="" data-code="+95">Myanmar</option>                
                                                <option value="NA" data-alt="" data-code="+264">Namibia</option>                
                                                <option value="NR" data-alt="" data-code="+674">Nauru</option>                
                                                <option value="NP" data-alt="" data-code="+977">Nepal</option>                
                                                <option value="NL" data-alt="" data-code="+31">Netherlands</option>                
                                                <option value="AN" data-alt="" data-code="+599">Netherlands Antilles</option>                
                                                <option value="NC" data-alt="" data-code="+687">New Caledonia</option>                
                                                <option value="NZ" data-alt="" data-code="+64">New Zealand</option>                
                                                <option value="NI" data-alt="" data-code="+505">Nicaragua</option>                
                                                <option value="NE" data-alt="" data-code="+227">Niger</option>                
                                                <option value="NG" data-alt="" data-code="+234">Nigeria</option>                
                                                <option value="NU" data-alt="" data-code="+683">Niue</option>                
                                                <option value="KP" data-alt="" data-code="+850">North Korea</option>                
                                                <option value="MP" data-alt="" data-code="+1 670">Northern Mariana Islands</option>                
                                                <option value="NO" data-alt="" data-code="+47">Norway</option>                
                                                <option value="OM" data-alt="" data-code="+968">Oman</option>                
                                                <option value="PK" data-alt="" data-code="+92">Pakistan</option>                
                                                <option value="PW" data-alt="" data-code="+680">Palau</option>                
                                                <option value="PA" data-alt="" data-code="+507">Panama</option>                
                                                <option value="PG" data-alt="" data-code="+675">Papua New Guinea</option>                
                                                <option value="PY" data-alt="" data-code="+595">Paraguay</option>                
                                                <option value="PE" data-alt="" data-code="+51">Peru</option>                
                                                <option value="PH" data-alt="" data-code="+63">Philippines</option>                
                                                <option value="PN" data-alt="" data-code="+870">Pitcairn</option>                
                                                <option value="PL" data-alt="" data-code="+48">Poland</option>                
                                                <option value="PT" data-alt="" data-code="+351">Portugal</option>                
                                                <option value="PR" data-alt="" data-code="+1">Puerto Rico</option>                
                                                <option value="QA" data-alt="" data-code="+974">Qatar</option>                
                                                <option value="RO" data-alt="" data-code="+40">Romania</option>                
                                                <option value="RU" data-alt="" data-code="+7">Russian Federation</option>                
                                                <option value="RW" data-alt="" data-code="+250">Rwanda</option>                
                                                <option value="SH" data-alt="" data-code="+290">Saint Helena</option>                
                                                <option value="KN" data-alt="" data-code="+1 869">Saint Kitts and Nevis</option>                
                                                <option value="LC" data-alt="" data-code="+1 758">Saint Lucia</option>                
                                                <option value="PM" data-alt="" data-code="+508">Saint Pierre and Miquelon</option>                
                                                <option value="VC" data-alt="" data-code="+1 784">Saint Vincent and the Grenadines</option>                
                                                <option value="WS" data-alt="" data-code="+685">Samoa</option>                
                                                <option value="SM" data-alt="" data-code="+378">San Marino</option>                
                                                <option value="ST" data-alt="" data-code="+239">Sao Tome and Principe</option>                
                                                <option value="SA" data-alt="" data-code="+966">Saudi Arabia</option>                
                                                <option value="SN" data-alt="" data-code="+221">Senegal</option>                
                                                <option value="SC" data-alt="" data-code="+248">Seychelles</option>                
                                                <option value="SL" data-alt="" data-code="+232">Sierra Leone</option>                
                                                <option value="SG" data-alt="" data-code="+65">Singapore</option>                
                                                <option value="SK" data-alt="" data-code="+421">Slovakia</option>                
                                                <option value="SI" data-alt="" data-code="+386">Slovenia</option>                
                                                <option value="SB" data-alt="" data-code="+677">Solomon Islands</option>                
                                                <option value="SO" data-alt="" data-code="+252">Somalia</option>                
                                                <option value="ZA" data-alt="" data-code="+27">South Africa</option>                
                                                <option value="KR" data-alt="South Korea" data-code="+82">South Korea</option>                
                                                <option value="ES" data-alt="" data-code="+34">Spain</option>                
                                                <option value="LK" data-alt="" data-code="+94">Sri Lanka</option>                
                                                <option value="SD" data-alt="" data-code="+249">Sudan</option>                
                                                <option value="SR" data-alt="" data-code="+597">Suriname</option>                
                                                <option value="SZ" data-alt="" data-code="+268">Swaziland</option>                
                                                <option value="SE" data-alt="" data-code="+46">Sweden</option>                
                                                <option value="CH" data-alt="" data-code="+41">Switzerland</option>                
                                                <option value="SY" data-alt="" data-code="+963">Syrian Arab Republic</option>                
                                                <option value="TW" data-alt="" data-code="+886">Taiwan, Province of China</option>                
                                                <option value="TJ" data-alt="" data-code="+992">Tajikistan</option>                
                                                <option value="TZ" data-alt="" data-code="+255">Tanzania, United Republic of</option>                
                                                <option value="TH" data-alt="" data-code="+66">Thailand</option>                
                                                <option value="TL" data-alt="" data-code="+670">Timor-Leste</option>                
                                                <option value="TG" data-alt="" data-code="+228">Togo</option>                
                                                <option value="TK" data-alt="" data-code="+690">Tokelau</option>                
                                                <option value="TO" data-alt="" data-code="+676">Tonga</option>                
                                                <option value="TT" data-alt="" data-code="+1 868">Trinidad and Tobago</option>                
                                                <option value="TN" data-alt="" data-code="+216">Tunisia</option>                
                                                <option value="TR" data-alt="" data-code="+90">Turkey</option>                
                                                <option value="TM" data-alt="" data-code="+993">Turkmenistan</option>                
                                                <option value="TC" data-alt="" data-code="+1 649">Turks and Caicos Islands</option>                
                                                <option value="TV" data-alt="" data-code="+688">Tuvalu</option>                
                                                <option value="UG" data-alt="" data-code="+256">Uganda</option>                
                                                <option value="GB" data-alt="United Kingdom England Wales Scotland Ireland Northern Ireland NI Great Britain" data-code="+44" selected="selected">UK</option>                
                                                <option value="UA" data-alt="" data-code="+380">Ukraine</option>                
                                                <option value="AE" data-alt="uae" data-code="+971">United Arab Emirates</option>                
                                                <option value="US" data-alt="US USA America" data-code="+1">United States</option>                
                                                <option value="UY" data-alt="" data-code="+598">Uruguay</option>                
                                                <option value="UZ" data-alt="" data-code="+998">Uzbekistan</option>                
                                                <option value="VU" data-alt="" data-code="+678">Vanuatu</option>                
                                                <option value="VE" data-alt="" data-code="+58">Venezuela</option>                
                                                <option value="VN" data-alt="" data-code="+84">Viet Nam</option>                
                                                <option value="VG" data-alt="" data-code="+1 284">Virgin Islands, British</option>                
                                                <option value="VI" data-alt="" data-code="+1 340">Virgin Islands, U.s.</option>                
                                                <option value="WF" data-alt="" data-code="+681">Wallis and Futuna</option>                
                                                <option value="YE" data-alt="" data-code="+967">Yemen</option>                
                                                <option value="ZM" data-alt="" data-code="+260">Zambia</option>                
                                                <option value="ZW" data-alt="" data-code="+263">Zimbabwe</option>                
                                            </select>
                                    </div>
                                </fieldset>
                                
                                <div class='alert alert-danger' id='collect-errors' style='display:none;'>Please complete all required fields marked red above before proceeding.</div>
                                
                                <a id="set-collect-address"  class="btn btn-primary btn-block text-uppercase" href="Javascript:void(0);">Collect From Here</a>
                            </form>
                            </div>
                            
						</div><!-- END COL 1 TO HIDE -->
                       
                       <div class='col-1-to-show' style='display:none;'></div>
                       
                        </div>
                        <div class="col-sm-6 mt-3">
                        <div class='col-2-to-hide'>
                        	<div class="whitetextboxlp">
                        	<h6><strong>Enter The Delivery Address</strong></h6>
                            <form id="deliveryAddressForm" class="pt-2" action="" method="post">
                            	<div class="form-group">
                                    <label for="addressType2">Type Of Address</label>
                                    <select id="addressType2" class="req2 form-control custom-select">
                                        <option value="" selected>Please Select</option>
                                        <option value="1">House</option>
                                        <option value="2">Apartment/Flat</option>
                                        <option value="3">Business</option>
                                        <option value="4">University Accommodation</option>
                                        <option value="5">Hotel</option>
                                        <option value="6">Other</option>
                                    </select>
                                    <div id="ifApartment2" style="display:none;" class="alert alert-info" role="alert">
                                        <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong> <small>Please ensure your buzzer is working and is clearly labelled. When the driver arrives please meet them on the ground floor / lobby to hand over or receive your items.</small>
                                    </div>
                                    <div id="ifBusiness2" style="display:none;" class="alert alert-info" role="alert">
                                        <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong> <small>Unless the building is split with different floors for different businesses items will normally be collected from the main ground floor reception.</small>
                                    </div>
                                    <div id="ifUni2" style="display:none;" class="alert alert-info" role="alert">
                                        <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong> <small>Please ensure the Uni address provided is a standard address that receives deliveries, drivers cannot access individual dorms or call upon arrival. If you're in secure private halls without a reception, ask your Uni where to have items delivered to or collected from, they will beable to advise you of a reception or post room. If sending to / from a porters' lodge include this in the address.</small>
                                    </div>
                                    <div id="ifHotel2" style="display:none;" class="alert alert-info" role="alert">
                                        <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong> <small>If sending to or from a Hotel please ensure the “Guest Name" is in the format: “Mr Smith c/o The Reception” or “Mr Smith c/o The Concierge”. When leaving your bags for collection please inform the hotel that a courier will be collecting them on your behalf.</small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="notHotelName2" for="custName">Full Name</label>
                                    <label id="ifHotelName2" style="display:none;" for="custName">Contact or Guest Name</label>
                                    <input id="custName2" type="text" class="form-control req2">
                                </div>
                                <div class="form-group">
                                	<label for="custPhone">Phone Number of Contact</label>
                                    <div class="row no-gutters">
                                    	<div class="col-3">
                                            <select id="custPhone_CountryCode2" name="custPhone_CountryCode" class="form-control custom-select" placeholder="" autocomplete="false" data-bind="value: CountryCode" tabindex="-1" title="">
                                                <option value=""></option>
                                                <option value="AF" data-alt="" data-code="+93">Afghanistan</option>                
                                                <option value="AL" data-alt="" data-code="+355">Albania</option>                
                                                <option value="DZ" data-alt="" data-code="+213">Algeria</option>                
                                                <option value="AS" data-alt="" data-code="+1 684">American Samoa</option>                
                                                <option value="AD" data-alt="" data-code="+376">Andorra</option>                
                                                <option value="AO" data-alt="" data-code="+244">Angola</option>                
                                                <option value="AI" data-alt="" data-code="+1 264">Anguilla</option>                
                                                <option value="AQ" data-alt="" data-code="+672">Antarctica</option>                
                                                <option value="AG" data-alt="" data-code="+1 268">Antigua and Barbuda</option>                
                                                <option value="AR" data-alt="" data-code="+54">Argentina</option>                
                                                <option value="AM" data-alt="" data-code="+374">Armenia</option>                
                                                <option value="AW" data-alt="" data-code="+297">Aruba</option>                
                                                <option value="AU" data-alt="" data-code="+61">Australia</option>                
                                                <option value="AT" data-alt="" data-code="+43">Austria</option>                
                                                <option value="AZ" data-alt="" data-code="+994">Azerbaijan</option>                
                                                <option value="BS" data-alt="" data-code="+1 242">Bahamas</option>                
                                                <option value="BH" data-alt="" data-code="+973">Bahrain</option>                
                                                <option value="BD" data-alt="" data-code="+880">Bangladesh</option>                
                                                <option value="BB" data-alt="" data-code="+1 246">Barbados</option>                
                                                <option value="BY" data-alt="" data-code="+375">Belarus</option>                
                                                <option value="BE" data-alt="" data-code="+32">Belgium</option>                
                                                <option value="BZ" data-alt="" data-code="+501">Belize</option>                
                                                <option value="BJ" data-alt="" data-code="+229">Benin</option>                
                                                <option value="BM" data-alt="" data-code="+1 441">Bermuda</option>                
                                                <option value="BT" data-alt="" data-code="+975">Bhutan</option>                
                                                <option value="BO" data-alt="" data-code="+591">Bolivia</option>                
                                                <option value="BA" data-alt="" data-code="+387">Bosnia and Herzegovina</option>                
                                                <option value="BW" data-alt="" data-code="+267">Botswana</option>                
                                                <option value="BR" data-alt="" data-code="+55">Brazil</option>                
                                                <option value="BN" data-alt="" data-code="+673">Brunei Darussalam</option>                
                                                <option value="BG" data-alt="" data-code="+359">Bulgaria</option>                
                                                <option value="BF" data-alt="" data-code="+226">Burkina Faso</option>                
                                                <option value="BI" data-alt="" data-code="+257">Burundi</option>                
                                                <option value="KH" data-alt="" data-code="+855">Cambodia</option>                
                                                <option value="CM" data-alt="" data-code="+237">Cameroon</option>                
                                                <option value="CA" data-alt="" data-code="+1">Canada</option>                
                                                <option value="CV" data-alt="" data-code="+238">Cape Verde</option>                
                                                <option value="KY" data-alt="" data-code="+1 345">Cayman Islands</option>                
                                                <option value="CF" data-alt="" data-code="+236">Central African Republic</option>                
                                                <option value="TD" data-alt="" data-code="+235">Chad</option>                
                                                <option value="CL" data-alt="" data-code="+56">Chile</option>                
                                                <option value="CN" data-alt="" data-code="+86">China</option>                
                                                <option value="CX" data-alt="" data-code="+61">Christmas Island</option>                
                                                <option value="CC" data-alt="" data-code="+61">Cocos (Keeling) Islands</option>                
                                                <option value="CO" data-alt="" data-code="+57">Colombia</option>                
                                                <option value="KM" data-alt="" data-code="+269">Comoros</option>                
                                                <option value="CG" data-alt="" data-code="+242">Congo</option>                
                                                <option value="CD" data-alt="" data-code="+243">Congo, the Democratic Republic of the</option>                
                                                <option value="CK" data-alt="" data-code="+682">Cook Islands</option>                
                                                <option value="CR" data-alt="" data-code="+506">Costa Rica</option>                
                                                <option value="CI" data-alt="" data-code="+225">Cote D'Ivoire</option>                
                                                <option value="HR" data-alt="" data-code="+385">Croatia</option>                
                                                <option value="CU" data-alt="" data-code="+53">Cuba</option>                
                                                <option value="CY" data-alt="" data-code="+357">Cyprus</option>                
                                                <option value="CZ" data-alt="" data-code="+420">Czech Republic</option>                
                                                <option value="DK" data-alt="" data-code="+45">Denmark</option>                
                                                <option value="DJ" data-alt="" data-code="+253">Djibouti</option>                
                                                <option value="DM" data-alt="" data-code="+1 767">Dominica</option>                
                                                <option value="DO" data-alt="" data-code="+1 809">Dominican Republic</option>                
                                                <option value="EC" data-alt="" data-code="+593">Ecuador</option>                
                                                <option value="EG" data-alt="" data-code="+20">Egypt</option>                
                                                <option value="SV" data-alt="" data-code="+503">El Salvador</option>                
                                                <option value="GQ" data-alt="" data-code="+240">Equatorial Guinea</option>                
                                                <option value="ER" data-alt="" data-code="+291">Eritrea</option>                
                                                <option value="EE" data-alt="" data-code="+372">Estonia</option>                
                                                <option value="ET" data-alt="" data-code="+251">Ethiopia</option>                
                                                <option value="FK" data-alt="" data-code="+500">Falkland Islands (Malvinas)</option>                
                                                <option value="FO" data-alt="" data-code="+298">Faroe Islands</option>                
                                                <option value="FJ" data-alt="" data-code="+679">Fiji</option>                
                                                <option value="FI" data-alt="" data-code="+358">Finland</option>                
                                                <option value="FR" data-alt="" data-code="+33">France</option>                
                                                <option value="PF" data-alt="" data-code="+689">French Polynesia</option>                
                                                <option value="GA" data-alt="" data-code="+241">Gabon</option>                
                                                <option value="GM" data-alt="" data-code="+220">Gambia</option>                
                                                <option value="GE" data-alt="" data-code="+995">Georgia</option>                
                                                <option value="DE" data-alt="" data-code="+49">Germany</option>                
                                                <option value="GH" data-alt="" data-code="+233">Ghana</option>                
                                                <option value="GI" data-alt="" data-code="+350">Gibraltar</option>                
                                                <option value="GR" data-alt="" data-code="+30">Greece</option>                
                                                <option value="GL" data-alt="" data-code="+299">Greenland</option>                
                                                <option value="GD" data-alt="" data-code="+1 473">Grenada</option>                
                                                <option value="GU" data-alt="" data-code="+1 671">Guam</option>                
                                                <option value="GT" data-alt="" data-code="+502">Guatemala</option>                
                                                <option value="GN" data-alt="" data-code="+224">Guinea</option>                
                                                <option value="GW" data-alt="" data-code="+245">Guinea-Bissau</option>                
                                                <option value="GY" data-alt="" data-code="+592">Guyana</option>                
                                                <option value="HT" data-alt="" data-code="+509">Haiti</option>                
                                                <option value="VA" data-alt="" data-code="+39">Holy See (Vatican City State)</option>                
                                                <option value="HN" data-alt="" data-code="+504">Honduras</option>                
                                                <option value="HK" data-alt="" data-code="+852">Hong Kong</option>                
                                                <option value="HU" data-alt="" data-code="+36">Hungary</option>                
                                                <option value="IS" data-alt="" data-code="+354">Iceland</option>                
                                                <option value="IN" data-alt="" data-code="+91">India</option>                
                                                <option value="ID" data-alt="" data-code="+62">Indonesia</option>                
                                                <option value="IR" data-alt="" data-code="+98">Iran, Islamic Republic of</option>                
                                                <option value="IQ" data-alt="" data-code="+964">Iraq</option>                
                                                <option value="IE" data-alt="" data-code="+353">Ireland</option>                
                                                <option value="IL" data-alt="" data-code="+972">Israel</option>                
                                                <option value="IT" data-alt="" data-code="+39">Italy</option>                
                                                <option value="JM" data-alt="" data-code="+1 876">Jamaica</option>                
                                                <option value="JP" data-alt="" data-code="+81">Japan</option>                
                                                <option value="JO" data-alt="" data-code="+962">Jordan</option>                
                                                <option value="KZ" data-alt="" data-code="+7">Kazakhstan</option>                
                                                <option value="KE" data-alt="" data-code="+254">Kenya</option>                
                                                <option value="KI" data-alt="" data-code="+686">Kiribati</option>                
                                                <option value="KW" data-alt="" data-code="+965">Kuwait</option>                
                                                <option value="KG" data-alt="" data-code="+996">Kyrgyzstan</option>                
                                                <option value="LA" data-alt="" data-code="+856">Lao People's Democratic Republic</option>                
                                                <option value="LV" data-alt="" data-code="+371">Latvia</option>                
                                                <option value="LB" data-alt="" data-code="+961">Lebanon</option>                
                                                <option value="LS" data-alt="" data-code="+266">Lesotho</option>                
                                                <option value="LR" data-alt="" data-code="+231">Liberia</option>                
                                                <option value="LY" data-alt="" data-code="+218">Libyan Arab Jamahiriya</option>                
                                                <option value="LI" data-alt="" data-code="+423">Liechtenstein</option>                
                                                <option value="LT" data-alt="" data-code="+370">Lithuania</option>                
                                                <option value="LU" data-alt="" data-code="+352">Luxembourg</option>                
                                                <option value="MO" data-alt="" data-code="+853">Macao</option>                
                                                <option value="MK" data-alt="" data-code="+389">Macedonia, the Former Yugoslav Republic of</option>                
                                                <option value="MG" data-alt="" data-code="+261">Madagascar</option>                
                                                <option value="MW" data-alt="" data-code="+265">Malawi</option>                
                                                <option value="MY" data-alt="" data-code="+60">Malaysia</option>                
                                                <option value="MV" data-alt="" data-code="+960">Maldives</option>                
                                                <option value="ML" data-alt="" data-code="+223">Mali</option>                
                                                <option value="MT" data-alt="" data-code="+356">Malta</option>                
                                                <option value="MH" data-alt="" data-code="+692">Marshall Islands</option>                
                                                <option value="MR" data-alt="" data-code="+222">Mauritania</option>                
                                                <option value="MU" data-alt="" data-code="+230">Mauritius</option>                
                                                <option value="YT" data-alt="" data-code="+262">Mayotte</option>                
                                                <option value="MX" data-alt="" data-code="+52">Mexico</option>                
                                                <option value="FM" data-alt="" data-code="+691">Micronesia, Federated States of</option>                
                                                <option value="MD" data-alt="" data-code="+373">Moldova, Republic of</option>                
                                                <option value="MC" data-alt="" data-code="+377">Monaco</option>                
                                                <option value="MN" data-alt="" data-code="+976">Mongolia</option>                
                                                <option value="MS" data-alt="" data-code="+1 664">Montserrat</option>                
                                                <option value="MA" data-alt="" data-code="+212">Morocco</option>                
                                                <option value="MZ" data-alt="" data-code="+258">Mozambique</option>                
                                                <option value="MM" data-alt="" data-code="+95">Myanmar</option>                
                                                <option value="NA" data-alt="" data-code="+264">Namibia</option>                
                                                <option value="NR" data-alt="" data-code="+674">Nauru</option>                
                                                <option value="NP" data-alt="" data-code="+977">Nepal</option>                
                                                <option value="NL" data-alt="" data-code="+31">Netherlands</option>                
                                                <option value="AN" data-alt="" data-code="+599">Netherlands Antilles</option>                
                                                <option value="NC" data-alt="" data-code="+687">New Caledonia</option>                
                                                <option value="NZ" data-alt="" data-code="+64">New Zealand</option>                
                                                <option value="NI" data-alt="" data-code="+505">Nicaragua</option>                
                                                <option value="NE" data-alt="" data-code="+227">Niger</option>                
                                                <option value="NG" data-alt="" data-code="+234">Nigeria</option>                
                                                <option value="NU" data-alt="" data-code="+683">Niue</option>                
                                                <option value="KP" data-alt="" data-code="+850">North Korea</option>                
                                                <option value="MP" data-alt="" data-code="+1 670">Northern Mariana Islands</option>                
                                                <option value="NO" data-alt="" data-code="+47">Norway</option>                
                                                <option value="OM" data-alt="" data-code="+968">Oman</option>                
                                                <option value="PK" data-alt="" data-code="+92">Pakistan</option>                
                                                <option value="PW" data-alt="" data-code="+680">Palau</option>                
                                                <option value="PA" data-alt="" data-code="+507">Panama</option>                
                                                <option value="PG" data-alt="" data-code="+675">Papua New Guinea</option>                
                                                <option value="PY" data-alt="" data-code="+595">Paraguay</option>                
                                                <option value="PE" data-alt="" data-code="+51">Peru</option>                
                                                <option value="PH" data-alt="" data-code="+63">Philippines</option>                
                                                <option value="PN" data-alt="" data-code="+870">Pitcairn</option>                
                                                <option value="PL" data-alt="" data-code="+48">Poland</option>                
                                                <option value="PT" data-alt="" data-code="+351">Portugal</option>                
                                                <option value="PR" data-alt="" data-code="+1">Puerto Rico</option>                
                                                <option value="QA" data-alt="" data-code="+974">Qatar</option>                
                                                <option value="RO" data-alt="" data-code="+40">Romania</option>                
                                                <option value="RU" data-alt="" data-code="+7">Russian Federation</option>                
                                                <option value="RW" data-alt="" data-code="+250">Rwanda</option>                
                                                <option value="SH" data-alt="" data-code="+290">Saint Helena</option>                
                                                <option value="KN" data-alt="" data-code="+1 869">Saint Kitts and Nevis</option>                
                                                <option value="LC" data-alt="" data-code="+1 758">Saint Lucia</option>                
                                                <option value="PM" data-alt="" data-code="+508">Saint Pierre and Miquelon</option>                
                                                <option value="VC" data-alt="" data-code="+1 784">Saint Vincent and the Grenadines</option>                
                                                <option value="WS" data-alt="" data-code="+685">Samoa</option>                
                                                <option value="SM" data-alt="" data-code="+378">San Marino</option>                
                                                <option value="ST" data-alt="" data-code="+239">Sao Tome and Principe</option>                
                                                <option value="SA" data-alt="" data-code="+966">Saudi Arabia</option>                
                                                <option value="SN" data-alt="" data-code="+221">Senegal</option>                
                                                <option value="SC" data-alt="" data-code="+248">Seychelles</option>                
                                                <option value="SL" data-alt="" data-code="+232">Sierra Leone</option>                
                                                <option value="SG" data-alt="" data-code="+65">Singapore</option>                
                                                <option value="SK" data-alt="" data-code="+421">Slovakia</option>                
                                                <option value="SI" data-alt="" data-code="+386">Slovenia</option>                
                                                <option value="SB" data-alt="" data-code="+677">Solomon Islands</option>                
                                                <option value="SO" data-alt="" data-code="+252">Somalia</option>                
                                                <option value="ZA" data-alt="" data-code="+27">South Africa</option>                
                                                <option value="KR" data-alt="South Korea" data-code="+82">South Korea</option>                
                                                <option value="ES" data-alt="" data-code="+34">Spain</option>                
                                                <option value="LK" data-alt="" data-code="+94">Sri Lanka</option>                
                                                <option value="SD" data-alt="" data-code="+249">Sudan</option>                
                                                <option value="SR" data-alt="" data-code="+597">Suriname</option>                
                                                <option value="SZ" data-alt="" data-code="+268">Swaziland</option>                
                                                <option value="SE" data-alt="" data-code="+46">Sweden</option>                
                                                <option value="CH" data-alt="" data-code="+41">Switzerland</option>                
                                                <option value="SY" data-alt="" data-code="+963">Syrian Arab Republic</option>                
                                                <option value="TW" data-alt="" data-code="+886">Taiwan, Province of China</option>                
                                                <option value="TJ" data-alt="" data-code="+992">Tajikistan</option>                
                                                <option value="TZ" data-alt="" data-code="+255">Tanzania, United Republic of</option>                
                                                <option value="TH" data-alt="" data-code="+66">Thailand</option>                
                                                <option value="TL" data-alt="" data-code="+670">Timor-Leste</option>                
                                                <option value="TG" data-alt="" data-code="+228">Togo</option>                
                                                <option value="TK" data-alt="" data-code="+690">Tokelau</option>                
                                                <option value="TO" data-alt="" data-code="+676">Tonga</option>                
                                                <option value="TT" data-alt="" data-code="+1 868">Trinidad and Tobago</option>                
                                                <option value="TN" data-alt="" data-code="+216">Tunisia</option>                
                                                <option value="TR" data-alt="" data-code="+90">Turkey</option>                
                                                <option value="TM" data-alt="" data-code="+993">Turkmenistan</option>                
                                                <option value="TC" data-alt="" data-code="+1 649">Turks and Caicos Islands</option>                
                                                <option value="TV" data-alt="" data-code="+688">Tuvalu</option>                
                                                <option value="UG" data-alt="" data-code="+256">Uganda</option>                
                                                <option value="GB" data-alt="United Kingdom England Wales Scotland Ireland Northern Ireland NI Great Britain" data-code="+44" selected="selected">UK</option>                
                                                <option value="UA" data-alt="" data-code="+380">Ukraine</option>                
                                                <option value="AE" data-alt="uae" data-code="+971">United Arab Emirates</option>                
                                                <option value="US" data-alt="US USA America" data-code="+1">United States</option>                
                                                <option value="UY" data-alt="" data-code="+598">Uruguay</option>                
                                                <option value="UZ" data-alt="" data-code="+998">Uzbekistan</option>                
                                                <option value="VU" data-alt="" data-code="+678">Vanuatu</option>                
                                                <option value="VE" data-alt="" data-code="+58">Venezuela</option>                
                                                <option value="VN" data-alt="" data-code="+84">Viet Nam</option>                
                                                <option value="VG" data-alt="" data-code="+1 284">Virgin Islands, British</option>                
                                                <option value="VI" data-alt="" data-code="+1 340">Virgin Islands, U.s.</option>                
                                                <option value="WF" data-alt="" data-code="+681">Wallis and Futuna</option>                
                                                <option value="YE" data-alt="" data-code="+967">Yemen</option>                
                                                <option value="ZM" data-alt="" data-code="+260">Zambia</option>                
                                                <option value="ZW" data-alt="" data-code="+263">Zimbabwe</option>                
                                            </select>
                                        </div>
                                        <div class="col-9">
                                            <input id="custPhone2" type="text" placeholder="Enter local number" class="req2 form-control">
                                        </div>
                                    </div>
                                    <p><small class="text-muted">Unfortunately drivers cannot telephone upon arrival. Please ensure your address is fully accessible e.g. door bell working</small></p>
                                </div>
                                <div class="form-group">
                                	<div id="radiobuzzer2">
                                        <p>Do you have a buzzer (other than your apartment number) or entry code?</p>
                                        <div class="form-check form-check-inline">
                                            <label class="custom-control custom-radio">
                                                <input id="yesBuzzer2" name="radioBuzzer2" type="radio" class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Yes</span>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="custom-control custom-radio">
                                                <input checked id="noBuzzer2" name="radioBuzzer2" type="radio" class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">No</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div style="display:none;" id="ifBuzzer2">
                                        <label for="custBuzzer2">Buzzer/Entry Code</label>
                                        <input id="custBuzzer2" type="text" class="form-control" placeholder="Optional">
                                    </div>
                                </div>
                                <div class="form-group">
                                	<label for="custAddress1_2">Address Line 1</label>
                                    <input id="custAddress1_2" type="text" class="req2 form-control">
                                </div>
                                <div class="form-group">
                                	<label for="custAddress2_2">Address Line 2</label>
                                    <input id="custAddress2_2" type="text" class="form-control" placeholder="Optional">
                                </div>
                                <div class="form-group">
                                	<label for="custCity">City</label>
                                    <input id="custCity2" type="text" class="req2 form-control"  value="<?php echo $_POST['quote']['to_city'];?>">
                                </div>
                                <div class="form-group">
                                	<label for="custPostcode2">Post/Zip Code</label>
                                    <input id="custPostcode2" type="text" <?php if(isset($_GET['tp'])):?>value='<?php echo $_GET['tp'];?>'<?php endif;?> class="req2 form-control">
                                </div>
                                 <div class='form-group' style='display:<?php if($_GET['ct'] == 'US'):?>block;<?php else:?>none;<?php endif;?>' id='state-list-to'>
                                	<label>State:</label>
                                	<select class='form-control' id='state-to'>
											<option value="AL">Alabama</option>
											<option value="AK">Alaska</option>
											<option value="AZ">Arizona</option>
											<option value="AR">Arkansas</option>
											<option value="CA">California</option>
											<option value="CO">Colorado</option>
											<option value="CT">Connecticut</option>
											<option value="DE">Delaware</option>
											<option value="DC">District Of Columbia</option>
											<option value="FL">Florida</option>
											<option value="GA">Georgia</option>
											<option value="HI">Hawaii</option>
											<option value="ID">Idaho</option>
											<option value="IL">Illinois</option>
											<option value="IN">Indiana</option>
											<option value="IA">Iowa</option>
											<option value="KS">Kansas</option>
											<option value="KY">Kentucky</option>
											<option value="LA">Louisiana</option>
											<option value="ME">Maine</option>
											<option value="MD">Maryland</option>
											<option value="MA">Massachusetts</option>
											<option value="MI">Michigan</option>
											<option value="MN">Minnesota</option>
											<option value="MS">Mississippi</option>
											<option value="MO">Missouri</option>
											<option value="MT">Montana</option>
											<option value="NE">Nebraska</option>
											<option value="NV">Nevada</option>
											<option value="NH">New Hampshire</option>
											<option value="NJ">New Jersey</option>
											<option value="NM">New Mexico</option>
											<option value="NY">New York</option>
											<option value="NC">North Carolina</option>
											<option value="ND">North Dakota</option>
											<option value="OH">Ohio</option>
											<option value="OK">Oklahoma</option>
											<option value="OR">Oregon</option>
											<option value="PA">Pennsylvania</option>
											<option value="RI">Rhode Island</option>
											<option value="SC">South Carolina</option>
											<option value="SD">South Dakota</option>
											<option value="TN">Tennessee</option>
											<option value="TX">Texas</option>
											<option value="UT">Utah</option>
											<option value="VT">Vermont</option>
											<option value="VA">Virginia</option>
											<option value="WA">Washington</option>
											<option value="WV">West Virginia</option>
											<option value="WI">Wisconsin</option>
											<option value="WY">Wyoming</option>
										</select>				
				
                                	
                                </div>
                                <fieldset >
                                    <div class="form-group">
                                      	<label for="Destination_CountryIso">Country</label>
                                        <select id="custOrigin_CountryIso2" name="Origin_CountryIso" class="form-control custom-select" placeholder=""  tabindex="-1" title="">
                                                <option value=""></option>
                                                <option value="AF" data-alt="" data-code="+93">Afghanistan</option>                
                                                <option value="AL" data-alt="" data-code="+355">Albania</option>                
                                                <option value="DZ" data-alt="" data-code="+213">Algeria</option>                
                                                <option value="AS" data-alt="" data-code="+1 684">American Samoa</option>                
                                                <option value="AD" data-alt="" data-code="+376">Andorra</option>                
                                                <option value="AO" data-alt="" data-code="+244">Angola</option>                
                                                <option value="AI" data-alt="" data-code="+1 264">Anguilla</option>                
                                                <option value="AQ" data-alt="" data-code="+672">Antarctica</option>                
                                                <option value="AG" data-alt="" data-code="+1 268">Antigua and Barbuda</option>                
                                                <option value="AR" data-alt="" data-code="+54">Argentina</option>                
                                                <option value="AM" data-alt="" data-code="+374">Armenia</option>                
                                                <option value="AW" data-alt="" data-code="+297">Aruba</option>                
                                                <option value="AU" data-alt="" data-code="+61">Australia</option>                
                                                <option value="AT" data-alt="" data-code="+43">Austria</option>                
                                                <option value="AZ" data-alt="" data-code="+994">Azerbaijan</option>                
                                                <option value="BS" data-alt="" data-code="+1 242">Bahamas</option>                
                                                <option value="BH" data-alt="" data-code="+973">Bahrain</option>                
                                                <option value="BD" data-alt="" data-code="+880">Bangladesh</option>                
                                                <option value="BB" data-alt="" data-code="+1 246">Barbados</option>                
                                                <option value="BY" data-alt="" data-code="+375">Belarus</option>                
                                                <option value="BE" data-alt="" data-code="+32">Belgium</option>                
                                                <option value="BZ" data-alt="" data-code="+501">Belize</option>                
                                                <option value="BJ" data-alt="" data-code="+229">Benin</option>                
                                                <option value="BM" data-alt="" data-code="+1 441">Bermuda</option>                
                                                <option value="BT" data-alt="" data-code="+975">Bhutan</option>                
                                                <option value="BO" data-alt="" data-code="+591">Bolivia</option>                
                                                <option value="BA" data-alt="" data-code="+387">Bosnia and Herzegovina</option>                
                                                <option value="BW" data-alt="" data-code="+267">Botswana</option>                
                                                <option value="BR" data-alt="" data-code="+55">Brazil</option>                
                                                <option value="BN" data-alt="" data-code="+673">Brunei Darussalam</option>                
                                                <option value="BG" data-alt="" data-code="+359">Bulgaria</option>                
                                                <option value="BF" data-alt="" data-code="+226">Burkina Faso</option>                
                                                <option value="BI" data-alt="" data-code="+257">Burundi</option>                
                                                <option value="KH" data-alt="" data-code="+855">Cambodia</option>                
                                                <option value="CM" data-alt="" data-code="+237">Cameroon</option>                
                                                <option value="CA" data-alt="" data-code="+1">Canada</option>                
                                                <option value="CV" data-alt="" data-code="+238">Cape Verde</option>                
                                                <option value="KY" data-alt="" data-code="+1 345">Cayman Islands</option>                
                                                <option value="CF" data-alt="" data-code="+236">Central African Republic</option>                
                                                <option value="TD" data-alt="" data-code="+235">Chad</option>                
                                                <option value="CL" data-alt="" data-code="+56">Chile</option>                
                                                <option value="CN" data-alt="" data-code="+86">China</option>                
                                                <option value="CX" data-alt="" data-code="+61">Christmas Island</option>                
                                                <option value="CC" data-alt="" data-code="+61">Cocos (Keeling) Islands</option>                
                                                <option value="CO" data-alt="" data-code="+57">Colombia</option>                
                                                <option value="KM" data-alt="" data-code="+269">Comoros</option>                
                                                <option value="CG" data-alt="" data-code="+242">Congo</option>                
                                                <option value="CD" data-alt="" data-code="+243">Congo, the Democratic Republic of the</option>                
                                                <option value="CK" data-alt="" data-code="+682">Cook Islands</option>                
                                                <option value="CR" data-alt="" data-code="+506">Costa Rica</option>                
                                                <option value="CI" data-alt="" data-code="+225">Cote D'Ivoire</option>                
                                                <option value="HR" data-alt="" data-code="+385">Croatia</option>                
                                                <option value="CU" data-alt="" data-code="+53">Cuba</option>                
                                                <option value="CY" data-alt="" data-code="+357">Cyprus</option>                
                                                <option value="CZ" data-alt="" data-code="+420">Czech Republic</option>                
                                                <option value="DK" data-alt="" data-code="+45">Denmark</option>                
                                                <option value="DJ" data-alt="" data-code="+253">Djibouti</option>                
                                                <option value="DM" data-alt="" data-code="+1 767">Dominica</option>                
                                                <option value="DO" data-alt="" data-code="+1 809">Dominican Republic</option>                
                                                <option value="EC" data-alt="" data-code="+593">Ecuador</option>                
                                                <option value="EG" data-alt="" data-code="+20">Egypt</option>                
                                                <option value="SV" data-alt="" data-code="+503">El Salvador</option>                
                                                <option value="GQ" data-alt="" data-code="+240">Equatorial Guinea</option>                
                                                <option value="ER" data-alt="" data-code="+291">Eritrea</option>                
                                                <option value="EE" data-alt="" data-code="+372">Estonia</option>                
                                                <option value="ET" data-alt="" data-code="+251">Ethiopia</option>                
                                                <option value="FK" data-alt="" data-code="+500">Falkland Islands (Malvinas)</option>                
                                                <option value="FO" data-alt="" data-code="+298">Faroe Islands</option>                
                                                <option value="FJ" data-alt="" data-code="+679">Fiji</option>                
                                                <option value="FI" data-alt="" data-code="+358">Finland</option>                
                                                <option value="FR" data-alt="" data-code="+33">France</option>                
                                                <option value="PF" data-alt="" data-code="+689">French Polynesia</option>                
                                                <option value="GA" data-alt="" data-code="+241">Gabon</option>                
                                                <option value="GM" data-alt="" data-code="+220">Gambia</option>                
                                                <option value="GE" data-alt="" data-code="+995">Georgia</option>                
                                                <option value="DE" data-alt="" data-code="+49">Germany</option>                
                                                <option value="GH" data-alt="" data-code="+233">Ghana</option>                
                                                <option value="GI" data-alt="" data-code="+350">Gibraltar</option>                
                                                <option value="GR" data-alt="" data-code="+30">Greece</option>                
                                                <option value="GL" data-alt="" data-code="+299">Greenland</option>                
                                                <option value="GD" data-alt="" data-code="+1 473">Grenada</option>                
                                                <option value="GU" data-alt="" data-code="+1 671">Guam</option>                
                                                <option value="GT" data-alt="" data-code="+502">Guatemala</option>                
                                                <option value="GN" data-alt="" data-code="+224">Guinea</option>                
                                                <option value="GW" data-alt="" data-code="+245">Guinea-Bissau</option>                
                                                <option value="GY" data-alt="" data-code="+592">Guyana</option>                
                                                <option value="HT" data-alt="" data-code="+509">Haiti</option>                
                                                <option value="VA" data-alt="" data-code="+39">Holy See (Vatican City State)</option>                
                                                <option value="HN" data-alt="" data-code="+504">Honduras</option>                
                                                <option value="HK" data-alt="" data-code="+852">Hong Kong</option>                
                                                <option value="HU" data-alt="" data-code="+36">Hungary</option>                
                                                <option value="IS" data-alt="" data-code="+354">Iceland</option>                
                                                <option value="IN" data-alt="" data-code="+91">India</option>                
                                                <option value="ID" data-alt="" data-code="+62">Indonesia</option>                
                                                <option value="IR" data-alt="" data-code="+98">Iran, Islamic Republic of</option>                
                                                <option value="IQ" data-alt="" data-code="+964">Iraq</option>                
                                                <option value="IE" data-alt="" data-code="+353">Ireland</option>                
                                                <option value="IL" data-alt="" data-code="+972">Israel</option>                
                                                <option value="IT" data-alt="" data-code="+39">Italy</option>                
                                                <option value="JM" data-alt="" data-code="+1 876">Jamaica</option>                
                                                <option value="JP" data-alt="" data-code="+81">Japan</option>                
                                                <option value="JO" data-alt="" data-code="+962">Jordan</option>                
                                                <option value="KZ" data-alt="" data-code="+7">Kazakhstan</option>                
                                                <option value="KE" data-alt="" data-code="+254">Kenya</option>                
                                                <option value="KI" data-alt="" data-code="+686">Kiribati</option>                
                                                <option value="KW" data-alt="" data-code="+965">Kuwait</option>                
                                                <option value="KG" data-alt="" data-code="+996">Kyrgyzstan</option>                
                                                <option value="LA" data-alt="" data-code="+856">Lao People's Democratic Republic</option>                
                                                <option value="LV" data-alt="" data-code="+371">Latvia</option>                
                                                <option value="LB" data-alt="" data-code="+961">Lebanon</option>                
                                                <option value="LS" data-alt="" data-code="+266">Lesotho</option>                
                                                <option value="LR" data-alt="" data-code="+231">Liberia</option>                
                                                <option value="LY" data-alt="" data-code="+218">Libyan Arab Jamahiriya</option>                
                                                <option value="LI" data-alt="" data-code="+423">Liechtenstein</option>                
                                                <option value="LT" data-alt="" data-code="+370">Lithuania</option>                
                                                <option value="LU" data-alt="" data-code="+352">Luxembourg</option>                
                                                <option value="MO" data-alt="" data-code="+853">Macao</option>                
                                                <option value="MK" data-alt="" data-code="+389">Macedonia, the Former Yugoslav Republic of</option>                
                                                <option value="MG" data-alt="" data-code="+261">Madagascar</option>                
                                                <option value="MW" data-alt="" data-code="+265">Malawi</option>                
                                                <option value="MY" data-alt="" data-code="+60">Malaysia</option>                
                                                <option value="MV" data-alt="" data-code="+960">Maldives</option>                
                                                <option value="ML" data-alt="" data-code="+223">Mali</option>                
                                                <option value="MT" data-alt="" data-code="+356">Malta</option>                
                                                <option value="MH" data-alt="" data-code="+692">Marshall Islands</option>                
                                                <option value="MR" data-alt="" data-code="+222">Mauritania</option>                
                                                <option value="MU" data-alt="" data-code="+230">Mauritius</option>                
                                                <option value="YT" data-alt="" data-code="+262">Mayotte</option>                
                                                <option value="MX" data-alt="" data-code="+52">Mexico</option>                
                                                <option value="FM" data-alt="" data-code="+691">Micronesia, Federated States of</option>                
                                                <option value="MD" data-alt="" data-code="+373">Moldova, Republic of</option>                
                                                <option value="MC" data-alt="" data-code="+377">Monaco</option>                
                                                <option value="MN" data-alt="" data-code="+976">Mongolia</option>                
                                                <option value="MS" data-alt="" data-code="+1 664">Montserrat</option>                
                                                <option value="MA" data-alt="" data-code="+212">Morocco</option>                
                                                <option value="MZ" data-alt="" data-code="+258">Mozambique</option>                
                                                <option value="MM" data-alt="" data-code="+95">Myanmar</option>                
                                                <option value="NA" data-alt="" data-code="+264">Namibia</option>                
                                                <option value="NR" data-alt="" data-code="+674">Nauru</option>                
                                                <option value="NP" data-alt="" data-code="+977">Nepal</option>                
                                                <option value="NL" data-alt="" data-code="+31">Netherlands</option>                
                                                <option value="AN" data-alt="" data-code="+599">Netherlands Antilles</option>                
                                                <option value="NC" data-alt="" data-code="+687">New Caledonia</option>                
                                                <option value="NZ" data-alt="" data-code="+64">New Zealand</option>                
                                                <option value="NI" data-alt="" data-code="+505">Nicaragua</option>                
                                                <option value="NE" data-alt="" data-code="+227">Niger</option>                
                                                <option value="NG" data-alt="" data-code="+234">Nigeria</option>                
                                                <option value="NU" data-alt="" data-code="+683">Niue</option>                
                                                <option value="KP" data-alt="" data-code="+850">North Korea</option>                
                                                <option value="MP" data-alt="" data-code="+1 670">Northern Mariana Islands</option>                
                                                <option value="NO" data-alt="" data-code="+47">Norway</option>                
                                                <option value="OM" data-alt="" data-code="+968">Oman</option>                
                                                <option value="PK" data-alt="" data-code="+92">Pakistan</option>                
                                                <option value="PW" data-alt="" data-code="+680">Palau</option>                
                                                <option value="PA" data-alt="" data-code="+507">Panama</option>                
                                                <option value="PG" data-alt="" data-code="+675">Papua New Guinea</option>                
                                                <option value="PY" data-alt="" data-code="+595">Paraguay</option>                
                                                <option value="PE" data-alt="" data-code="+51">Peru</option>                
                                                <option value="PH" data-alt="" data-code="+63">Philippines</option>                
                                                <option value="PN" data-alt="" data-code="+870">Pitcairn</option>                
                                                <option value="PL" data-alt="" data-code="+48">Poland</option>                
                                                <option value="PT" data-alt="" data-code="+351">Portugal</option>                
                                                <option value="PR" data-alt="" data-code="+1">Puerto Rico</option>                
                                                <option value="QA" data-alt="" data-code="+974">Qatar</option>                
                                                <option value="RO" data-alt="" data-code="+40">Romania</option>                
                                                <option value="RU" data-alt="" data-code="+7">Russian Federation</option>                
                                                <option value="RW" data-alt="" data-code="+250">Rwanda</option>                
                                                <option value="SH" data-alt="" data-code="+290">Saint Helena</option>                
                                                <option value="KN" data-alt="" data-code="+1 869">Saint Kitts and Nevis</option>                
                                                <option value="LC" data-alt="" data-code="+1 758">Saint Lucia</option>                
                                                <option value="PM" data-alt="" data-code="+508">Saint Pierre and Miquelon</option>                
                                                <option value="VC" data-alt="" data-code="+1 784">Saint Vincent and the Grenadines</option>                
                                                <option value="WS" data-alt="" data-code="+685">Samoa</option>                
                                                <option value="SM" data-alt="" data-code="+378">San Marino</option>                
                                                <option value="ST" data-alt="" data-code="+239">Sao Tome and Principe</option>                
                                                <option value="SA" data-alt="" data-code="+966">Saudi Arabia</option>                
                                                <option value="SN" data-alt="" data-code="+221">Senegal</option>                
                                                <option value="SC" data-alt="" data-code="+248">Seychelles</option>                
                                                <option value="SL" data-alt="" data-code="+232">Sierra Leone</option>                
                                                <option value="SG" data-alt="" data-code="+65">Singapore</option>                
                                                <option value="SK" data-alt="" data-code="+421">Slovakia</option>                
                                                <option value="SI" data-alt="" data-code="+386">Slovenia</option>                
                                                <option value="SB" data-alt="" data-code="+677">Solomon Islands</option>                
                                                <option value="SO" data-alt="" data-code="+252">Somalia</option>                
                                                <option value="ZA" data-alt="" data-code="+27">South Africa</option>                
                                                <option value="KR" data-alt="South Korea" data-code="+82">South Korea</option>                
                                                <option value="ES" data-alt="" data-code="+34">Spain</option>                
                                                <option value="LK" data-alt="" data-code="+94">Sri Lanka</option>                
                                                <option value="SD" data-alt="" data-code="+249">Sudan</option>                
                                                <option value="SR" data-alt="" data-code="+597">Suriname</option>                
                                                <option value="SZ" data-alt="" data-code="+268">Swaziland</option>                
                                                <option value="SE" data-alt="" data-code="+46">Sweden</option>                
                                                <option value="CH" data-alt="" data-code="+41">Switzerland</option>                
                                                <option value="SY" data-alt="" data-code="+963">Syrian Arab Republic</option>                
                                                <option value="TW" data-alt="" data-code="+886">Taiwan, Province of China</option>                
                                                <option value="TJ" data-alt="" data-code="+992">Tajikistan</option>                
                                                <option value="TZ" data-alt="" data-code="+255">Tanzania, United Republic of</option>                
                                                <option value="TH" data-alt="" data-code="+66">Thailand</option>                
                                                <option value="TL" data-alt="" data-code="+670">Timor-Leste</option>                
                                                <option value="TG" data-alt="" data-code="+228">Togo</option>                
                                                <option value="TK" data-alt="" data-code="+690">Tokelau</option>                
                                                <option value="TO" data-alt="" data-code="+676">Tonga</option>                
                                                <option value="TT" data-alt="" data-code="+1 868">Trinidad and Tobago</option>                
                                                <option value="TN" data-alt="" data-code="+216">Tunisia</option>                
                                                <option value="TR" data-alt="" data-code="+90">Turkey</option>                
                                                <option value="TM" data-alt="" data-code="+993">Turkmenistan</option>                
                                                <option value="TC" data-alt="" data-code="+1 649">Turks and Caicos Islands</option>                
                                                <option value="TV" data-alt="" data-code="+688">Tuvalu</option>                
                                                <option value="UG" data-alt="" data-code="+256">Uganda</option>                
                                                <option value="GB" data-alt="United Kingdom England Wales Scotland Ireland Northern Ireland NI Great Britain" data-code="+44" selected="selected">UK</option>                
                                                <option value="UA" data-alt="" data-code="+380">Ukraine</option>                
                                                <option value="AE" data-alt="uae" data-code="+971">United Arab Emirates</option>                
                                                <option value="US" data-alt="US USA America" data-code="+1">United States</option>                
                                                <option value="UY" data-alt="" data-code="+598">Uruguay</option>                
                                                <option value="UZ" data-alt="" data-code="+998">Uzbekistan</option>                
                                                <option value="VU" data-alt="" data-code="+678">Vanuatu</option>                
                                                <option value="VE" data-alt="" data-code="+58">Venezuela</option>                
                                                <option value="VN" data-alt="" data-code="+84">Viet Nam</option>                
                                                <option value="VG" data-alt="" data-code="+1 284">Virgin Islands, British</option>                
                                                <option value="VI" data-alt="" data-code="+1 340">Virgin Islands, U.s.</option>                
                                                <option value="WF" data-alt="" data-code="+681">Wallis and Futuna</option>                
                                                <option value="YE" data-alt="" data-code="+967">Yemen</option>                
                                                <option value="ZM" data-alt="" data-code="+260">Zambia</option>                
                                                <option value="ZW" data-alt="" data-code="+263">Zimbabwe</option>                
                                            </select>
                                    </div>
                                </fieldset>
                                <div class='alert alert-danger' id='deliver-errors' style='display:none;'>Please complete all required fields marked red above before proceeding.</div>
                                 <a id="set-deliver-address"  class="btn btn-primary btn-block text-uppercase" href="Javascript:void(0);">Deliver To Here</a>
                            </form>
                            </div>
                            
						</div>
                       <div class='col-2-to-show' style='display:none;'></div><!-- END COL 2 TO SHOW -->
                        </div>
                    </div>
                    <!-- This section is only shown once the above addresses have been confirmed and validated -->
                    <div style="display:none;" id='step-1-controls'>
                    	
                    	<div class="row">
                            <div class="col-sm-6">
                            	
                                <a href="Javascript:void(0);" onClick="change_address(1);" class="btn btn-primary">Change</a>
                            </div>
                            <div class="col-sm-6">
                            	
                                <a href="Javascript:void(0);" onClick="change_address(2);" class="btn btn-primary">Change</a>
                            </div>
                            <div class="col-12 mt-4">
                                <h5>Your Journey</h5>
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
                        </div>
                        <div class="mt-2 clearfix">
                            <a href="Javascript:void(0);" id='go-to-step-2' class="btn btn-primary btn-lg float-md-right">Next Step >></a>
                        </div>
                    </div>
                </div>
                
                <!-- END STEP 1 -->
                
            </section>
            <section class="tab-pane" id="orderStep2" role="tabpanel">
               
               
               
               
               
               <input type='hidden' id='shipping-date' />
                <input type='hidden' id='return-date' />
               
               	<div class="lightgreytextboxlp">
                    <h5>Pick a date for your luggage to be collected</h5>
                    <h6 class="pt-2"><strong>Collection Date</strong></h6>
                    <div id="datepicker1"></div>
                    <div class="row">
                        <div class="col-4 mt-2">
                            <strong>Collection Date</strong>
                            <div id="collectiondate"></div>
                        </div>
                       
                       
                        <div id="postcodealert" class="col-12" style="display:none;">
                        	<div class="alert alert-danger" role="alert">
                                <strong><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></strong> We were unable to perform a postcode to postcode transit time check. The estimated delivery date displayed assumes capital to capital. If you are sending outside of the capital city please be aware delivery time may be longer, you can contact us for more information. We will attempt a postcode to postcode check again when your labels generate. If your estimated transit time updates you will be notified via email.
                            </div>
                        </div>
                    </div>
                    
                    
                    <div id='return-date-select'>
                      <h5>Pick a date for your return luggage to be collected</h5>
                    <h6 class="pt-2"><strong>Collection Date</strong></h6>
                    <div id="datepicker21"></div>
                    <div class="row">
                        <div class="col-4 mt-2">
                            <strong>Return Collection Date</strong>
                            <div id="returndate"></div>
                        </div>
                       
                       
                        <div id="postcodealert" class="col-12" style="display:none;">
                        	<div class="alert alert-danger" role="alert">
                                <strong><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></strong> We were unable to perform a postcode to postcode transit time check. The estimated delivery date displayed assumes capital to capital. If you are sending outside of the capital city please be aware delivery time may be longer, you can contact us for more information. We will attempt a postcode to postcode check again when your labels generate. If your estimated transit time updates you will be notified via email.
                            </div>
                        </div>
                    </div>
					</div><!-- END RETURN DATE SELECT -->
                    
                    
                </div>
                <!-- Not sure if we will do this bit as it has not been mentioned as a feature of DHL
                <div class="lightgreytextboxlp mt-4">
                	<p>As standard, collection times are 9am-6pm. Would you like to take part in our collection time slot trials? If yes, pick one below:</p>
                    <select id="collectionTime" name="collectionTime" class="form-control custom-select" placeholder="" autocomplete="false">
                    	<option value="9">09:00 - 18:00</option>
                        <option value="13">13:00 - 18:00</option>
                    </select>
                </div>
                -->
                
                  <div class="lightgreytextboxlp">
                    <h5>Service Type</h5>
                    <div class="form-check form-check-inline mt-2" id="std-cb">
                        <label class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" name="serviceType" id="service-standard" value="standard" checked>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Standard</span>
                        </label>
                    </div>
                    <div class="form-check form-check-inline mt-2">
                        <label class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" name="serviceType" id="service-express" value="express">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Express</span>
                        </label>
                    </div>
                </div>
                <div class="lightgreytextboxlp mt-4">
                
                
                
                    <div class="mt-2 clearfix">
                        <button type="button" class="btn btn-secondary btn-lg float-md-left" id="gobacktostep1">Back</button>
                        <a href="Javascript:void(0);" id='go-to-step-3' class="btn btn-primary btn-lg float-md-right">Next Step >></a>
                    </div>
                </div>
               
            </section>
            <section class="tab-pane" id="orderStep3" role="tabpanel">
            
                
                <div class="lightgreytextboxlp">
                    <h5>Unit of Measurement</h5>
                    <div class="form-check form-check-inline mt-2">
                        <label class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" name="radioMeasurement" id="Metric" value="Metric" checked>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Metric <span class="orangetext m-unit">(cm/kg)</span></span>
                        </label>
                    </div>
                    <div class="form-check form-check-inline mt-2">
                        <label class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" name="radioMeasurement" id="Imperial" value="Imperial">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Imperial <span class="orangetext m-unit">(inches/lbs)</span></span>
                        </label>
                    </div>
                </div>
                <form data-bind="submit: addStuff" id="addStuff-form">
                    <div class="lightgreytextboxlp mt-4">
                        <h5>Add an Item</h5>
                        <div class="form-group row">
                            <div class="col-md-3 mt-2">
                                <label for="itemWidth">Width <span class="orangetext m-unit lwunit">(cm)</span><span style="display:none;" class="orangetext m-unit">(inches)</span></label>
                                <input id="itemWidth" type="number" min="1" class="form-control" placeholder="1">
                            </div>
                            <div class="col-md-3 mt-2">
                                <label for="itemWidth">Height <span class="orangetext m-unit lwunit">(cm)</span><span style="display:none;" class="orangetext m-unit">(inches)</span></label>
                                <input id="itemHeight" type="number" min="1" class="form-control" placeholder="1">
                            </div>
                            <div class="col-md-3 mt-2">
                                <label for="itemWidth">Length <span class="orangetext m-unit lwunit">(cm)</span><span style="display:none;" class="orangetext m-unit">(inches)</span></label>
                                <input id="itemLength" type="number" min="1" class="form-control" placeholder="1">
                            </div>
                            <div class="col-md-3 mt-2">
                                <label for="itemWidth">Weight <span class="orangetext m-unit wunit">(kg)</span><span style="display:none;" class="orangetext m-unit">(inches)</span></label>
                                <input id="itemWeight" type="number"  min="1" class="form-control" placeholder="1" <?php if(isset($_GET['kg'])):?>value='<?php echo $_GET['kg'];?>'<?php endif;?>>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="itemWidth">Type</label>
                                <select id="itemType" class="form-control custom-select">
                                    <option selected>Please Select</option>
                                    <option value="1">Suitcase</option>
                                    <option value="2">Box</option>
                                    <option value="3">Duffle</option>
                                    <option value="4">Hold All</option>
                                    <option value="5">Golf Bag</option>
                                    <option value="6">Ski Bag</option>
                                    <option value="7">Bike Box</option>
                                    <option value="8">Other</option>
                                </select>
                                <div id="ifBox" style="display:none;" class="alert alert-info text-muted" role="alert">
                                <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong> <small>Plastic boxes are not permitted. Only use strong double walled cardboard boxes. Ensure the box can hold the weight, if in doubt send more than one box. Secure boxes by running tape around the box, not just across the top.</small>
                            </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="itemDesc">Description <small>colour/features</small></label>
                                <input id="itemDesc" type="text" class="form-control" placeholder="Optional">
                            </div>
                            
                            <div class='col-12 mt-2'>
                            	
                            	<label>Do you want to declare any customs information for this parcel?</label>
                            	
                            	<select id='customs-yes-no' class='form-control'>
                            		<option value='no' selected='selected'>No</option>
                            		<option value='yes'>Yes</option>
                            	</select>
                            	
                            	<div class='customs-info'>
                            		
                            		<div class='panel-heading orangetext'>Customs Declaration</div>
                            		<div class='form-group row'>
                            		<div class='col-md-6 mt-2'><label>Parcel value (GBP)</label><input type='text' class='form-control' id='customs-value' /></div>
                            		<div class='col-md-6 mt-2'><label>Contents Type</label>
                            		<select id='customs-contents' class='form-control'>
                            			
                           			<option selected='selected' value='GIFT'>Gift</option>
                           			<option value='SAMPLE'>Sample</option>
                           			<option value='MERCHANDISE'>Merchandise</option>
                           			<option value='HUMANITARIAN_DONATION'>Humanitarian Donation</option>
                           			<option value='RETURN_MERCHANDISE'>Return Merchandise</option>
                           			<option value='OTHER'>Other</option>
                            			
                            		</select>
                            		<div id='show-customs-other'>
                            		<label>Other:</label>
                            		<input type='text' class='form-control' id='customs-contents-other' />
										</div>
                            		</div>
                            		
									</div>
                            	</div>
                            	
                            </div>
                            
                            <div class="col-12 mt-2">
                            	<div style='display:none;'><button data-bind="disable:$parent.AddingStuff" type="submit" class="btn btn-primary">Add Bag/Box</button></div>
                            	<a class='btn btn-primary' href='Javascript:void(0);' id='add-a-bag'>Add Bag/Box</a>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="lightgreytextboxlp mt-4">
                    <h5>Your Luggage</h5>
                    <p class="pt-2">Please add your items and ensure you enter your bag's / box's measurements correctly. Additional charges will apply if your item is larger or heavier than stated here.</p>
                    <table class="table table-hover">
                    	<thead>
                        	<tr>
                          		<th class="orangetext">Description</th>
                          		<th class="orangetext hidden-xs-down">Dimensions <span class="orangetext m-unit">(cm)</span></th>
                          		<th class="orangetext hidden-xs-down">Weight <span class="orangetext m-unit">(kg)</span></th>
                          		<th></th>
                        	</tr>
                      	</thead>
                      	<tbody></tbody>
                    </table>
                    <form action="" method="post">
                        <a href="Javascript:void(0);" id="go-to-step-4"  class="btn btn-primary btn-block">Next Step >></a>
                    </form>
                </div>
               
            </section>
            <section class="tab-pane" id="orderStep4" role="tabpanel">
            	<div id='insurance-options'>	
              <div class="lightgreytextboxlp">
               
                
                    <form class="form-inline">
                        <div class="row">
                        	 <div class="col-sm-7 mt-2" style="visibility: hidden;">
                                <label style="justify-content:initial;" for="labelHolders">Send me FREE label holders (Excludes delivery)</label>
                            </div>
                            <div class="col-sm-5 mt-2" style="visibility: hidden;">
                                <select id="labelHolders" class="form-control custom-select">
                                    <option selected>Please Select</option>
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                </select>
                                <div id="labelHoldersAlert" style="display:none;" class="alert alert-info" role="alert">
                                	<strong><i class="fa fa-unlock-alt" aria-hidden="true"></i></strong> <small>You must ensure you attach your labels securely in such a way that they will not come away.</small>
                                </div>
                            </div>
                            <div class="col-sm-7 mt-2">
                                <label style="justify-content:initial;" for="postMyLabels">Post my labels to me&nbsp;&nbsp;<input type="hidden" value="<?php  echo the_field( "post_labels_price", get_the_ID());?>" id="labels_price"><span id='post-labels-price-x'><?php $price = the_field( "post_labels_price", get_the_ID()); if(isset($price) && $price!=""){  echo "£".$price; } ?></span> &nbsp;&nbsp;  (Excludes delivery)</label>
                            </div>
                            <div class="col-sm-5 mt-2">
                                <select id="postMyLabels" class="form-control custom-select">
                                    <option selected>Please Select</option>
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                </select>
                                <div id="postMyLabelsAlert" style="display:none;" class="alert alert-info" role="alert">
                                	<strong><i class="fa fa-print" aria-hidden="true"></i></strong> <small>You will be required to print and attach labels to your bag, please ensure you have access to a printer.</small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="labelPostage" style="display:none;" class="lightgreytextboxlp mt-4">
                	<h5>Select Postage Method</h5>
                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="form-check form-check-inline">
                                <label class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" name="labelpostageMethod" id="FirstPostage" value="FirstPostage" checked>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">1st Class</span>
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" name="labelpostageMethod" id="SpecialPostage" value="SpecialPostage">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Special Delivery</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <h5>Delivery Address (For Label Holders)</h5>
                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="form-check form-check-inline">
                                <label class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" name="labeldeliveryAddress" id="ExistingAddy" value="ExistingAddy" checked>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Deliver to <span id='ca-copy'></span></span>
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" name="labeldeliveryAddress" id="NewAddy" value="NewAddy">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Enter a Different Address</span>
                                </label>
                            </div>
                            <form style="display:none;" id="postageLabelsForm">
                            	<div class="form-group">
                                    <label for="label-address-type">Type Of Address</label>
                                    <select id="label-address-type" class="form-control custom-select">
                                        <option selected>Please Select</option>
                                        <option value="1">House</option>
                                        <option value="2">Apartment/Flat</option>
                                        <option value="3">Business</option>
                                        <option value="4">University Accommodation</option>
                                        <option value="5">Hotel</option>
                                        <option value="6">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label id="notHotelName3" for="custName">Full Name</label>
                                    <label id="ifHotelName3" style="display:none;" for="custName">Contact or Guest Name</label>
                                    <input id="label-customer-name" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                	<label for="custPhone">Phone Number of Contact</label>
                                    <div class="row no-gutters">
                                    	<div class="col-3">
                                            <select id="label-phone-code" name="custPhone_CountryCode" class="form-control custom-select" placeholder="" autocomplete="false" data-bind="value: CountryCode" tabindex="-1" title="">
                                                <option value=""></option>
                                                <option value="AF" data-alt="" data-code="+93">Afghanistan</option>                
                                                <option value="AL" data-alt="" data-code="+355">Albania</option>                
                                                <option value="DZ" data-alt="" data-code="+213">Algeria</option>                
                                                <option value="AS" data-alt="" data-code="+1 684">American Samoa</option>                
                                                <option value="AD" data-alt="" data-code="+376">Andorra</option>                
                                                <option value="AO" data-alt="" data-code="+244">Angola</option>                
                                                <option value="AI" data-alt="" data-code="+1 264">Anguilla</option>                
                                                <option value="AQ" data-alt="" data-code="+672">Antarctica</option>                
                                                <option value="AG" data-alt="" data-code="+1 268">Antigua and Barbuda</option>                
                                                <option value="AR" data-alt="" data-code="+54">Argentina</option>                
                                                <option value="AM" data-alt="" data-code="+374">Armenia</option>                
                                                <option value="AW" data-alt="" data-code="+297">Aruba</option>                
                                                <option value="AU" data-alt="" data-code="+61">Australia</option>                
                                                <option value="AT" data-alt="" data-code="+43">Austria</option>                
                                                <option value="AZ" data-alt="" data-code="+994">Azerbaijan</option>                
                                                <option value="BS" data-alt="" data-code="+1 242">Bahamas</option>                
                                                <option value="BH" data-alt="" data-code="+973">Bahrain</option>                
                                                <option value="BD" data-alt="" data-code="+880">Bangladesh</option>                
                                                <option value="BB" data-alt="" data-code="+1 246">Barbados</option>                
                                                <option value="BY" data-alt="" data-code="+375">Belarus</option>                
                                                <option value="BE" data-alt="" data-code="+32">Belgium</option>                
                                                <option value="BZ" data-alt="" data-code="+501">Belize</option>                
                                                <option value="BJ" data-alt="" data-code="+229">Benin</option>                
                                                <option value="BM" data-alt="" data-code="+1 441">Bermuda</option>                
                                                <option value="BT" data-alt="" data-code="+975">Bhutan</option>                
                                                <option value="BO" data-alt="" data-code="+591">Bolivia</option>                
                                                <option value="BA" data-alt="" data-code="+387">Bosnia and Herzegovina</option>                
                                                <option value="BW" data-alt="" data-code="+267">Botswana</option>                
                                                <option value="BR" data-alt="" data-code="+55">Brazil</option>                
                                                <option value="BN" data-alt="" data-code="+673">Brunei Darussalam</option>                
                                                <option value="BG" data-alt="" data-code="+359">Bulgaria</option>                
                                                <option value="BF" data-alt="" data-code="+226">Burkina Faso</option>                
                                                <option value="BI" data-alt="" data-code="+257">Burundi</option>                
                                                <option value="KH" data-alt="" data-code="+855">Cambodia</option>                
                                                <option value="CM" data-alt="" data-code="+237">Cameroon</option>                
                                                <option value="CA" data-alt="" data-code="+1">Canada</option>                
                                                <option value="CV" data-alt="" data-code="+238">Cape Verde</option>                
                                                <option value="KY" data-alt="" data-code="+1 345">Cayman Islands</option>                
                                                <option value="CF" data-alt="" data-code="+236">Central African Republic</option>                
                                                <option value="TD" data-alt="" data-code="+235">Chad</option>                
                                                <option value="CL" data-alt="" data-code="+56">Chile</option>                
                                                <option value="CN" data-alt="" data-code="+86">China</option>                
                                                <option value="CX" data-alt="" data-code="+61">Christmas Island</option>                
                                                <option value="CC" data-alt="" data-code="+61">Cocos (Keeling) Islands</option>                
                                                <option value="CO" data-alt="" data-code="+57">Colombia</option>                
                                                <option value="KM" data-alt="" data-code="+269">Comoros</option>                
                                                <option value="CG" data-alt="" data-code="+242">Congo</option>                
                                                <option value="CD" data-alt="" data-code="+243">Congo, the Democratic Republic of the</option>                
                                                <option value="CK" data-alt="" data-code="+682">Cook Islands</option>                
                                                <option value="CR" data-alt="" data-code="+506">Costa Rica</option>                
                                                <option value="CI" data-alt="" data-code="+225">Cote D'Ivoire</option>                
                                                <option value="HR" data-alt="" data-code="+385">Croatia</option>                
                                                <option value="CU" data-alt="" data-code="+53">Cuba</option>                
                                                <option value="CY" data-alt="" data-code="+357">Cyprus</option>                
                                                <option value="CZ" data-alt="" data-code="+420">Czech Republic</option>                
                                                <option value="DK" data-alt="" data-code="+45">Denmark</option>                
                                                <option value="DJ" data-alt="" data-code="+253">Djibouti</option>                
                                                <option value="DM" data-alt="" data-code="+1 767">Dominica</option>                
                                                <option value="DO" data-alt="" data-code="+1 809">Dominican Republic</option>                
                                                <option value="EC" data-alt="" data-code="+593">Ecuador</option>                
                                                <option value="EG" data-alt="" data-code="+20">Egypt</option>                
                                                <option value="SV" data-alt="" data-code="+503">El Salvador</option>                
                                                <option value="GQ" data-alt="" data-code="+240">Equatorial Guinea</option>                
                                                <option value="ER" data-alt="" data-code="+291">Eritrea</option>                
                                                <option value="EE" data-alt="" data-code="+372">Estonia</option>                
                                                <option value="ET" data-alt="" data-code="+251">Ethiopia</option>                
                                                <option value="FK" data-alt="" data-code="+500">Falkland Islands (Malvinas)</option>                
                                                <option value="FO" data-alt="" data-code="+298">Faroe Islands</option>                
                                                <option value="FJ" data-alt="" data-code="+679">Fiji</option>                
                                                <option value="FI" data-alt="" data-code="+358">Finland</option>                
                                                <option value="FR" data-alt="" data-code="+33">France</option>                
                                                <option value="PF" data-alt="" data-code="+689">French Polynesia</option>                
                                                <option value="GA" data-alt="" data-code="+241">Gabon</option>                
                                                <option value="GM" data-alt="" data-code="+220">Gambia</option>                
                                                <option value="GE" data-alt="" data-code="+995">Georgia</option>                
                                                <option value="DE" data-alt="" data-code="+49">Germany</option>                
                                                <option value="GH" data-alt="" data-code="+233">Ghana</option>                
                                                <option value="GI" data-alt="" data-code="+350">Gibraltar</option>                
                                                <option value="GR" data-alt="" data-code="+30">Greece</option>                
                                                <option value="GL" data-alt="" data-code="+299">Greenland</option>                
                                                <option value="GD" data-alt="" data-code="+1 473">Grenada</option>                
                                                <option value="GU" data-alt="" data-code="+1 671">Guam</option>                
                                                <option value="GT" data-alt="" data-code="+502">Guatemala</option>                
                                                <option value="GN" data-alt="" data-code="+224">Guinea</option>                
                                                <option value="GW" data-alt="" data-code="+245">Guinea-Bissau</option>                
                                                <option value="GY" data-alt="" data-code="+592">Guyana</option>                
                                                <option value="HT" data-alt="" data-code="+509">Haiti</option>                
                                                <option value="VA" data-alt="" data-code="+39">Holy See (Vatican City State)</option>                
                                                <option value="HN" data-alt="" data-code="+504">Honduras</option>                
                                                <option value="HK" data-alt="" data-code="+852">Hong Kong</option>                
                                                <option value="HU" data-alt="" data-code="+36">Hungary</option>                
                                                <option value="IS" data-alt="" data-code="+354">Iceland</option>                
                                                <option value="IN" data-alt="" data-code="+91">India</option>                
                                                <option value="ID" data-alt="" data-code="+62">Indonesia</option>                
                                                <option value="IR" data-alt="" data-code="+98">Iran, Islamic Republic of</option>                
                                                <option value="IQ" data-alt="" data-code="+964">Iraq</option>                
                                                <option value="IE" data-alt="" data-code="+353">Ireland</option>                
                                                <option value="IL" data-alt="" data-code="+972">Israel</option>                
                                                <option value="IT" data-alt="" data-code="+39">Italy</option>                
                                                <option value="JM" data-alt="" data-code="+1 876">Jamaica</option>                
                                                <option value="JP" data-alt="" data-code="+81">Japan</option>                
                                                <option value="JO" data-alt="" data-code="+962">Jordan</option>                
                                                <option value="KZ" data-alt="" data-code="+7">Kazakhstan</option>                
                                                <option value="KE" data-alt="" data-code="+254">Kenya</option>                
                                                <option value="KI" data-alt="" data-code="+686">Kiribati</option>                
                                                <option value="KW" data-alt="" data-code="+965">Kuwait</option>                
                                                <option value="KG" data-alt="" data-code="+996">Kyrgyzstan</option>                
                                                <option value="LA" data-alt="" data-code="+856">Lao People's Democratic Republic</option>                
                                                <option value="LV" data-alt="" data-code="+371">Latvia</option>                
                                                <option value="LB" data-alt="" data-code="+961">Lebanon</option>                
                                                <option value="LS" data-alt="" data-code="+266">Lesotho</option>                
                                                <option value="LR" data-alt="" data-code="+231">Liberia</option>                
                                                <option value="LY" data-alt="" data-code="+218">Libyan Arab Jamahiriya</option>                
                                                <option value="LI" data-alt="" data-code="+423">Liechtenstein</option>                
                                                <option value="LT" data-alt="" data-code="+370">Lithuania</option>                
                                                <option value="LU" data-alt="" data-code="+352">Luxembourg</option>                
                                                <option value="MO" data-alt="" data-code="+853">Macao</option>                
                                                <option value="MK" data-alt="" data-code="+389">Macedonia, the Former Yugoslav Republic of</option>                
                                                <option value="MG" data-alt="" data-code="+261">Madagascar</option>                
                                                <option value="MW" data-alt="" data-code="+265">Malawi</option>                
                                                <option value="MY" data-alt="" data-code="+60">Malaysia</option>                
                                                <option value="MV" data-alt="" data-code="+960">Maldives</option>                
                                                <option value="ML" data-alt="" data-code="+223">Mali</option>                
                                                <option value="MT" data-alt="" data-code="+356">Malta</option>                
                                                <option value="MH" data-alt="" data-code="+692">Marshall Islands</option>                
                                                <option value="MR" data-alt="" data-code="+222">Mauritania</option>                
                                                <option value="MU" data-alt="" data-code="+230">Mauritius</option>                
                                                <option value="YT" data-alt="" data-code="+262">Mayotte</option>                
                                                <option value="MX" data-alt="" data-code="+52">Mexico</option>                
                                                <option value="FM" data-alt="" data-code="+691">Micronesia, Federated States of</option>                
                                                <option value="MD" data-alt="" data-code="+373">Moldova, Republic of</option>                
                                                <option value="MC" data-alt="" data-code="+377">Monaco</option>                
                                                <option value="MN" data-alt="" data-code="+976">Mongolia</option>                
                                                <option value="MS" data-alt="" data-code="+1 664">Montserrat</option>                
                                                <option value="MA" data-alt="" data-code="+212">Morocco</option>                
                                                <option value="MZ" data-alt="" data-code="+258">Mozambique</option>                
                                                <option value="MM" data-alt="" data-code="+95">Myanmar</option>                
                                                <option value="NA" data-alt="" data-code="+264">Namibia</option>                
                                                <option value="NR" data-alt="" data-code="+674">Nauru</option>                
                                                <option value="NP" data-alt="" data-code="+977">Nepal</option>                
                                                <option value="NL" data-alt="" data-code="+31">Netherlands</option>                
                                                <option value="AN" data-alt="" data-code="+599">Netherlands Antilles</option>                
                                                <option value="NC" data-alt="" data-code="+687">New Caledonia</option>                
                                                <option value="NZ" data-alt="" data-code="+64">New Zealand</option>                
                                                <option value="NI" data-alt="" data-code="+505">Nicaragua</option>                
                                                <option value="NE" data-alt="" data-code="+227">Niger</option>                
                                                <option value="NG" data-alt="" data-code="+234">Nigeria</option>                
                                                <option value="NU" data-alt="" data-code="+683">Niue</option>                
                                                <option value="KP" data-alt="" data-code="+850">North Korea</option>                
                                                <option value="MP" data-alt="" data-code="+1 670">Northern Mariana Islands</option>                
                                                <option value="NO" data-alt="" data-code="+47">Norway</option>                
                                                <option value="OM" data-alt="" data-code="+968">Oman</option>                
                                                <option value="PK" data-alt="" data-code="+92">Pakistan</option>                
                                                <option value="PW" data-alt="" data-code="+680">Palau</option>                
                                                <option value="PA" data-alt="" data-code="+507">Panama</option>                
                                                <option value="PG" data-alt="" data-code="+675">Papua New Guinea</option>                
                                                <option value="PY" data-alt="" data-code="+595">Paraguay</option>                
                                                <option value="PE" data-alt="" data-code="+51">Peru</option>                
                                                <option value="PH" data-alt="" data-code="+63">Philippines</option>                
                                                <option value="PN" data-alt="" data-code="+870">Pitcairn</option>                
                                                <option value="PL" data-alt="" data-code="+48">Poland</option>                
                                                <option value="PT" data-alt="" data-code="+351">Portugal</option>                
                                                <option value="PR" data-alt="" data-code="+1">Puerto Rico</option>                
                                                <option value="QA" data-alt="" data-code="+974">Qatar</option>                
                                                <option value="RO" data-alt="" data-code="+40">Romania</option>                
                                                <option value="RU" data-alt="" data-code="+7">Russian Federation</option>                
                                                <option value="RW" data-alt="" data-code="+250">Rwanda</option>                
                                                <option value="SH" data-alt="" data-code="+290">Saint Helena</option>                
                                                <option value="KN" data-alt="" data-code="+1 869">Saint Kitts and Nevis</option>                
                                                <option value="LC" data-alt="" data-code="+1 758">Saint Lucia</option>                
                                                <option value="PM" data-alt="" data-code="+508">Saint Pierre and Miquelon</option>                
                                                <option value="VC" data-alt="" data-code="+1 784">Saint Vincent and the Grenadines</option>                
                                                <option value="WS" data-alt="" data-code="+685">Samoa</option>                
                                                <option value="SM" data-alt="" data-code="+378">San Marino</option>                
                                                <option value="ST" data-alt="" data-code="+239">Sao Tome and Principe</option>                
                                                <option value="SA" data-alt="" data-code="+966">Saudi Arabia</option>                
                                                <option value="SN" data-alt="" data-code="+221">Senegal</option>                
                                                <option value="SC" data-alt="" data-code="+248">Seychelles</option>                
                                                <option value="SL" data-alt="" data-code="+232">Sierra Leone</option>                
                                                <option value="SG" data-alt="" data-code="+65">Singapore</option>                
                                                <option value="SK" data-alt="" data-code="+421">Slovakia</option>                
                                                <option value="SI" data-alt="" data-code="+386">Slovenia</option>                
                                                <option value="SB" data-alt="" data-code="+677">Solomon Islands</option>                
                                                <option value="SO" data-alt="" data-code="+252">Somalia</option>                
                                                <option value="ZA" data-alt="" data-code="+27">South Africa</option>                
                                                <option value="KR" data-alt="South Korea" data-code="+82">South Korea</option>                
                                                <option value="ES" data-alt="" data-code="+34">Spain</option>                
                                                <option value="LK" data-alt="" data-code="+94">Sri Lanka</option>                
                                                <option value="SD" data-alt="" data-code="+249">Sudan</option>                
                                                <option value="SR" data-alt="" data-code="+597">Suriname</option>                
                                                <option value="SZ" data-alt="" data-code="+268">Swaziland</option>                
                                                <option value="SE" data-alt="" data-code="+46">Sweden</option>                
                                                <option value="CH" data-alt="" data-code="+41">Switzerland</option>                
                                                <option value="SY" data-alt="" data-code="+963">Syrian Arab Republic</option>                
                                                <option value="TW" data-alt="" data-code="+886">Taiwan, Province of China</option>                
                                                <option value="TJ" data-alt="" data-code="+992">Tajikistan</option>                
                                                <option value="TZ" data-alt="" data-code="+255">Tanzania, United Republic of</option>                
                                                <option value="TH" data-alt="" data-code="+66">Thailand</option>                
                                                <option value="TL" data-alt="" data-code="+670">Timor-Leste</option>                
                                                <option value="TG" data-alt="" data-code="+228">Togo</option>                
                                                <option value="TK" data-alt="" data-code="+690">Tokelau</option>                
                                                <option value="TO" data-alt="" data-code="+676">Tonga</option>                
                                                <option value="TT" data-alt="" data-code="+1 868">Trinidad and Tobago</option>                
                                                <option value="TN" data-alt="" data-code="+216">Tunisia</option>                
                                                <option value="TR" data-alt="" data-code="+90">Turkey</option>                
                                                <option value="TM" data-alt="" data-code="+993">Turkmenistan</option>                
                                                <option value="TC" data-alt="" data-code="+1 649">Turks and Caicos Islands</option>                
                                                <option value="TV" data-alt="" data-code="+688">Tuvalu</option>                
                                                <option value="UG" data-alt="" data-code="+256">Uganda</option>                
                                                <option value="GB" data-alt="United Kingdom England Wales Scotland Ireland Northern Ireland NI Great Britain" data-code="+44" selected="selected">UK</option>                
                                                <option value="UA" data-alt="" data-code="+380">Ukraine</option>                
                                                <option value="AE" data-alt="uae" data-code="+971">United Arab Emirates</option>                
                                                <option value="US" data-alt="US USA America" data-code="+1">United States</option>                
                                                <option value="UY" data-alt="" data-code="+598">Uruguay</option>                
                                                <option value="UZ" data-alt="" data-code="+998">Uzbekistan</option>                
                                                <option value="VU" data-alt="" data-code="+678">Vanuatu</option>                
                                                <option value="VE" data-alt="" data-code="+58">Venezuela</option>                
                                                <option value="VN" data-alt="" data-code="+84">Viet Nam</option>                
                                                <option value="VG" data-alt="" data-code="+1 284">Virgin Islands, British</option>                
                                                <option value="VI" data-alt="" data-code="+1 340">Virgin Islands, U.s.</option>                
                                                <option value="WF" data-alt="" data-code="+681">Wallis and Futuna</option>                
                                                <option value="YE" data-alt="" data-code="+967">Yemen</option>                
                                                <option value="ZM" data-alt="" data-code="+260">Zambia</option>                
                                                <option value="ZW" data-alt="" data-code="+263">Zimbabwe</option>                
                                            </select>
                                        </div>
                                        <div class="col-9">
                                            <input id="label-phone" type="text" placeholder="Enter local number" class="form-control">
                                        </div>
                                    </div>
                                    <p><small class="text-muted">Unfortunately drivers cannot telephone upon arrival. Please ensure your address is fully accessible e.g. door bell working</small></p>
                                </div>
                                <div class="form-group">
                                	<div id="radiobuzzer">
                                        <p>Do you have a buzzer (other than your apartment number) or entry code?</p>
                                        <div class="form-check form-check-inline">
                                            <label class="custom-control custom-radio">
                                                <input id="yesBuzzer" name="radioBuzzer" type="radio" class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Yes</span>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="custom-control custom-radio">
                                                <input checked id="noBuzzer" name="radioBuzzer" type="radio" class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">No</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div style="display:none;" id="ifBuzzer">
                                        <label for="custBuzzer">Buzzer/Entry Code</label>
                                        <input id="label-buzzer" type="text" class="form-control" placeholder="Optional">
                                    </div>
                                </div>
                                <div class="form-group">
                                	<label for="custAddress1">Address Line 1</label>
                                    <input id="label-address-1" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                	<label for="custAddress2">Address Line 2</label>
                                    <input id="label-address-2" type="text" class="form-control" placeholder="Optional">
                                </div>
                                <div class="form-group">
                                	<label for="custCity">City</label>
                                    <input id="label-city" type="text" class="form-control">
                                </div>
                                 <div class="form-group">
                                	<label for="label-postcode">Post/Zip Code</label>
                                    <input id="label-postcode" type="text" class="form-control">
                                </div>
                                <fieldset disabled>
                                    <div class="form-group">
                                      	<label for="Origin_CountryIso">Country</label>
                                        <select id="label-country" name="custPhone_CountryCode" class="form-control custom-select" placeholder="" autocomplete="false" data-bind="value: CountryCode" tabindex="-1" title="">
                                                <option value=""></option>
                                                <option value="AF" data-alt="" data-code="+93">Afghanistan</option>                
                                                <option value="AL" data-alt="" data-code="+355">Albania</option>                
                                                <option value="DZ" data-alt="" data-code="+213">Algeria</option>                
                                                <option value="AS" data-alt="" data-code="+1 684">American Samoa</option>                
                                                <option value="AD" data-alt="" data-code="+376">Andorra</option>                
                                                <option value="AO" data-alt="" data-code="+244">Angola</option>                
                                                <option value="AI" data-alt="" data-code="+1 264">Anguilla</option>                
                                                <option value="AQ" data-alt="" data-code="+672">Antarctica</option>                
                                                <option value="AG" data-alt="" data-code="+1 268">Antigua and Barbuda</option>                
                                                <option value="AR" data-alt="" data-code="+54">Argentina</option>                
                                                <option value="AM" data-alt="" data-code="+374">Armenia</option>                
                                                <option value="AW" data-alt="" data-code="+297">Aruba</option>                
                                                <option value="AU" data-alt="" data-code="+61">Australia</option>                
                                                <option value="AT" data-alt="" data-code="+43">Austria</option>                
                                                <option value="AZ" data-alt="" data-code="+994">Azerbaijan</option>                
                                                <option value="BS" data-alt="" data-code="+1 242">Bahamas</option>                
                                                <option value="BH" data-alt="" data-code="+973">Bahrain</option>                
                                                <option value="BD" data-alt="" data-code="+880">Bangladesh</option>                
                                                <option value="BB" data-alt="" data-code="+1 246">Barbados</option>                
                                                <option value="BY" data-alt="" data-code="+375">Belarus</option>                
                                                <option value="BE" data-alt="" data-code="+32">Belgium</option>                
                                                <option value="BZ" data-alt="" data-code="+501">Belize</option>                
                                                <option value="BJ" data-alt="" data-code="+229">Benin</option>                
                                                <option value="BM" data-alt="" data-code="+1 441">Bermuda</option>                
                                                <option value="BT" data-alt="" data-code="+975">Bhutan</option>                
                                                <option value="BO" data-alt="" data-code="+591">Bolivia</option>                
                                                <option value="BA" data-alt="" data-code="+387">Bosnia and Herzegovina</option>                
                                                <option value="BW" data-alt="" data-code="+267">Botswana</option>                
                                                <option value="BR" data-alt="" data-code="+55">Brazil</option>                
                                                <option value="BN" data-alt="" data-code="+673">Brunei Darussalam</option>                
                                                <option value="BG" data-alt="" data-code="+359">Bulgaria</option>                
                                                <option value="BF" data-alt="" data-code="+226">Burkina Faso</option>                
                                                <option value="BI" data-alt="" data-code="+257">Burundi</option>                
                                                <option value="KH" data-alt="" data-code="+855">Cambodia</option>                
                                                <option value="CM" data-alt="" data-code="+237">Cameroon</option>                
                                                <option value="CA" data-alt="" data-code="+1">Canada</option>                
                                                <option value="CV" data-alt="" data-code="+238">Cape Verde</option>                
                                                <option value="KY" data-alt="" data-code="+1 345">Cayman Islands</option>                
                                                <option value="CF" data-alt="" data-code="+236">Central African Republic</option>                
                                                <option value="TD" data-alt="" data-code="+235">Chad</option>                
                                                <option value="CL" data-alt="" data-code="+56">Chile</option>                
                                                <option value="CN" data-alt="" data-code="+86">China</option>                
                                                <option value="CX" data-alt="" data-code="+61">Christmas Island</option>                
                                                <option value="CC" data-alt="" data-code="+61">Cocos (Keeling) Islands</option>                
                                                <option value="CO" data-alt="" data-code="+57">Colombia</option>                
                                                <option value="KM" data-alt="" data-code="+269">Comoros</option>                
                                                <option value="CG" data-alt="" data-code="+242">Congo</option>                
                                                <option value="CD" data-alt="" data-code="+243">Congo, the Democratic Republic of the</option>                
                                                <option value="CK" data-alt="" data-code="+682">Cook Islands</option>                
                                                <option value="CR" data-alt="" data-code="+506">Costa Rica</option>                
                                                <option value="CI" data-alt="" data-code="+225">Cote D'Ivoire</option>                
                                                <option value="HR" data-alt="" data-code="+385">Croatia</option>                
                                                <option value="CU" data-alt="" data-code="+53">Cuba</option>                
                                                <option value="CY" data-alt="" data-code="+357">Cyprus</option>                
                                                <option value="CZ" data-alt="" data-code="+420">Czech Republic</option>                
                                                <option value="DK" data-alt="" data-code="+45">Denmark</option>                
                                                <option value="DJ" data-alt="" data-code="+253">Djibouti</option>                
                                                <option value="DM" data-alt="" data-code="+1 767">Dominica</option>                
                                                <option value="DO" data-alt="" data-code="+1 809">Dominican Republic</option>                
                                                <option value="EC" data-alt="" data-code="+593">Ecuador</option>                
                                                <option value="EG" data-alt="" data-code="+20">Egypt</option>                
                                                <option value="SV" data-alt="" data-code="+503">El Salvador</option>                
                                                <option value="GQ" data-alt="" data-code="+240">Equatorial Guinea</option>                
                                                <option value="ER" data-alt="" data-code="+291">Eritrea</option>                
                                                <option value="EE" data-alt="" data-code="+372">Estonia</option>                
                                                <option value="ET" data-alt="" data-code="+251">Ethiopia</option>                
                                                <option value="FK" data-alt="" data-code="+500">Falkland Islands (Malvinas)</option>                
                                                <option value="FO" data-alt="" data-code="+298">Faroe Islands</option>                
                                                <option value="FJ" data-alt="" data-code="+679">Fiji</option>                
                                                <option value="FI" data-alt="" data-code="+358">Finland</option>                
                                                <option value="FR" data-alt="" data-code="+33">France</option>                
                                                <option value="PF" data-alt="" data-code="+689">French Polynesia</option>                
                                                <option value="GA" data-alt="" data-code="+241">Gabon</option>                
                                                <option value="GM" data-alt="" data-code="+220">Gambia</option>                
                                                <option value="GE" data-alt="" data-code="+995">Georgia</option>                
                                                <option value="DE" data-alt="" data-code="+49">Germany</option>                
                                                <option value="GH" data-alt="" data-code="+233">Ghana</option>                
                                                <option value="GI" data-alt="" data-code="+350">Gibraltar</option>                
                                                <option value="GR" data-alt="" data-code="+30">Greece</option>                
                                                <option value="GL" data-alt="" data-code="+299">Greenland</option>                
                                                <option value="GD" data-alt="" data-code="+1 473">Grenada</option>                
                                                <option value="GU" data-alt="" data-code="+1 671">Guam</option>                
                                                <option value="GT" data-alt="" data-code="+502">Guatemala</option>                
                                                <option value="GN" data-alt="" data-code="+224">Guinea</option>                
                                                <option value="GW" data-alt="" data-code="+245">Guinea-Bissau</option>                
                                                <option value="GY" data-alt="" data-code="+592">Guyana</option>                
                                                <option value="HT" data-alt="" data-code="+509">Haiti</option>                
                                                <option value="VA" data-alt="" data-code="+39">Holy See (Vatican City State)</option>                
                                                <option value="HN" data-alt="" data-code="+504">Honduras</option>                
                                                <option value="HK" data-alt="" data-code="+852">Hong Kong</option>                
                                                <option value="HU" data-alt="" data-code="+36">Hungary</option>                
                                                <option value="IS" data-alt="" data-code="+354">Iceland</option>                
                                                <option value="IN" data-alt="" data-code="+91">India</option>                
                                                <option value="ID" data-alt="" data-code="+62">Indonesia</option>                
                                                <option value="IR" data-alt="" data-code="+98">Iran, Islamic Republic of</option>                
                                                <option value="IQ" data-alt="" data-code="+964">Iraq</option>                
                                                <option value="IE" data-alt="" data-code="+353">Ireland</option>                
                                                <option value="IL" data-alt="" data-code="+972">Israel</option>                
                                                <option value="IT" data-alt="" data-code="+39">Italy</option>                
                                                <option value="JM" data-alt="" data-code="+1 876">Jamaica</option>                
                                                <option value="JP" data-alt="" data-code="+81">Japan</option>                
                                                <option value="JO" data-alt="" data-code="+962">Jordan</option>                
                                                <option value="KZ" data-alt="" data-code="+7">Kazakhstan</option>                
                                                <option value="KE" data-alt="" data-code="+254">Kenya</option>                
                                                <option value="KI" data-alt="" data-code="+686">Kiribati</option>                
                                                <option value="KW" data-alt="" data-code="+965">Kuwait</option>                
                                                <option value="KG" data-alt="" data-code="+996">Kyrgyzstan</option>                
                                                <option value="LA" data-alt="" data-code="+856">Lao People's Democratic Republic</option>                
                                                <option value="LV" data-alt="" data-code="+371">Latvia</option>                
                                                <option value="LB" data-alt="" data-code="+961">Lebanon</option>                
                                                <option value="LS" data-alt="" data-code="+266">Lesotho</option>                
                                                <option value="LR" data-alt="" data-code="+231">Liberia</option>                
                                                <option value="LY" data-alt="" data-code="+218">Libyan Arab Jamahiriya</option>                
                                                <option value="LI" data-alt="" data-code="+423">Liechtenstein</option>                
                                                <option value="LT" data-alt="" data-code="+370">Lithuania</option>                
                                                <option value="LU" data-alt="" data-code="+352">Luxembourg</option>                
                                                <option value="MO" data-alt="" data-code="+853">Macao</option>                
                                                <option value="MK" data-alt="" data-code="+389">Macedonia, the Former Yugoslav Republic of</option>                
                                                <option value="MG" data-alt="" data-code="+261">Madagascar</option>                
                                                <option value="MW" data-alt="" data-code="+265">Malawi</option>                
                                                <option value="MY" data-alt="" data-code="+60">Malaysia</option>                
                                                <option value="MV" data-alt="" data-code="+960">Maldives</option>                
                                                <option value="ML" data-alt="" data-code="+223">Mali</option>                
                                                <option value="MT" data-alt="" data-code="+356">Malta</option>                
                                                <option value="MH" data-alt="" data-code="+692">Marshall Islands</option>                
                                                <option value="MR" data-alt="" data-code="+222">Mauritania</option>                
                                                <option value="MU" data-alt="" data-code="+230">Mauritius</option>                
                                                <option value="YT" data-alt="" data-code="+262">Mayotte</option>                
                                                <option value="MX" data-alt="" data-code="+52">Mexico</option>                
                                                <option value="FM" data-alt="" data-code="+691">Micronesia, Federated States of</option>                
                                                <option value="MD" data-alt="" data-code="+373">Moldova, Republic of</option>                
                                                <option value="MC" data-alt="" data-code="+377">Monaco</option>                
                                                <option value="MN" data-alt="" data-code="+976">Mongolia</option>                
                                                <option value="MS" data-alt="" data-code="+1 664">Montserrat</option>                
                                                <option value="MA" data-alt="" data-code="+212">Morocco</option>                
                                                <option value="MZ" data-alt="" data-code="+258">Mozambique</option>                
                                                <option value="MM" data-alt="" data-code="+95">Myanmar</option>                
                                                <option value="NA" data-alt="" data-code="+264">Namibia</option>                
                                                <option value="NR" data-alt="" data-code="+674">Nauru</option>                
                                                <option value="NP" data-alt="" data-code="+977">Nepal</option>                
                                                <option value="NL" data-alt="" data-code="+31">Netherlands</option>                
                                                <option value="AN" data-alt="" data-code="+599">Netherlands Antilles</option>                
                                                <option value="NC" data-alt="" data-code="+687">New Caledonia</option>                
                                                <option value="NZ" data-alt="" data-code="+64">New Zealand</option>                
                                                <option value="NI" data-alt="" data-code="+505">Nicaragua</option>                
                                                <option value="NE" data-alt="" data-code="+227">Niger</option>                
                                                <option value="NG" data-alt="" data-code="+234">Nigeria</option>                
                                                <option value="NU" data-alt="" data-code="+683">Niue</option>                
                                                <option value="KP" data-alt="" data-code="+850">North Korea</option>                
                                                <option value="MP" data-alt="" data-code="+1 670">Northern Mariana Islands</option>                
                                                <option value="NO" data-alt="" data-code="+47">Norway</option>                
                                                <option value="OM" data-alt="" data-code="+968">Oman</option>                
                                                <option value="PK" data-alt="" data-code="+92">Pakistan</option>                
                                                <option value="PW" data-alt="" data-code="+680">Palau</option>                
                                                <option value="PA" data-alt="" data-code="+507">Panama</option>                
                                                <option value="PG" data-alt="" data-code="+675">Papua New Guinea</option>                
                                                <option value="PY" data-alt="" data-code="+595">Paraguay</option>                
                                                <option value="PE" data-alt="" data-code="+51">Peru</option>                
                                                <option value="PH" data-alt="" data-code="+63">Philippines</option>                
                                                <option value="PN" data-alt="" data-code="+870">Pitcairn</option>                
                                                <option value="PL" data-alt="" data-code="+48">Poland</option>                
                                                <option value="PT" data-alt="" data-code="+351">Portugal</option>                
                                                <option value="PR" data-alt="" data-code="+1">Puerto Rico</option>                
                                                <option value="QA" data-alt="" data-code="+974">Qatar</option>                
                                                <option value="RO" data-alt="" data-code="+40">Romania</option>                
                                                <option value="RU" data-alt="" data-code="+7">Russian Federation</option>                
                                                <option value="RW" data-alt="" data-code="+250">Rwanda</option>                
                                                <option value="SH" data-alt="" data-code="+290">Saint Helena</option>                
                                                <option value="KN" data-alt="" data-code="+1 869">Saint Kitts and Nevis</option>                
                                                <option value="LC" data-alt="" data-code="+1 758">Saint Lucia</option>                
                                                <option value="PM" data-alt="" data-code="+508">Saint Pierre and Miquelon</option>                
                                                <option value="VC" data-alt="" data-code="+1 784">Saint Vincent and the Grenadines</option>                
                                                <option value="WS" data-alt="" data-code="+685">Samoa</option>                
                                                <option value="SM" data-alt="" data-code="+378">San Marino</option>                
                                                <option value="ST" data-alt="" data-code="+239">Sao Tome and Principe</option>                
                                                <option value="SA" data-alt="" data-code="+966">Saudi Arabia</option>                
                                                <option value="SN" data-alt="" data-code="+221">Senegal</option>                
                                                <option value="SC" data-alt="" data-code="+248">Seychelles</option>                
                                                <option value="SL" data-alt="" data-code="+232">Sierra Leone</option>                
                                                <option value="SG" data-alt="" data-code="+65">Singapore</option>                
                                                <option value="SK" data-alt="" data-code="+421">Slovakia</option>                
                                                <option value="SI" data-alt="" data-code="+386">Slovenia</option>                
                                                <option value="SB" data-alt="" data-code="+677">Solomon Islands</option>                
                                                <option value="SO" data-alt="" data-code="+252">Somalia</option>                
                                                <option value="ZA" data-alt="" data-code="+27">South Africa</option>                
                                                <option value="KR" data-alt="South Korea" data-code="+82">South Korea</option>                
                                                <option value="ES" data-alt="" data-code="+34">Spain</option>                
                                                <option value="LK" data-alt="" data-code="+94">Sri Lanka</option>                
                                                <option value="SD" data-alt="" data-code="+249">Sudan</option>                
                                                <option value="SR" data-alt="" data-code="+597">Suriname</option>                
                                                <option value="SZ" data-alt="" data-code="+268">Swaziland</option>                
                                                <option value="SE" data-alt="" data-code="+46">Sweden</option>                
                                                <option value="CH" data-alt="" data-code="+41">Switzerland</option>                
                                                <option value="SY" data-alt="" data-code="+963">Syrian Arab Republic</option>                
                                                <option value="TW" data-alt="" data-code="+886">Taiwan, Province of China</option>                
                                                <option value="TJ" data-alt="" data-code="+992">Tajikistan</option>                
                                                <option value="TZ" data-alt="" data-code="+255">Tanzania, United Republic of</option>                
                                                <option value="TH" data-alt="" data-code="+66">Thailand</option>                
                                                <option value="TL" data-alt="" data-code="+670">Timor-Leste</option>                
                                                <option value="TG" data-alt="" data-code="+228">Togo</option>                
                                                <option value="TK" data-alt="" data-code="+690">Tokelau</option>                
                                                <option value="TO" data-alt="" data-code="+676">Tonga</option>                
                                                <option value="TT" data-alt="" data-code="+1 868">Trinidad and Tobago</option>                
                                                <option value="TN" data-alt="" data-code="+216">Tunisia</option>                
                                                <option value="TR" data-alt="" data-code="+90">Turkey</option>                
                                                <option value="TM" data-alt="" data-code="+993">Turkmenistan</option>                
                                                <option value="TC" data-alt="" data-code="+1 649">Turks and Caicos Islands</option>                
                                                <option value="TV" data-alt="" data-code="+688">Tuvalu</option>                
                                                <option value="UG" data-alt="" data-code="+256">Uganda</option>                
                                                <option value="GB" data-alt="United Kingdom England Wales Scotland Ireland Northern Ireland NI Great Britain" data-code="+44" selected="selected">UK</option>                
                                                <option value="UA" data-alt="" data-code="+380">Ukraine</option>                
                                                <option value="AE" data-alt="uae" data-code="+971">United Arab Emirates</option>                
                                                <option value="US" data-alt="US USA America" data-code="+1">United States</option>                
                                                <option value="UY" data-alt="" data-code="+598">Uruguay</option>                
                                                <option value="UZ" data-alt="" data-code="+998">Uzbekistan</option>                
                                                <option value="VU" data-alt="" data-code="+678">Vanuatu</option>                
                                                <option value="VE" data-alt="" data-code="+58">Venezuela</option>                
                                                <option value="VN" data-alt="" data-code="+84">Viet Nam</option>                
                                                <option value="VG" data-alt="" data-code="+1 284">Virgin Islands, British</option>                
                                                <option value="VI" data-alt="" data-code="+1 340">Virgin Islands, U.s.</option>                
                                                <option value="WF" data-alt="" data-code="+681">Wallis and Futuna</option>                
                                                <option value="YE" data-alt="" data-code="+967">Yemen</option>                
                                                <option value="ZM" data-alt="" data-code="+260">Zambia</option>                
                                                <option value="ZW" data-alt="" data-code="+263">Zimbabwe</option>                
                                            </select>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="lightgreytextboxlp mt-4" style="display:none;">
                	<h5>Cover</h5>
                    <form class="form-inline">
                        <div class="row">
                        	<div class="col-sm-7 mt-2">
                                <label style="justify-content:initial;" for="ccCover">Cancellation and Change Cover&nbsp;&nbsp;<span id='cancellation-cover-price'>£4.00</span>&nbsp;&nbsp;</label>
                            </div>
                            <div class="col-sm-5 mt-2">
                                <select id="ccCover" class="form-control custom-select">
                                    <option selected>Please Select</option>
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                </select>
                            </div>
                            <div class="col-sm-7 mt-2">
                                <label style="justify-content:initial;" for="coverLevel">Select the level of cover (per bag) you require</label>
                            </div>
                            <div class="col-sm-5 mt-2">
                                <select id="coverLevel" class="form-control custom-select">
                                    <option selected>Please Select</option>
                                    <option value="1"><span class="m-unit">&pound;60</span> Cover (Free)</option>
                                    <option value="2"><span class="m-unit">&pound;125</span> Cover <span class="covercost">(&pound;5.00)</span></option>
                                    <option value="3"><span class="m-unit">&pound;300</span> Cover <span class="covercost">(&pound;10.00)</span></option>
                                    <option value="4"><span class="m-unit">&pound;600</span> Cover <span class="covercost">(&pound;20.00)</span></option>
                                </select>
                            </div>
                        </div>
                    </form>
                    <small>Express My Stuff cover does not replace external insurance appropriate to your own travel needs. Cover does not include delay (including purchasing replacement items), trip cancellation and does not cover items we advise are prohibited or sent at your own risk. For more information click here.</small>
                    
                    <h4 class='orangeheader' style='margin-top:20px;'>WHAT IS CANCELLATION &amp; CHANGE COVER?</h4>
                    <p><small>Cancellation cover allows an order to be cancelled for a full no quibble refund of your transit costs, even if you miss the driver.

The refund will be processed less any additional purchased products already posted, such as labels or label holder postage. The cost of the cancellation cover itself or complimentary cover will also not be refunded.

Cancellation cover fees are displayed during checkout, it is currently approximately £4.00 for every £50.00 spent.

Customers with cancellation cover can also change their collection or delivery address, or collection date, at any time prior to collection once without an admin fee being applied. For no fee to apply the change must be made online via your account.</small></p>

<p><small>If an address change increases the total order value, for example if you update your collection to take place from a remote area, the additional transit cost will also be due. Address changes must be within the same country.

Addresses and the collection date can be updated once without admin fees, any subsequent changes will incur an admin fee.</small></p>
                </div>
                <div class="lightgreytextboxlp mt-4" style="display:none;">
                	<h5>Tracking</h5>
                    <p>Once your order is complete we recommend registering an account with us to track your items or you can use <a href="http://www.dhl.co.uk/en/express/tracking.html">DHL's own tracking system</a>.</p>
                </div>
                <div class="lightgreytextboxlp mt-4">
                	<h5>Why are you using us?</h5>
                    <select id="whyUs" class="form-control custom-select">
                        <option selected>Please Select</option>
                        <option value="1">To University</option>
                        <option value="2">From University</option>
                        <option value="3">Studying Abroad</option>
                        <option value="4">Annual Holiday</option>
                        <option value="5">Short Break</option>
                        <option value="6">Holiday Home</option>
                        <option value="7">Business</option>
                        <option value="8">Golfing</option>
                        <option value="9">Skiing</option>
                        <option value="10">Cycling</option>
                        <option value="11">Expat</option>
                        <option value="12">Military</option>
                        <option value="13">Contracting</option>
                        <option value="14">Relocating</option>
                        <option value="15">Care Package</option>
                        <option value="16">Other</option>
                    </select>
                    <div class="mt-4 clearfix">
                        <button type="button" class="btn btn-secondary btn-lg float-md-left">Back</button>
                        <a href="Javascript:void(0);" class="btn btn-primary btn-lg float-md-right" id="login-register-option">Next Step >></a>
                    </div>
                </div>
                
				</div><!-- END INSURANCE OPTIONS ETC -->
               
               
                
                <div id="accountSetup" style="display:none;" class="lightgreytextboxlp">
                    <h5>Setup an account? It's easier to track your bags if you do.</h5>
                    <div class="form-check form-check-inline mt-2">
                        <label class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" name="radioAccount" id="accountYes" value="accountYes" checked>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Yes</span>
                        </label>
                    </div>
                    <div class="form-check form-check-inline mt-2">
                        <label class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" name="radioAccount" id="accountNo" value="accountNo">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">No</span></span>
                        </label>
                    </div>
                    <div class="form-check form-check-inline mt-2">
                        <label class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" name="radioAccount" id="accountAlready" value="accountNo">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">I already have one</span></span>
                        </label>
                    </div>
                    
                    <input type='hidden' id='ems-user-id' value='0' />
                    
                    <div id='no-account' style='display: none;'>
                    
                    <div id='nouser-msg' style='display:none;' class='alert alert-danger'></div>
                    
                    <p>Please fill out your basic details below so we can get in touch with you and send you your shipping labels.</p>
                    	
                    	<div class="form-group">
                            <label for="accountName">Your First Name *</label>
                            <input id="nouser-first-name"  type="text" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="accountName">Your Last Name *</label>
                            <input id="nouser-last-name"  type="text" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="accountName">Your Email Address *</label>
                            <input id="nouser-email"  type="text" class="form-control requser">
                        </div>
                    	
                    	 <a href="Javascript:void(0);" class="btn btn-primary" id="fill-user">Proceed &raquo;</a>
                    </div>
                    
                    <div id="accountYesForm" style="">
                    
                    <div id='register-msg' class='alert alert-danger' style='display:none;'></div>
                    
                    	<div class="form-group">
                            <label for="accountName">Your First Name *</label>
                            <input id="user-first-name" name="emsuser[first_name]" type="text" class="form-control requser">
                        </div>
                        
                        <div class="form-group">
                            <label for="accountName">Your Last Name *</label>
                            <input id="user-last-name" name="emsuser[last_name]" type="text" class="form-control requser">
                        </div>
                        
                       
                        <div class="form-group">
                            <label for="accountEmail">Email</label>
                            <input id="user-email" name="emsuser[email_address]" type="email" class="form-control requser">
                        </div>
                      
                        <div class="form-group">
                            <label for="accountPassword">Password *</label>
                            <input id="user-password" name="emsuser[password]" type="password" class="form-control requser">
                        </div>
                        
                        <div class="form-group">
                            <label for="accountName">Confirm Password *</label>
                            <input id="user-password-confirm" type="text" class="form-control requser">
                        </div>
                        
                        <a href="Javascript:void(0);" class="btn btn-primary" id="do-register">Register</a>
                    </div>
                    <div id="accountAlreadyForm" style="display:none;">
                       <div id='login-msg' class='alert alert-danger' style='display:none;'></div>
                    	<div class="form-group">
                            <label for="accountEmailLogin">Email</label>
                            <input id="login-email" type="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="accountPasswordLogin">Password</label>
                            <input id="login-password" type="password" class="form-control">
                        </div>
                        <a href="Javascript:void(0);" id="do-login" class="btn btn-primary">Login</a>
                      
                    </div>
                </div>
            </section>
        </div>
        
	</div><!-- .entry-content -->
	
	<div class="modal fade" tabindex="-1" role="dialog" id="norates-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">We Couldn't Find a Rate for Your Parcel</h4>
      </div>
      <div class="modal-body">
        <p>Please double check that your address details are correct and that you have entered all of your parcel fields in correctly.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="message-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">There was an error with your shipment</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="rates-modal">
  <div class="modal-dialog" role="document" style="width:950px; max-width:none;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Select from the following:</h4>
      </div>
      <div class="modal-body">
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


	
	<footer class="entry-footer">
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->




<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
var $j = jQuery.noConflict();


    $j( function() {
        $j( "#datepicker1" ).datepicker({
            numberOfMonths: 2,
            showButtonPanel: true,
            dateFormat: 'DD dx MM, yy',
        
            minDate: 1,
    maxDate: '+10d',
            beforeShowDay: $j.datepicker.noWeekends,
            onSelect: function(dateText, inst) {
                var arrOrd = new Array('0','st','nd','rd','th','th','th','th','th','th','th','th','th','th','th','th','th','th','th','th','th','st','nd','rd','th','th','th','th','th','th','th','st');
                var day = Number(inst.selectedDay);
                var suffix = arrOrd[day];       
                $j(this).val($(this).val().replace(inst.selectedDay+"x",inst.selectedDay+suffix));
                $j('#collectiondate').text($j(this).val().replace(inst.selectedDay+"x",inst.selectedDay+suffix));
                if(inst.selectedMonth < 10){ var smi = "0"; } else { var smi = ""; }
                $j('#shipping-date').val(inst.selectedYear+"-"+smi+inst.selectedMonth+"-"+inst.selectedDay);
                
            }
        });
    });
    
    
    $j( function() {
        $j( "#datepicker21" ).datepicker({
            numberOfMonths: 2,
            showButtonPanel: true,
            dateFormat: 'DD dx MM, yy',
            minDate: 1,
    maxDate: '+10d',
            beforeShowDay: $j.datepicker.noWeekends,
            onSelect: function(dateText, inst) {
                var arrOrd = new Array('0','st','nd','rd','th','th','th','th','th','th','th','th','th','th','th','th','th','th','th','th','th','st','nd','rd','th','th','th','th','th','th','th','st');
                var day = Number(inst.selectedDay);
                var suffix = arrOrd[day];       
                $j(this).val($j(this).val().replace(inst.selectedDay+"x",inst.selectedDay+suffix));
                $j('#returndate').text($(this).val().replace(inst.selectedDay+"x",inst.selectedDay+suffix));
                if(inst.selectedMonth < 10){ var smi = "0"; } else { var smi = ""; }
                $j('#return-date').val(inst.selectedYear+"-"+smi+inst.selectedMonth+"-"+inst.selectedDay);
                
            }
        });
    });
</script>