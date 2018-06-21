<?php

require_once( dirname(__FILE__) . '/stripe-php/init.php' );
require_once( dirname(__FILE__) . '/stripe-php/config.php' );

$package_name   = 'Invoice-'.rand(); 
$package_price  = $_POST['package_price'];

global $Quote;
$ems_session        =   get_ems_session();

$secret = SECRET_KEY;
\Stripe\Stripe::setApiKey( $secret );
 
if(isset($_POST['stripeToken'])){
    try {
        if ( !isset($_POST['stripeToken']) )
            throw new Exception('The Stripe Token is not correct');
        \Stripe\Charge::create( array( 'amount'    => $package_price, 
                                    'currency'    => 'GBP',
                                    'source'      => $_POST['stripeToken'],
                                    'description' => 'Package:  (Name ' . $package_name . ')'
                                  ) 
                          );

        /* if successful - send a plugin by email */
        $Quote->send_shopping_email($ems_session);

        echo '<strong>Payment is successfull.<br>You will be redirecting.....please wait</strong>';
        ?>
        <script type="text/javascript">            
            setTimeout(function(){
                window.location = "<?php echo get_site_url(); ?>/thank-you/?amount=<?php echo $package_price; ?>&package=<?php echo $package_name; ?>";
            }, 3000);
        </script>
    <?php
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>
<section class="tab-pane active" id="orderStep1">
    <!-- START STEP 1 -->
    <div class="lightgreytextboxlp" id="shoppingBox">
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
                                <input id="custCity" type="text" class="form-control" value="<?php echo $_POST['quote']['from_city']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="custCity">Post/Zip Code</label>
                                <input id="custPostcode" type="text" class="req1 form-control" <?php if (isset($_GET['fp'])): ?>value='<?php echo $_GET['fp']; ?>'<?php endif; ?> />
                            </div>
                            <div style='display:<?php if ($_GET['cf'] == 'US'): ?>block;<?php else: ?>none;<?php endif; ?>' class='form-group' id='state-list-from'>
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
                                    <select id="custOrigin_CountryIso" name="Origin_CountryIso" class="form-control custom-select" tabindex="-1" title="">
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
                                <input id="custCity2" type="text" class="req2 form-control"  value="<?php echo $_POST['quote']['to_city']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="custPostcode2">Post/Zip Code</label>
                                <input id="custPostcode2" type="text" <?php if (isset($_GET['tp'])): ?>value='<?php echo $_GET['tp']; ?>'<?php endif; ?> class="req2 form-control">
                            </div>
                            <div class='form-group' style='display:<?php if ($_GET['ct'] == 'US'): ?>block;<?php else: ?>none;<?php endif; ?>' id='state-list-to'>
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
                <div class="col-12 mt-4" style="visibility:hidden;">
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
                <!--
                <a href="Javascript:void(0);" id='pay-with-worldpay' class="btn btn-primary btn-lg float-md-right">Pay with Worldpay</a>
                -->
                <?php
                    $options            = unserialize($Quote->get_shopping_option('shopping_options'));
                    $package            = $_GET['package'];
                    $chosen_package     = $options[$package];
                    $extra_packages     = $_GET['extra_packages'];
                    $price_per_package  = $Quote->get_shopping_option('price_per_shopping_package');
                    $price              = $chosen_package['price'];
                    $extra_price        = $extra_packages * $price_per_package;
                    $total_price        = $price + $extra_price;
                ?>
                <?php
                    $publishable_key    = PUBLIC_KEY;
                    $plugin_name        = $chosen_package['name'];
                    $plugin_image_url   = the_custom_logo();
                    $plugin_price       = $total_price* 100;
                ?>
                <form action="" method="post" class="float-md-right">
                    <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                        data-key="<?php echo $publishable_key; ?>"
                        data-name="<?php echo $plugin_name; ?>"
                        data-description="Package is: <?php echo $plugin_name; ?>"  
                        data-image="http://localhost/quarantine/wp-content/uploads/2017/02/sitelogosmall.jpg" 
                        data-amount="<?php echo $plugin_price; ?>"
                        data-locale="auto">
                    </script>
                    <?php /* you can pass parameters to php file in hidden fields, for example - plugin ID */ ?>
                    <input type="hidden" name="package_price" value="<?php echo $plugin_price; ?>">
                    <input type="hidden" name="package_name" value="<?php echo $plugin_name; ?>">
                </form>
            </div>
        </div>
    </div>
    <!-- END STEP 1 -->

</section>