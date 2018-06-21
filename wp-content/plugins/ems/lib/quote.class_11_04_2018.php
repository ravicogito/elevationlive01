<?php

class ems_quote {

    var $currency;
    var $foreign_carrier;
    var $uk_carrier;

    function __construct() {
        $this->set_currency();
        $this->set_carriers();
    }

    public function set_carriers() {

        $this->foreign_carrier = '608bfd57e42d4a56970e893d043b4d3c';
        $this->uk_carrier = '501c726e695042db80ab01cacfb64004';
    }

    public function create_new_shipment() {
        
    }

    public function set_currency() {

        session_start();

        if (isset($_SESSION['ems_currency'])):

            $this->currency = $_SESSION['ems_currency'];

        else:

            $this->currency = 'GBP';

        endif;
    }

    public function get_currency_symbol() {

        switch ($this->currency):

            case 'GBP':
                return '£';
                break;
            case 'EUR':
                return '€';
                break;
            case 'USD':
                return '$';
                break;
            case 'AUD':
                return 'A$';
                break;
            case 'SEK':
                return 'kr';
                break;

        endswitch;
    }

    public function get_currency_title() {

        switch ($this->currency):

            case 'GBP':
                return '£ GBP';
                break;
            case 'EUR':
                return '€ EUR';
                break;
            case 'USD':
                return '$ USD';
                break;
            case 'AUD':
                return '$ AUD';
                break;
            case 'SEK':
                return 'kr SEK';
                break;

        endswitch;
    }

    public function create_login_session() {


        global $wpdb;

        $data = $_POST['login'];

        $email_address = $data['email_address'];
        $password = sha1($data['password']);

        $sql = "SELECT * FROM ems_users WHERE email_address = '$email_address' AND password = '$password'";

        $row = $wpdb->get_row($sql);




        if (!empty($row)):

            session_start();

            $_SESSION['ems_user'] = $row;

        else:

            die('There was an error with your login');

        endif;
    }

    public function is_logged_in() {

        session_start();

        if (isset($_SESSION['ems_user']) && !empty($_SESSION['ems_user'])): return true;
        else: return false;
        endif;
    }

    public function logout() {

        session_start();
        unset($_SESSION['ems_user']);
        session_destroy();
        header('Location: ' . get_bloginfo('home'));
    }

    private function user_exists($email) {


        global $wpdb;

        $sql = "SELECT * FROM ems_users WHERE email_address = '$email'";

        $row = $wpdb->get_row($sql);

        if (!empty($row)): return true;
        else: return false;
        endif;
    }

    private function is_valid_email($email) {

        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function ems_login() {


        global $wpdb;

        $email_address = $_POST['email_address'];
        $password = sha1($_POST['password']);

        $sql = "SELECT * FROM ems_users WHERE email_address = '$email_address' AND password = '$password' LIMIT 1";

        $row = $wpdb->get_row($sql);

        if (empty($row)):

            $return = array('status' => 'error', 'reason' => 'Those details were not recognised. Please try again');

        else:
            session_start();

            $_SESSION['ems_user'] = $row;
            $return = array('status' => 'success', 'user_id' => $row->id);

        endif;

        echo json_encode($return);

        die();
    }

    public function ems_register() {

        global $wpdb;

        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email_address = $_POST['email_address'];
        $password = sha1($_POST['password']);

        if ($this->user_exists($email_address)):

            $return = array(
                'status' => 'error',
                'reason' => 'That e-mail address is already registered'
            );

            echo json_encode($return);

            die();

        endif;


        if ($first_name == ''):

            $return = array(
                'status' => 'error',
                'reason' => 'Please provide your first name'
            );

            echo json_encode($return);
            die();
        endif;

        if ($last_name == ''):

            $return = array(
                'status' => 'error',
                'reason' => 'Please provide your last name'
            );

            echo json_encode($return);
            die();
        endif;

        if ($email_address == ''):

            $return = array(
                'status' => 'error',
                'reason' => 'Please provide your email address'
            );

            echo json_encode($return);
            die();
        endif;

        if (!$this->is_valid_email($email_address)):

            $return = array(
                'status' => 'error',
                'reason' => 'The e-mail address provided is invalid'
            );

            echo json_encode($return);
            die();

        endif;


        $data = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email_address' => $email_address,
            'password' => $password
        );

        $wpdb->insert('ems_users', $data);

        if ($wpdb->insert_id != ''):

            session_start();
            $id = $wpdb->insert_id;

            $sql = "SELECT * FROM ems_users WHERE id = $id";
            $row = $wpdb->get_row($sql);
            $_SESSION['ems_user'] = $row;

        endif;

        $return = array(
            'status' => 'success',
            'user_id' => $wpdb->insert_id
        );

        echo json_encode($return);
        die();
    }

    public function get_rates($from_city, $from_postcode, $from_country, $to_city, $to_postcode, $to_country) {

        $from_address = array(
            'object_purpose' => 'QUOTE',
            'name' => 'Mr Hippo',
            'company' => 'Shippo',
            'street1' => '',
            'city' => $from_city,
            'state' => '',
            'zip' => $from_postcode,
            'country' => $from_country,
            'phone' => '+1 555 341 9393',
            'email' => 'mr-hippo@goshipppo.com',
        );

        // Example to_address array
        // The complete refence for the address object is available here: https://goshippo.com/docs/reference#addresses
        $to_address = array(
            'object_purpose' => 'QUOTE',
            'name' => 'Ms Hippo',
            'company' => 'San Diego Zoo',
            'street1' => '',
            'city' => $to_city,
            'state' => '',
            'zip' => $to_postcode,
            'country' => $to_country,
            'phone' => '+1 555 341 9393',
            'email' => 'ms-hippo@goshipppo.com',
        );

        // Parcel information array
        // The complete reference for parcel object is here: https://goshippo.com/docs/reference#parcels
        $parcel = array(
            'length' => '5',
            'width' => '5',
            'height' => '5',
            'distance_unit' => 'in',
            'weight' => '2',
            'mass_unit' => 'lb',
        );

        // Example shipment object
        // For complete reference to the shipment object: https://goshippo.com/docs/reference#shipments
        // This object has async=false, indicating that the function will wait until all rates are generated before it returns.
        // By default, Shippo handles responses asynchronously. However this will be depreciated soon. Learn more: https://goshippo.com/docs/async
        $shipment = Shippo_Shipment::create(
                        array(
                            'object_purpose' => 'QUOTE',
                            'address_from' => $from_address,
                            'address_to' => $to_address,
                            'parcel' => $parcel,
                            'async' => false,
        ));



        // Rates are stored in the `rates_list` array inside the shipment object
        $rates = $shipment['rates_list'];



        // You can now show those rates to the user in your UI.
        // Most likely you want to show some of the following fields:
        //  - provider (carrier name)
        //  - servicelevel_name
        //  - amount (price of label - you could add e.g. a 10% markup here)
        //  - days (transit time)
        // Don't forget to store the `object_id` of each Rate so that you can use it for the label purchase later.
        // The details on all of the fields in the returned object are here: https://goshippo.com/docs/reference#rates
        //	echo "Available rates:" . "\n";

        $prices = array();

        foreach ($rates as $rate) {
            //echo "--> " . $rate['provider'] . " - " . $rate['servicelevel_name'] . "\n";
            //echo "  --> " . "Amount: "             . $rate['amount'] . "\n";
            array_push($prices, $rate['amount']);
            //echo "  --> " . "Days to delivery: "   . $rate['days'] . "\n";
        }
        //echo "\n";
        // This would be the index of the rate selected by the user
        $selected_rate_index = 1;

        // After the user has selected a rate, use the corresponding object_id
        $selected_rate = $rates[$selected_rate_index];
        $selected_rate_object_id = $selected_rate['object_id'];


        // Purchase the desired rate with a transaction request
        // Set async=false, indicating that the function will wait until the carrier returns a shipping label before it returns
        $transaction = Shippo_Transaction::create(array(
                    'rate' => $selected_rate_object_id,
                    'async' => false,
        ));

        // Print the shipping label from label_url
        // Get the tracking number from tracking_number
        if ($transaction['object_status'] == 'SUCCESS') {
            //echo "--> " . "Shipping label url: " . $transaction['label_url'] . "\n";
            //echo "--> " . "Shipping tracking number: " . $transaction['tracking_number'] . "\n";
        } else {
            //echo "Transaction failed with messages:" . "\n";
            foreach ($transaction['messages'] as $message) {
                //echo "--> " . $message . "\n";
            }
        }
        // For more tutorals of address validation, tracking, returns, refunds, and other functionality, check out our
        // complete documentation: https://goshippo.com/docs/

        echo min($prices);
    }

    public function estimate($from_city, $from_postcode, $from_country, $to_city, $to_postcode, $to_country) {


        $delivery_windows = array(1, 3, 7);
        $destination_zip_codes = array($to_postcode);

// Example from_address array
// The complete refence for the address object is available here: https://goshippo.com/docs/reference#addresses
        $from_address = array(
            'object_purpose' => 'QUOTE',
            'name' => 'Mr Hippo',
            'company' => 'Shippo',
            'street1' => '',
            'city' => $from_city,
            'state' => '',
            'zip' => $from_postcode,
            'country' => $from_country,
            'phone' => '+1 555 341 9393',
            'email' => 'mr-hippo@goshipppo.com',
        );

// Parcel information array
// The complete reference for parcel object is here: https://goshippo.com/docs/reference#parcels
        $parcel = array(
            'length' => '5',
            'width' => '5',
            'height' => '5',
            'distance_unit' => 'in',
            'weight' => '2',
            'mass_unit' => 'lb',
        );


// Collect the shipments to each address
        $shipments = array();
        foreach ($destination_zip_codes as $zip_code) {
            // Example to_address with the zip code
            // The complete refence for the address object is available here: https://goshippo.com/docs/reference#addresses
            $to_address = array(
                'object_purpose' => 'QUOTE',
                'country' => $to_country,
                'zip' => $zip_code,
            );

            // For each destination address we now create a Shipment object.
            // async=false indicates that the function will wait until all rates are generated before it returns.
            // The reference for the shipment object is here: https://goshippo.com/docs/reference#shipments
            // By default Shippo API operates on an async basis. You can read about our async flow here: https://goshippo.com/docs/async
            $shipments[] = Shippo_Shipment::create(array(
                        'object_purpose' => 'QUOTE',
                        'address_from' => $from_address,
                        'address_to' => $to_address,
                        'parcel' => $parcel,
                        'async' => false
            ));
        }

// Collect all shipments rates
        $all_rates = array();
        foreach ($shipments as $shipment) {
            $all_rates = array_merge($all_rates, $shipment['rates_list']);
        }

// This function takes a list of $rates, filters only those rates in
// the $delivery_window, and returns the rates estimation
        function calculate_rates_estimation($rates, $delivery_window) {
            // Filter rates by delivery window
            $eligible_rates = array_values(array_filter(
                            $rates, function($rate) use($delivery_window) {
                        return $rate['days'] <= $delivery_window;
                    }
            ));

            // Calculate estimations on the eligible_rates
            $min = $eligible_rates[0]['amount'];
            $max = 0.0;
            $sum = 0.0;
            foreach ($eligible_rates as $rate) {
                $amount = $rate['amount'];

                $min = min($min, $amount);
                $max = max($max, $amount);
                $sum += $amount;
            }

            return array(
                'delivery_window' => $delivery_window,
                'min' => $min,
                'max' => $max,
                'average' => $sum / count($eligible_rates),
            );
        }

        $arrays = array();
// Show estimations for each delivery window
        foreach ($delivery_windows as $delivery_window) {
            $estimations = calculate_rates_estimation($all_rates, $delivery_window);

            $result = array();
            $result['days'] = $delivery_window;
            $result['price'] = $estimations['min'];

            array_push($arrays, $result);
        }

        return $arrays;
    }

    public function get_places() {

        global $wpdb;

        $sql = "SELECT * FROM ems_countries ORDER BY orderx ASC";
        $results = $wpdb->get_results($sql);

        return $results;
    }

    public function get_places_by_name() {

        global $wpdb;

        $sql = "SELECT * FROM ems_countries ORDER BY name ASC";
        $results = $wpdb->get_results($sql);

        return $results;
    }

    public function get_place($id) {

        global $wpdb;

        $sql = "SELECT * FROM ems_countries WHERE id = " . $id;
        $result = $wpdb->get_row($sql);

        return $result;
    }

    public function update_places() {



        global $wpdb;

        $sql = "DELETE FROM ems_countries";

        $result = $wpdb->query($sql);

        $places = $_POST['ems_places'];



        foreach ($places as $place):


            if ($place['name'] != '' && $place['postcode'] != '' && $place['country_code'] != ''):

                $wpdb->insert('ems_countries', $place);

            endif;



        endforeach;
    }

    public function retrieve_batch() {
        // a7f594a7a1234f93987580e705fa6f27
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);




        $transaction = Shippo_Transaction::create(array(
                    'rate' => 'a7f594a7a1234f93987580e705fa6f27',
                    'label_file_type' => "PDF",
                    'async' => false));

// Retrieve label url and tracking number or error message
        if ($transaction["object_status"] == "SUCCESS") {
            echo( $transaction["label_url"] );
            //echo("\n");
            //echo( $transaction["tracking_number"] );
        } else {
            // echo( $transaction["messages"] );
        }

        //34ff748197b24043b374cbc3a87e9154


        $transaction = Shippo_Transaction::create(array(
                    'rate' => '34ff748197b24043b374cbc3a87e9154',
                    'label_file_type' => "PDF",
                    'async' => false));
        echo( $transaction["label_url"] );

        die();
    }

    public function calculate_package($from_name, $from_address_1, $from_address_2, $from_telephone, $from_city, $from_postcode, $from_country, $to_name, $to_address_1, $to_address_2, $to_telephone, $to_city, $to_postcode, $to_country, $width, $height, $length, $weight, $units, $shipping_date, $delivery_service, $journey_type, $return_shipping_date) {

        /* 	ini_set('display_errors', 1);
          ini_set('display_startup_errors', 1);
          error_reporting(E_ALL); */

        $service_type = $_POST['service_type'];
        if ($units == 'metric'): $distance_unit = 'cm';
            $mass_unit = 'kg';
        else: $distance_unit = 'in';
            $mass_unit = 'lb';
        endif;

        $has_customs = $_POST['has_customs'];
        $customs_contents = $_POST['customs_contents'];
        $customs_contents_other = $_POST['customs_contents_other'];
        $customs_value = str_replace('£', '', $_POST['customs_value']);
        if ($has_customs == 'yes'):

            $customs_item = array(
                'description' => $_POST['item_description'],
                'quantity' => '1',
                'net_weight' => $weight,
                'mass_unit' => $mass_unit,
                'value_amount' => $customs_value,
                'value_currency' => 'GBP',
                'origin_country' => $from_country);

            $customs_declaration = Shippo_CustomsDeclaration::create(
                            array(
                                'contents_type' => $customs_contents,
                                'contents_explanation' => $customs_contents_other,
                                'non_delivery_option' => 'RETURN',
                                'certify' => 'true',
                                'certify_signer' => $to_name,
                                'items' => array($customs_item)
            ));
        //	print_r($customs_declaration);
        else: $customs_declaration = '';

        endif;

        $eu_countries = array(
            'AT', 'BE', 'HR', 'BG', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IE',
            'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE', 'GB'
        );


        $from_address = array(
            'object_purpose' => 'PURCHASE',
            'name' => $from_name,
            'street1' => $from_address_1,
            'street2' => $from_address_2,
            'city' => $from_city,
            'zip' => $from_postcode,
            'country' => $from_country,
            'phone' => $from_telephone,
            'email' => 'freelance@healthysyntax.co.uk',
        );

        if ($from_country == 'US'):
            $from_address['state'] = $_POST['state_from'];
        endif;

        if (in_array($from_country, $eu_countries)): $from_zone = 'europe';
        else: $from_zone = 'worldwide';
        endif;

        // Example to_address array
        // The complete refence for the address object is available here: https://goshippo.com/docs/reference#addresses
        $to_address = array(
            'object_purpose' => 'PURCHASE',
            'name' => $to_name,
            'street1' => $to_address_1,
            'street2' => $to_address_2,
            'city' => $to_city,
            'zip' => $to_postcode,
            'country' => $to_country,
            'phone' => $to_telephone,
            'email' => 'andy@healthysyntax.co.uk',
        );

        if ($to_country == 'US'):
            $to_address['state'] = $_POST['state_to'];
        endif;

        if (in_array($to_country, $eu_countries)): $to_zone = 'europe';
        else: $to_zone = 'worldwide';
        endif;

        if ($from_zone == 'worldwide' || $to_zone == 'worldwide'): $zone = 'worldwide';
        else: $zone = 'europe';
        endif;

        if ($from_country == 'GB' && $to_country == 'GB'):

            $zone = 'domestic';
        endif;
        // Parcel information array
        // The complete reference for parcel object is here: https://goshippo.com/docs/reference#parcels

        if ($from_country == 'GB'): $carriers = array($this->uk_carrier);
        else: $carriers = array($this->foreign_carrier);
        endif;


        $parcel = array(
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'distance_unit' => $distance_unit,
            'weight' => $weight,
            'mass_unit' => $mass_unit,
        );



        // Example shipment object
        // For complete reference to the shipment object: https://goshippo.com/docs/reference#shipments
        // This object has async=false, indicating that the function will wait until all rates are generated before it returns.
        // By default, Shippo handles responses asynchronously. However this will be depreciated soon. Learn more: https://goshippo.com/docs/async

        $xdate = explode('-', $shipping_date);

        $y = $xdate[0];
        $m = $xdate[1];
        $d = $xdate[2];

        $new_month = $m + 1;

        if ($new_month < 10):
            $m = sprintf("%02d", $new_month);
        else:
            $m = $new_month;
        endif;

        $shipping_date = $y . "-" . $m . "-" . $d;

        $today = date('Y-m-d');

        if ($shipping_date <= $today): die('norates');
        endif;

        $sd = date("Y-m-d\TH:i:s.000\Z", strtotime($shipping_date . " 12:56:57"));

        try {
            $shipment = Shippo_Shipment::create(
                            array(
                                'object_purpose' => 'PURCHASE',
                                'address_from' => $from_address,
                                'address_to' => $to_address,
                                'parcels' => $parcel,
                                'async' => false,
                                'shipment_date' => $sd,
                                'currency' => 'USD',
                                'customs_declaration' => $customs_declaration,
                                'carrier_accounts' => $carriers
            ));
        } catch (Exception $e) {
            $message = 'Message: ' . $e->getMessage();
            $message = array('message' => $m);
            echo json_encode($message);
            die();
        }


        if (isset($shipment->messages[0])):
            $m = str_replace('<', '[', $shipment->messages[0]->text);
            $m = str_replace('>', ']', $m);
            $message = array('message' => $m);
            echo json_encode($message);
            die();
        endif;

        // Rates are stored in the `rates_list` array inside the shipment object
        $rates = $shipment['rates'];
        //	print_r($rates);
        foreach ($rates as $keyrates => $valuerates):

            if ($valuerates->amount < 1): unset($rates[$keyrates]);
            endif;
        endforeach;



        $rates_url = $shipment['rates_url'];

        $shipment_object_id = $shipment['object_id'];

        if (isset($_POST['currency']) && $_POST['currency'] != 'GBP'):

            $currency_rates = Shippo_Shipment::get_shipping_rates(
                            array(
                                'id' => $shipment_object_id,
                                'currency' => $_POST['currency']
                            )
            );




            $rates = $currency_rates->results;

        endif;

        // You can now show those rates to the user in your UI.
        // Most likely you want to show some of the following fields:
        //  - provider (carrier name)
        //  - servicelevel_name
        //  - amount (price of label - you could add e.g. a 10% markup here)
        //  - days (transit time)
        // Don't forget to store the `object_id` of each Rate so that you can use it for the label purchase later.
        // The details on all of the fields in the returned object are here: https://goshippo.com/docs/reference#rates
        //	echo "Available rates:" . "\n";



        $prices = array();

        foreach ($rates as $rate) {

            //echo "--> " . $rate['provider'] . " - " . $rate['servicelevel_name'] . "\n";
            //echo "  --> " . "Amount: "             . $rate['amount'] . "\n";
            array_push($prices, $rate['amount']);
            //echo "  --> " . "Days to delivery: "   . $rate['days'] . "\n";
        }
        //echo "\n";
        // This would be the index of the rate selected by the user
        $selected_rate_index = 1;

        // After the user has selected a rate, use the corresponding object_id
        $selected_rate = $rates[$selected_rate_index];
        $selected_rate_object_id = $selected_rate['object_id'];


        // Purchase the desired rate with a transaction request
        // Set async=false, indicating that the function will wait until the carrier returns a shipping label before it returns
        //$transaction = Shippo_Transaction::create(array(
        //	'rate'=> $selected_rate_object_id,
        //	'async'=> false,
        //));
        // Print the shipping label from label_url
        // Get the tracking number from tracking_number
        if ($transaction['object_status'] == 'SUCCESS') {
            //echo "--> " . "Shipping label url: " . $transaction['label_url'] . "\n";
            //echo "--> " . "Shipping tracking number: " . $transaction['tracking_number'] . "\n";
        } else {
            //echo "Transaction failed with messages:" . "\n";
            foreach ($transaction['messages'] as $message) {
                //echo "--> " . $message . "\n";
            }
        }
        // For more tutorals of address validation, tracking, returns, refunds, and other functionality, check out our
        // complete documentation: https://goshippo.com/docs/

        $pricing_array = array();

        $min_price = min($prices);

        //	if($min_price < 1): die('norates'); endif;
        $arrays = array();
        foreach ($rates as $rate):




            if ($rate->currency == 'GBP'):
                $amount = $rate->amount;
            else:
                $amount = $rate->amount_local;
            endif;


            $markup = $this->get_markup_for_parcel_kg_tiers($from_country, $to_country, $weight, $service_type);

            $one_percent = ($amount / 100);

            $required_percentage_to_add = ($one_percent * $markup);

            if ($journey_type == 'return'):
                $amount = $amount * 2;
                $markup = $markup * 2;
            endif;

            $final_amount = ($amount + $required_percentage_to_add);

            switch ($rate->servicelevel->name):

                case 'EXPRESS WORLDWIDE EU DOC':
                    $sname = 'DHL Express EU';
                    break;
                case 'Express Worldwide Non Doc':
                    $sname = 'DHL EXPRESS WORLDWIDE';
                    break;
                case 'EXPRESS WORLDWIDE NONDOC':
                    $sname = 'DHL EXPRESS WORLDWIDE';
                    break;
                case 'EXPRESS WORLDWIDE':
                    $sname = 'DHL EXPRESS WORLDWIDE';
                    break;
                case 'ECONOMY SELECT DOC':
                    $sname = 'DHL STANDARD (ROAD)';
                    break;
                case 'EXPRESS WORLDWIDE DOC':
                    $sname = 'DHL EXPRESS WORLDWIDE';
                    break;

                case 'Economy Select Non Doc':
                    $sname = 'DHL STANDARD (ROAD) NON EU';
                    break;
                case 'ECONOMY SELECT NON DOC':
                    $sname = 'DHL STANDARD (ROAD) NON EU';
                    break;
                case 'ECONOMY SELECT':
                    $sname = 'DHL STANDARD (ROAD) NON EU';
                    break;
                case 'DOMESTIC EXPRESS DOC':
                    $sname = 'DHL EXPRESS - UK DOMESTIC';
                    break;
                case 'EXPRESS DOMESTIC 10:00':
                    $sname = 'DHL EXPRESS - UK DOMESTIC (EXPRESS SERVICE - 10AM NEXT DAY)';
                    break;

                default:
                    $sname = $rate->servicelevel_name;
                    break;
            endswitch;

            if ($rate->servicelevel->name != 'MEDICAL EXPRESS' && $rate->servicelevel->name != 'BREAK BULK ECONOMY DOC'):

                $array = array('object_id' => $rate->shipment,
                    'amount' => number_format($final_amount, 2),
                    'provider' => $rate->provider,
                    'rate' => $rate->object_id,
                    'original_price' => $amount,
                    'currency' => $_POST['currency'],
                    'provider_image' => $rate->provider_image_200,
                    'arrives_by' => $rate->arrives_by,
                    'servicelevel_name' => $rate->servicelevel->name,
                    'days' => $rate->days
                );

                array_push($arrays, $array);

            endif;

        endforeach;

        if ($zone == 'europe' && $service_type == 'standard'):

            $new_rates = array();

            foreach ($arrays as $item):

                if ($item['servicelevel_name'] == 'ECONOMY SELECT DOC' || $item['servicelevel_name'] == 'ECONOMY SELECT NONDOC'):
                    array_push($new_rates, $item);
                endif;

            endforeach;

            $arrays = $new_rates;

        endif;

        if ($zone == 'europe' && $service_type == 'express'):

            $new_rates = array();

            foreach ($arrays as $item):

                if ($item['servicelevel_name'] == 'EXPRESS WORLDWIDE EU DOC' || $item['servicelevel_name'] == 'EXPRESS WORLDWIDE EU NONDOC'):
                    array_push($new_rates, $item);
                endif;

            endforeach;

            $arrays = $new_rates;

        endif;

        if ($zone == 'worldwide' && $service_type == 'standard'): die('wrong_service_type');
        endif;

        if ($zone == 'worldwide' && $service_type == 'express'):

            $new_rates = array();

            foreach ($arrays as $item):

                if ($item['servicelevel_name'] == 'EXPRESS WORLDWIDE NON DOC' || $item['servicelevel_name'] == 'EXPRESS WORLDWIDE NONDOC' || $item['servicelevel_name'] == 'Express Worldwide Non Doc' || $item['servicelevel_name'] == 'EXPRESS WORLDWIDE DOC'):
                    array_push($new_rates, $item);
                endif;

            endforeach;

            $arrays = $new_rates;

        endif;

        if ($zone == 'domestic' && $service_type == 'standard'):

            $new_rates = array();

            foreach ($arrays as $item):

                if ($item['servicelevel_name'] == 'DOMESTIC EXPRESS DOC' || $item['servicelevel_name'] == 'DOMESTIC EXPRESS NONDOC'):
                    $new_rates = array();
                    array_push($new_rates, $item);
                endif;

            endforeach;

            $arrays = $new_rates;


        endif;

        if (empty($arrays)): echo 'norates';
        else:

            echo json_encode($arrays);

        endif;

        //echo min($prices);
    }

    public function calculate_package_return($from_name, $from_address_1, $from_address_2, $from_telephone, $from_city, $from_postcode, $from_country, $to_name, $to_address_1, $to_address_2, $to_telephone, $to_city, $to_postcode, $to_country, $width, $height, $length, $weight, $units, $shipping_date, $delivery_service) {

        $from_address = array(
            'object_purpose' => 'PURCHASE',
            'name' => $from_name,
            'street1' => $from_address_1,
            'street2' => $from_address_2,
            'city' => $from_city,
            'state' => '',
            'zip' => $from_postcode,
            'country' => $from_country,
            'phone' => $from_telephone,
            'email' => 'freelance@healthysyntax.co.uk',
        );

        // Example to_address array
        // The complete refence for the address object is available here: https://goshippo.com/docs/reference#addresses
        $to_address = array(
            'object_purpose' => 'PURCHASE',
            'name' => $to_name,
            'street1' => $to_address_1,
            'street2' => $to_address_2,
            'city' => $to_city,
            'state' => '',
            'zip' => $to_postcode,
            'country' => $to_country,
            'phone' => $to_telephone,
            'email' => 'andy@healthysyntax.co.uk',
        );

        // Parcel information array
        // The complete reference for parcel object is here: https://goshippo.com/docs/reference#parcels
        if ($units == 'metric'): $distance_unit = 'cm';
            $mass_unit = 'kg';
        else: $distance_unit = 'in';
            $mass_unit = 'lb';
        endif;


        $parcel = array(
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'distance_unit' => $distance_unit,
            'weight' => $weight,
            'mass_unit' => $mass_unit,
        );



        // Example shipment object
        // For complete reference to the shipment object: https://goshippo.com/docs/reference#shipments
        // This object has async=false, indicating that the function will wait until all rates are generated before it returns.
        // By default, Shippo handles responses asynchronously. However this will be depreciated soon. Learn more: https://goshippo.com/docs/async

        $sd = date("Y-m-d\TH:i:s.000\Z", strtotime($shipping_date . " 12:56:57"));

        $shipment = Shippo_Shipment::create(
                        array(
                            'object_purpose' => 'PURCHASE',
                            'address_from' => $from_address,
                            'address_to' => $to_address,
                            'parcel' => $parcel,
                            'async' => false,
                            'shipment_date' => $sd,
                            'currency' => 'USD'
        ));

        //print_r($shipment);
        // Rates are stored in the `rates_list` array inside the shipment object
        $rates = $shipment['rates_list'];

        $rates_url = $shipment['rates_url'];

        $shipment_object_id = $shipment['object_id'];

        if (isset($_POST['currency']) && $_POST['currency'] != 'GBP'):

            $currency_rates = Shippo_Shipment::get_shipping_rates(
                            array(
                                'id' => $shipment_object_id,
                                'currency' => $_POST['currency']
                            )
            );




            $rates = $currency_rates->results;

        endif;

        // You can now show those rates to the user in your UI.
        // Most likely you want to show some of the following fields:
        //  - provider (carrier name)
        //  - servicelevel_name
        //  - amount (price of label - you could add e.g. a 10% markup here)
        //  - days (transit time)
        // Don't forget to store the `object_id` of each Rate so that you can use it for the label purchase later.
        // The details on all of the fields in the returned object are here: https://goshippo.com/docs/reference#rates
        //	echo "Available rates:" . "\n";

        $prices = array();

        foreach ($rates as $rate) {

            //echo "--> " . $rate['provider'] . " - " . $rate['servicelevel_name'] . "\n";
            //echo "  --> " . "Amount: "             . $rate['amount'] . "\n";
            array_push($prices, $rate['amount']);
            //echo "  --> " . "Days to delivery: "   . $rate['days'] . "\n";
        }
        //echo "\n";
        // This would be the index of the rate selected by the user
        $selected_rate_index = 1;

        // After the user has selected a rate, use the corresponding object_id
        $selected_rate = $rates[$selected_rate_index];
        $selected_rate_object_id = $selected_rate['object_id'];


        // Purchase the desired rate with a transaction request
        // Set async=false, indicating that the function will wait until the carrier returns a shipping label before it returns
        //$transaction = Shippo_Transaction::create(array(
        //	'rate'=> $selected_rate_object_id,
        //	'async'=> false,
        //));
        // Print the shipping label from label_url
        // Get the tracking number from tracking_number
        if ($transaction['object_status'] == 'SUCCESS') {
            //echo "--> " . "Shipping label url: " . $transaction['label_url'] . "\n";
            //echo "--> " . "Shipping tracking number: " . $transaction['tracking_number'] . "\n";
        } else {
            //echo "Transaction failed with messages:" . "\n";
            foreach ($transaction['messages'] as $message) {
                //echo "--> " . $message . "\n";
            }
        }
        // For more tutorals of address validation, tracking, returns, refunds, and other functionality, check out our
        // complete documentation: https://goshippo.com/docs/

        $pricing_array = array();

        $min_price = min($prices);

        //	if($min_price < 1): die('norates'); endif;
        $arrays = array();
        foreach ($rates as $rate):




            if (isset($_POST['currency']) && $_POST['currency'] != 'GBP'):
                $amount = $rate->amount_local;
            else:
                $amount = $rate->amount;
            endif;

            $markup = $this->get_markup_for_parcel($from_country, $to_country);

            $one_percent = ($amount / 100);

            $required_percentage_to_add = ($one_percent * $markup);

            $final_amount = ($amount + $required_percentage_to_add);

            $array = array('object_id' => $rate->shipment,
                'amount' => number_format($final_amount, 2),
                'provider' => $rate->provider,
                'rate' => $rate->object_id,
                'original_price' => $amount,
                'currency' => $_POST['currency'],
                'provider_image' => $rate->provider_image_200,
                'arrives_by' => $rate->arrives_by,
                'servicelevel_name' => $rate->servicelevel_name,
                'days' => $rate->days
            );

            array_push($arrays, $array);

        endforeach;

        echo json_encode($arrays);

        //echo min($prices);
    }

    public function save_cart() {


        global $wpdb;
        $ems_session = $_POST['ems_session'];
        $sql = "DELETE FROM ems_shipments WHERE ems_session = '$ems_session'";
        $wpdb->query($sql);

        $data = $_POST;

        $data['collection_address'] = serialize($data['collection_address']);
        $data['delivery_address'] = serialize($data['delivery_address']);
        $data['label_address'] = serialize($data['label_address']);



        unset($data['ems_cmd']);

        if ($wpdb->insert('ems_shipments', $data)) {
            echo 'success';
        } else {
            echo $wpdb->last_query;
            echo 'error';
        }

        //Insert into ems_shopping_purchases
        $user_id = $data['ems_user_id'];
        $collection_address = serialize($data['collection_address']);
        $delivery_address = serialize($data['delivery_address']);
        $package_amount = $data['total'];


        $extra_packages = '';
        $package_name = '';
        $package_index = '';

        $data_array = array(
            'user_id' => $user_id,
            'ems_session' => $ems_session,
            'product_title' => $package_name,
            'product_index' => $package_index,
            'collection_address' => $collection_address,
            'delivery_address' => $delivery_address,
            'amount' => $package_amount,
            'extra_packages' => $extra_packages
        );

        $messages = array();

        if ($wpdb->insert('ems_shopping_purchases', $data_array)):
            $messages['status'] = 'SUCCESS';
        else:
            $messages['status'] = 'ERROR';
        endif;
        die();
    }

    public function send_transaction_email($parcels, $transactions, $order) {



        $currency = $order->currency;

        if (!empty($transactions)):
            require 'mailer/PHPMailerAutoload.php';

            $mail = new PHPMailer;


            $ems_user_id = $order->ems_user_id;

            if ($ems_user_id == 0):

                $customer_name = $order->nouser_first_name . ' ' . $order->nouser_last_name;
                $customer_email = $order->nouser_email;
                $first_name = $order->nouser_first_name;


            else:

                $user = $this->get_user($ems_user_id);

                $customer_name = $user->first_name . ' ' . $user->last_name;
                $customer_email = $user->email_address;
                $first_name = $user->first_name;

            endif;


            $mail->setFrom('sales@expressmystuff.co.uk', 'Express My Stuff');
            $mail->addAddress($customer_email, $customer_name);


            $mail->isHTML(true);

            $message = "<html>
		<head>
		
		</head>
		<body style='background-color:#c9c9c9;'>
		<table width='600' align='center' border='0' bgcolor='#ffffff' cellpadding='20' style='background-color:#ffffff;'><tr><td>";

            $message .= "<table width='100%'><tr><td align='center'><a href='http://www.expressmystuff.co.uk'><img src='http://www.expressmystuff.co.uk/wp-content/uploads/2017/02/sitelogosmall.jpg' /></a></td></tr></table><hr />";

            $message .= "<table width='100%'><tr><td width='50%' align='left'><a href='http://www.expressmystuff.co.uk/quote/'><img src='http://www.expressmystuff.co.uk/wp-content/uploads/2017/07/beach-email-banner.jpg' /></a></td><td width='50%' align='right'><a href='http://www.expressmystuff.co.uk/express-my-shopping/'><img src='http://www.expressmystuff.co.uk/wp-content/uploads/2017/07/shopping-email-banner.png' /></a></td></tr></table><hr />";

            $message .= "<p>Dear " . $first_name . ",</p>
		
		<p>Thank you for your recent order with Express My Stuff. Please find your order details, shipment labels and order tracking information below.</p>";

            $message .= "<table width='100%' bgcolor='#EEEEEE' style='background-color:#EEEEEE;' border='1' bordercolor='#000000' style='border:solid 1px #000;'><thead>
		<tr>
		<th style='text-align:left;' align='left'>Item / Description</th>
		<th style='text-align:left;' align='left'>Amount</th>
		</tr>
		</thead><tbody>";

            foreach ($parcels as $parcel):
                $t += $parcel->price;
                $message .= "<tr>
		<td align='left' bgcolor='#FFFFFF' style='background-color:#FFFFFF;'><strong>Shipment: </strong>" . $parcel->dimensions . " x " . $parcel->weight . "</td>
		<td align='left' bgcolor='#FFFFFF' style='background-color:#FFFFFF;'>" . $parcel->price . " " . $currency . "</td>
		</tr>";


            endforeach;

            if ($order->total > $t):
                $remainder = ($order->total - $t);
                $message .= "<tr>
		<td align='left' bgcolor='#FFFFFF' style='background-color:#FFFFFF;'>Insurance / postage</td>
		<td align='left' bgcolor='#FFFFFF' style='background-color:#FFFFFF;'>" . number_format($remainder, 2) . " " . $currency . "</td>
		</tr>";

            endif;

            $message .= "</tbody></table><hr /><h3>Shipment Labels and Tracking Information</h3>";

            foreach ($transactions as $transaction):

                $message .= "<p></strong>Parcel:</strong> " . $transaction['description'] . "<br />				
				<strong>Label: </strong><a href='" . $transaction['label'] . "'>Click here to download</a><br />
				<strong>Tracking reference: </strong> " . $transaction['tracking'] . "<br />
				<strong>Invoice: </strong><a href='" . $transaction['invoice'] . "'>Click here to download</a><br />
				<strong>Tracking link: </strong> <a href='http://www.dhl.co.uk/en/express/tracking.html'>Track your order here</a></p><hr />";

            endforeach;

            $message .= "<tr><td align='left' bgcolor='#FFFFFF' style='background-color:#FFFFFF;'>
			Express My Stuff<br />		
			Montessoriuk Ltd <br />		
			Dalry<br />
			78 Cornwall Road<br />
			Harrogate,<br />
			North Yorkshire<br />
			HG1 2NE<br />
			VAT Number: 121 9285 24<br />
			Telephone: 0844 482 8665<br />
			sales@expressmystuff.co.uk<br />
			https://www.expressmystuff.co.uk<br />
		</td></tr>
		<p>Remember your entered sizes and dimensions need to be accurate otherwise you will be automatically charged for any excess charges. If you are unsure, use the DHL Drop Off Points: <a href='http://www.expressmystuff.co.uk/no-waiting-around-with-a-dhl-service-point-in-the-uk/'>UK</a> or <a href='http://www.expressmystuff.co.uk/why-use-a-dhl-service-point-overseas/'>Overseas</a></p>";
            $message .= "</td></tr></table></body></html>";

            $mail->Subject = 'Your Express My Stuff Order';
            $mail->Body = $message;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();

        endif;
    }

    public function send_transaction_email_debug($parcels, $transactions, $order) {



        $currency = $order->currency;

        if (!empty($transactions)):
            require 'mailer/PHPMailerAutoload.php';

            $mail = new PHPMailer;


            $ems_user_id = $order->ems_user_id;

            if ($ems_user_id == 0):

                $customer_name = $order->nouser_first_name . ' ' . $order->nouser_last_name;
                $customer_email = $order->nouser_email;
                $first_name = $order->nouser_first_name;


            else:

                $user = $this->get_user($ems_user_id);

                $customer_name = $user->first_name . ' ' . $user->last_name;
                $customer_email = $user->email_address;
                $first_name = $user->first_name;

            endif;


            $mail->setFrom('sales@expressmystuff.co.uk', 'Express My Stuff');
            //$mail->addAddress($customer_email, $customer_name);
            $mail->addAddress('freelance@healthysyntax.co.uk', 'Andy James');

            $mail->isHTML(true);

            $message = "<html>
		<head>
		
		</head>
		<body style='background-color:#c9c9c9;'>
		<table width='600' align='center' border='0' bgcolor='#ffffff' cellpadding='20' style='background-color:#ffffff;'><tr><td>";

            $message .= "<table width='100%'><tr><td align='center'><a href='http://www.expressmystuff.co.uk'><img src='http://www.expressmystuff.co.uk/wp-content/uploads/2017/02/sitelogosmall.jpg' /></a></td></tr></table><hr />";

            $message .= "<table width='100%'><tr><td width='50%' align='left'><a href='http://www.expressmystuff.co.uk/quote/'><img src='http://www.expressmystuff.co.uk/wp-content/uploads/2017/07/beach-email-banner.jpg' /></a></td><td width='50%' align='right'><a href='http://www.expressmystuff.co.uk/express-my-shopping/'><img src='http://www.expressmystuff.co.uk/wp-content/uploads/2017/07/shopping-email-banner.png' /></a></td></tr></table><hr />";

            $message .= "<p>Dear " . $first_name . ",</p>
		
		<p>Thank you for your recent order with Express My Stuff. Please find your order details, shipment labels and order tracking information below.</p>";

            $message .= "<table width='100%' bgcolor='#EEEEEE' style='background-color:#EEEEEE;' border='1' bordercolor='#000000' style='border:solid 1px #000;'><thead>
		<tr>
		<th style='text-align:left;' align='left'>Item / Description</th>
		<th style='text-align:left;' align='left'>Amount</th>
		</tr>
		</thead><tbody>";

            foreach ($parcels as $parcel):
                $t += $parcel->price;
                $message .= "<tr>
		<td align='left' bgcolor='#FFFFFF' style='background-color:#FFFFFF;'><strong>Shipment: </strong>" . $parcel->dimensions . " x " . $parcel->weight . "</td>
		<td align='left' bgcolor='#FFFFFF' style='background-color:#FFFFFF;'>" . $parcel->price . " " . $currency . "</td>
		</tr>";


            endforeach;

            if ($order->total > $t):
                $remainder = ($order->total - $t);
                $message .= "<tr>
		<td align='left' bgcolor='#FFFFFF' style='background-color:#FFFFFF;'>Insurance / postage</td>
		<td align='left' bgcolor='#FFFFFF' style='background-color:#FFFFFF;'>" . number_format($remainder, 2) . " " . $currency . "</td>
		</tr>";

            endif;

            $message .= "</tbody></table><hr /><h3>Shipment Labels and Tracking Information</h3>";

            foreach ($transactions as $transaction):

                $message .= "<p></strong>Parcel:</strong> " . $transaction['description'] . "<br />				
				<strong>Label: </strong><a href='" . $transaction['label'] . "'>Click here to download</a><br />
				<strong>Tracking reference: </strong> " . $transaction['tracking'] . "<br />
				<strong>Invoice: </strong><a href='" . $transaction['invoice'] . "'>Click here to download</a><br />
				<strong>Tracking link: </strong> <a href='http://www.dhl.co.uk/en/express/tracking.html'>Track your order here</a></p><hr />";

            endforeach;

            $message .= "<tr><td align='left' bgcolor='#FFFFFF' style='background-color:#FFFFFF;'>
			Express My Stuff<br />		
			Montessoriuk Ltd <br />		
			Dalry<br />
			78 Cornwall Road<br />
			Harrogate,<br />
			North Yorkshire<br />
			HG1 2NE<br />
			VAT Number: 121 9285 24<br />
			Telephone: 0844 482 8665<br />
			sales@expressmystuff.co.uk<br />
			https://www.expressmystuff.co.uk<br />
		</td></tr>
		<p>Remember your entered sizes and dimensions need to be accurate otherwise you will be automatically charged for any excess charges. If you are unsure, use the DHL Drop Off Points: <a href='http://www.expressmystuff.co.uk/no-waiting-around-with-a-dhl-service-point-in-the-uk/'>UK</a> or <a href='http://www.expressmystuff.co.uk/why-use-a-dhl-service-point-overseas/'>Overseas</a></p>";
            $message .= "</td></tr></table></body></html>";

            $mail->Subject = 'Your Express My Stuff Order';
            $mail->Body = $message;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();

        endif;
    }

    public function purchase_orders($ems_session) {

        global $wpdb;

        $order = $this->get_order_from_session($ems_session);

        $parcels = $order->parcels;
        $transactions = array();
        foreach ($parcels as $parcel) {

            $rate_object = $parcel->rate_object;
            $description = $parcel->dimensions . ' x ' . $parcel->weight;

            $transaction = Shippo_Transaction::create(array(
                        'rate' => $rate_object,
                        'async' => false,
            ));

            if ($transaction->status == 'SUCCESS') {


                $label_url = $transaction->label_url;
                $invoice_url = $transaction->commercial_invoice_url;
                $transaction_reference = $transaction->tracking_number;

                $this_transaction = array('description' => $description, 'label' => $label_url, 'tracking' => $transaction_reference, 'invoice' => $invoice_url);

                array_push($transactions, $this_transaction);





                $data = array('ems_session' => $ems_session,
                    'rate_object' => $rate_object,
                    'description' => $description,
                    'transaction_reference' => $transaction_reference,
                    'label_url' => $label_url,
                    'invoice_url' => $invoice_url);


                if ($wpdb->insert('ems_transactions', $data)) {
                    $messages['status'] = 'SUCCESS';
                } else {
                    $messages['status'] = 'ERROR';
                }
            }
        }
        
        $this->send_transaction_email($parcels, $transactions, $order);
    }

    public function purchase_orders_debug($ems_session) {

        global $wpdb;

        $order = $this->get_order_from_session($ems_session);

        $parcels = $order->parcels;
        $transactions = array();
        foreach ($parcels as $parcel) {

            $rate_object = $parcel->rate_object;
            $description = $parcel->dimensions . ' x ' . $parcel->weight;

            $transaction = Shippo_Transaction::create(array(
                        'rate' => $rate_object,
                        'async' => false,
            ));


            if ($transaction->status == 'SUCCESS') {


                $label_url = $transaction->label_url;
                $invoice_url = $transaction->commercial_invoice_url;
                $transaction_reference = $transaction->tracking_number;

                $this_transaction = array('description' => $description, 'label' => $label_url, 'tracking' => $transaction_reference, 'invoice' => $invoice_url);

                array_push($transactions, $this_transaction);





                $data = array('ems_session' => $ems_session,
                    'rate_object' => $rate_object,
                    'description' => $description,
                    'transaction_reference' => $transaction_reference,
                    'label_url' => $label_url,
                    'invoice_url' => $invoice_url);


                $wpdb->insert('ems_transactions', $data);
            }
        }

        $this->send_transaction_email_debug($order, $parcels, $transactions, $order);
    }

    public function get_order_from_session($ems_session) {


        global $wpdb;

        $sql = "SELECT * FROM ems_shipments WHERE ems_session = '$ems_session'";

        $row = $wpdb->get_row($sql);

        $row->delivery_address = unserialize($row->delivery_address);
        $row->collection_address = unserialize($row->collection_address);
        $row->parcels = json_decode($row->parcels);

        return $row;
    }

    public function add_markup() {


        global $wpdb;

        $data = $_POST['newmarkup'];

        $wpdb->insert('ems_markups', $data);

        header('Location: admin.php?page=ems_markup');
    }

    public function get_markups() {

        global $wpdb;

        $sql = "SELECT * FROM ems_markups";

        $results = $wpdb->get_results($sql);

        return $results;
    }

    public function delete_markup() {

        global $wpdb;

        $markup_id = $_GET['markup_id'];

        $sql = "DELETE FROM ems_markups WHERE id = $markup_id";

        $wpdb->query($sql);

        header('Location: admin.php?page=ems_markup');
    }
    
    public function add_promo() {


        global $wpdb;

        $data = $_POST['newpromotion'];

        $wpdb->insert('ems_promocode', $data);

        header('Location: admin.php?page=ems_promotion');
    }

    public function get_promocodes() {

        global $wpdb;

        $sql = "SELECT * FROM ems_promocode";

        $results = $wpdb->get_results($sql);

        return $results;
    }

    public function delete_promocode() {

        global $wpdb;

        $code_id = $_GET['promocode_id'];

        $sql = "DELETE FROM ems_promocode WHERE id = $code_id";

        $wpdb->query($sql);

        header('Location: admin.php?page=ems_promotion');
    }
    
    public function update_promotion() {

        global $wpdb;

        $data = array(
            'code'      => $_GET['code'],
            'discount'  => $_GET['dicount']
        );

        $where = array('id' => $_GET['promo_id']);

        $wpdb->update('ems_promocode', $data, $where);

        header('Location: admin.php?page=ems_promotion&u=yes');
    }
    
    public function update_ems_option($key, $value) {


        global $wpdb;

        $data = array('meta_value' => $value);
        $where = array('meta_key' => $key);

        $wpdb->update('ems_options', $data, $where);
    }

    public function get_ems_option($key) {

        global $wpdb;

        $sql = "SELECT * FROM ems_options WHERE meta_key = '$key'";

        $row = $wpdb->get_row($sql);

        return $row->meta_value;
    }

    public function update_base_rate() {

        $markup = $_POST['base_markup'];

        $this->update_ems_option('base_markup', $markup);

        header('Location: admin.php?page=ems_markup');
    }

    public function get_markup_for_parcel($collection_country_code, $delivery_country_code) {


        global $wpdb;

        $sql = "SELECT * FROM ems_markups WHERE collection_country = '$collection_country_code' AND delivery_country = '$delivery_country_code'";

        $row = $wpdb->get_row($sql);

        if (!empty($row)):

            return $row->markup;

        else:

            return $this->get_ems_option('base_markup');

        endif;
    }

    public function get_markup_for_parcel_kg($collection_country_code, $delivery_country_code, $kg, $service_level) {


        global $wpdb;

        $sql = "SELECT markup_5,markup_15,markup_30,markup FROM ems_markups WHERE collection_country = '$collection_country_code' AND delivery_country = '$delivery_country_code' AND service_level = '$service_level'";


        $row = $wpdb->get_row($sql);

        if (!empty($row)):

            if ($kg == 5): $mk = $row->markup_5;
            elseif ($kg == 15): $mk = $row->markup_15;
            elseif ($kg == 30): $mk = $row->markup_30;
            endif;

            if ($mk > 0): return $mk;
            else: return $row->markup;
            endif;


            return $row->markup;

        else:

            return $this->get_ems_option('base_markup');

        endif;
    }

    public function get_markup_for_parcel_kg_tiers($collection_country_code, $delivery_country_code, $kg, $service_level) {


        global $wpdb;

        $weight = 5;

        if ($kg <= 5): $weight = 5;
        elseif ($kg > 5 && $kg <= 15): $weight = 15;
        else: $weight = 30;
        endif;

        $sql = "SELECT * FROM ems_markups WHERE collection_country = '$collection_country_code' AND delivery_country = '$delivery_country_code' AND service_level = '$service_level'";


        $row = $wpdb->get_row($sql);

        if (!empty($row)):

            if ($weight == 5): $mk = $row->markup_5;
            elseif ($weight == 15): $mk = $row->markup_15;
            elseif ($weight == 30): $mk = $row->markup_30;
            endif;

            if ($mk > 0): return $mk;
            else: return $row->markup;
            endif;


            return $row->markup;

        else:

            return $this->get_ems_option('base_markup');

        endif;
    }

    public function get_order_transactions($ems_session) {


        global $wpdb;

        $sql = "SELECT * FROM ems_transactions WHERE ems_session = '$ems_session'";

        $results = $wpdb->get_results($sql);

        return $results;
    }

    public function get_user($id) {

        global $wpdb;

        $sql = "SELECT * FROM ems_users WHERE id = $id";

        $row = $wpdb->get_row($sql);

        if (!empty($row)):
            return $row;
        endif;
    }

    public function get_orders() {


        global $wpdb;

        $sql = "SELECT * FROM ems_shipments WHERE is_paid = 1 ORDER BY date DESC";

        $results = $wpdb->get_results($sql);

        foreach ($results as $key => $value):

            $results[$key]->transactions = $this->get_order_transactions($value->ems_session);

            if ($value->ems_user_id != 0):

                $results[$key]->user_info = $this->get_user($value->ems_user_id);

            endif;

        endforeach;

        return $results;
    }

    public function get_order($id) {


        global $wpdb;

        $sql = "SELECT * FROM ems_shipments WHERE id = $id";

        $row = $wpdb->get_row($sql);

        if (empty($row)): die('No order information available');
        else:


            $row->transactions = $this->get_order_transactions($row->ems_session);
            if ($row->ems_user_id != 0):
                $row->user_info = $this->get_user($row->ems_user_id);
            endif;

            return $row;

        endif;
    }

    public function get_my_orders() {

        global $wpdb;
        session_start();
        $user = $_SESSION['ems_user'];

        $id = $user->id;

        $sql = "SELECT * FROM ems_shipments WHERE ems_user_id = $id";


        $results = $wpdb->get_results($sql);

        foreach ($results as $key => $value):

            $results[$key]->transactions = $this->get_order_transactions($value->ems_session);

            if ($value->ems_user_id != 0):

                $results[$key]->user_info = $this->get_user($value->ems_user_id);

            endif;

        endforeach;

        return $results;
    }

    public function update_account() {

        global $wpdb;

        session_start();

        $user = $_SESSION['ems_user'];

        $user_id = $user->id;

        $ems_user = $_POST['emsuser'];

        if ($ems_user['password'] != ''):

            $ems_user['password'] = sha1($ems_user['password']);

        else:
            unset($ems_user['password']);
        endif;

        $where = array('id' => $user_id);


        $wpdb->update('ems_users', $ems_user, $where);

        $sql = "SELECT * FROM ems_users WHERE id = $user_id";

        $row = $wpdb->get_row($sql);

        $_SESSION['ems_user'] = $row;

        header('Location: ' . get_bloginfo('home') . '/my-account?account&updated');
    }

    public function get_country_from_code($code) {


        global $wpdb;

        $sql = "SELECT * FROM ems_countries WHERE country_code = '$code'";

        $row = $wpdb->get_row($sql);

        if (!empty($row)):
            return $row;
        endif;
    }

    public function convert_gbp_currency($currency_to, $amount) {


        global $wpdb;

        $sql = "SELECT * FROM ems_currencies WHERE currency = '$currency_to'";
        $result = $wpdb->get_row($sql);

        $gbp_rate = $result->gbp_rate;

        $natural_rate = (1 / $gbp_rate);

        $new_amount = $amount * $natural_rate;

        return number_format($new_amount, 2);
    }

    public function do_quick_quote_gb() {
        $input = 3.5;
        echo $number = ceil($input / 10) * 10
        ?>
        GB rates will appear here from spreadsheet
        <?php
    }

    public function do_quick_quote() {
        /* ini_set('display_errors', 1);
          ini_set('display_startup_errors', 1);
          error_reporting(E_ALL); */
        //$currency_symbol = $this->get_currency_symbol();

        $from = $_POST['from'];
        $to = $_POST['to'];
        $journey = $_POST['journey'];

        global $wpdb;

        if (!is_numeric($from)):
            // $sql = "SELECT * FROM ems_countries WHERE country_code = '$from'";
            // $from_country = $wpdb->get_row($sql);
            // $from_country_code = $from_country->country_code;

            // $sql = "SELECT * FROM ems_countries WHERE country_code = '$to'";
            // $to_country = $wpdb->get_row($sql);
            // $to_country_code = $to_country->country_code;


            $sql = "SELECT country_code,city,postcode FROM ems_countries WHERE country_code = $from OR country_code = $to";
            $from_country = $wpdb->get_results($sql);
            $from_country_code = $from_country[0]->country_code;
            $to_country_code = $from_country[1]->country_code;

            $from_country1 = $from_country[0];
            $to_country1 = $from_country[1];
        else:
            // $sql = "SELECT * FROM ems_countries WHERE id = $from";
            // $from_country = $wpdb->get_row($sql);
            // $from_country_code = $from_country->country_code;

            // $sql = "SELECT * FROM ems_countries WHERE id = $to";
            // $to_country = $wpdb->get_row($sql);
            // $to_country_code = $to_country->country_code;

            $sql = "SELECT * FROM ems_countries WHERE id = $from OR id = $to";
            $from_country = $wpdb->get_results($sql);
            //$from_country_code = $from_country[0]->country_code;
            //$to_country_code = $from_country[1]->country_code;

            //$from_country1 = $from_country[0];
            //$to_country1 = $from_country[1];
            
             //get a row as an assoc. array
             $sql_from = "SELECT country_code,city,postcode FROM ems_countries WHERE id = $from";
            $from_country_data = $wpdb->get_results($sql_from);
            $from_country_code = $from_country_data[0]->country_code;

             $sql_to = "SELECT country_code,city,postcode FROM ems_countries WHERE id = $to";
            $to_country_data = $wpdb->get_results($sql_to);
            $to_country_code = $to_country_data[0]->country_code;

            $from_country1 = $from_country_data[0];
            $to_country1 = $to_country_data[0];

// print_r($from_country[0]);
// die();

        endif;
        $width = 1;
        $height = 1;
        $length = 1;


        $eu_countries = [
            "BE", "BG", "CZ", "DK", "DE", "EE", "IE", "EL", "ES", "FR", "HR", "IT", "CY",
            "LV", "LT", "LU", "HU", "MT", "NL", "AT", "PL", "PT", "RO", "SI", "SK", "FI",
            "SE", "UK", "GB"
        ];
        $final_data = array();
        if (in_array($from_country_code, $eu_countries)): $from_zone = 'europe';
        else: $to_zone = 'worldwide';
        endif;
        if (in_array($to_country_code, $eu_countries)): $to_zone = 'europe';
        else: $to_zone = 'worldwide';
        endif;

        if ($from_zone == 'europe' && $to_zone == 'europe'): $zone = 'europe';
        else: $zone = 'worldwide';
        endif;

        if ($from_country_code == 'GB' && $to_country_code == 'GB'): $zone = 'domestic';
        endif;



        //$weights = array($_POST['weight']);
        $weights = array();
        $weights[] = '5';
        $weights[] = '15';
        $weights[] = '30';
        //$weights = array(5);


        foreach ($weights as $weight):
            $from_address = array(
                'object_purpose' => 'QUOTE',
                'name' => 'Mr Hippo',
                'company' => 'Shippo',
                'street1' => '',
                'city' => $from_country1->city,
                'state' => '',
                'zip' => $from_country1->postcode,
                'country' => $from_country_code,
                'phone' => '+1 555 341 9393',
                'email' => 'mr-hippo@goshipppo.com',
            );

            // Example to_address array
            // The complete refence for the address object is available here: https://goshippo.com/docs/reference#addresses
            $to_address = array(
                'object_purpose' => 'QUOTE',
                'name' => 'Ms Hippo',
                'company' => 'San Diego Zoo',
                'street1' => '',
                'city' => $to_country1->city,
                'state' => '',
                'zip' => $to_country1->postcode,
                'country' => $to_country_code,
                'phone' => '+1 555 341 9393',
                'email' => 'ms-hippo@goshipppo.com',
            );

            // Parcel information array
            // The complete reference for parcel object is here: https://goshippo.com/docs/reference#parcels
            $parcel = array(
                'length' => $length,
                'width' => $width,
                'height' => $height,
                'distance_unit' => 'cm',
                'weight' => $weight,
                'mass_unit' => 'kg',
            );
// print_r($parcel);
// die();
            // Example shipment object
            // For complete reference to the shipment object: https://goshippo.com/docs/reference#shipments
            // This object has async=false, indicating that the function will wait until all rates are generated before it returns.
            // By default, Shippo handles responses asynchronously. However this will be depreciated soon. Learn more: https://goshippo.com/docs/async
            if ($from_country_code == 'GB'): $carriers = array($this->uk_carrier);
            else: $carriers = array($this->foreign_carrier);
            endif;

            $shipment = Shippo_Shipment::create(
                            array(
                                'object_purpose' => 'QUOTE',
                                'address_from' => $from_address,
                                'address_to' => $to_address,
                                'parcels' => $parcel,
                                'carrier_accounts' => $carriers,
                                'async' => false
            ));



            // Rates are stored in the `rates_list` array inside the shipment object
            $rates = $shipment['rates'];
            $obj_id = $shipment['object_id'];


            if ($this->currency != 'GBP'):

                $currency_rates = Shippo_Shipment::get_shipping_rates(
                                array(
                                    'id' => $obj_id,
                                    'currency' => $this->currency
                                )
                );




                $rates = $currency_rates->results;


            endif;

            /* $accounts = Shippo_CarrierAccount::all();
              echo "<pre>";
              print_r($accounts);
              echo "</pre>"; */ /*
              ?>
              <h3><?php echo $weight;?>kg Prices</h3>
              <table class='table table-striped table-hover'>


              <thead>
              <tr>
              <th>Service</th>
              <th>Service Level</th>
              <th>Price</th>
              <th>Carrier Account</th>
              <th>Rate Object ID (Shippo)</th>
              </tr>

              </thead>

              <tbody>

              <?php foreach($rates as $rate):

              switch($rate->carrier_account):

              case '501c726e695042db80ab01cacfb64004':
              $account_id = '418747074';
              break;
              case '608bfd57e42d4a56970e893d043b4d3c':
              $account_id = '958822864';
              break;
              endswitch;
              ?>
              <tr>
              <td><?php echo $rate->provider;?></td>
              <td><?php echo $rate->servicelevel->name;?></td>
              <td><?php echo $rate->amount;?> <?php echo $rate->currency;?></td>
              <td>DHL Account ID:<br /><strong><?php echo $account_id;?></strong><br />GoShippo Carrier Object:<br /><strong><?php echo $rate->carrier_account;?></strong></td>
              <td><?php echo $rate->object_id;?></td>
              </tr>

              <?php endforeach;?>

              </tbody>

              </table>
              <?php
             */

            foreach ($rates as $key => $value):

                if ($value->amount < 1 || $value->amount_local < 1):
                    unset($rates[$key]);
                endif;

            endforeach;

            $weight = 'w' . $weight;
            foreach ($rates as $rate):

                if ($rate->servicelevel->name == 'EXPRESS WORLDWIDE DOC'):
                    $markup = $this->get_markup_for_parcel_kg($from_country_code, $to_country_code, $weight, 'express');

                    if ($rate->currency == 'GBP'):
                        $amount = $rate->amount;
                    else:
                        $amount = $rate->amount_local;
                    endif;
                    $currency = $rate->currency;

                    if ($amount < 1): continue;
                    endif;



                    if ($journey == 'single'):



                        if (isset($final_data[$weight . 'kg']['express']['amount']) && $final_data[$weight . 'kg']['express']['amount'] < $amount):
                            continue;
                        endif;
                    else:

                        $a2 = $amount * 2;
                        if (isset($final_data[$weight . 'kg']['express']['amount']) && $final_data[$weight . 'kg']['express']['amount'] < $a2):
                            continue;
                        endif;

                    endif;

                    $one_percent = $amount / 100;
                    $markup_amount = $one_percent * $markup;
                    $amount = number_format($markup_amount + $amount, 2);
                    if ($journey == 'single'):
                        $final_data[$weight . 'kg']['express']['amount'] = $amount;
                        $final_data[$weight . 'kg']['express']['currency'] = $currency;
                        $ow = str_replace('w', '', $weight);
                        $final_data[$weight . 'kg']['express']['url'] = get_bloginfo('home') . "/order?cf=" . $from_country_code . "&ct=" . $to_country_code . "&amp;pr=" . $amount . "&amp;kg=" . $ow . "&amp;protocol=express";
                    else:
                        $final_data[$weight . 'kg']['express']['amount'] = number_format($amount * 2, 2);
                        $final_data[$weight . 'kg']['express']['currency'] = $currency;
                        $ow = str_replace('w', '', $weight);
                        $final_data[$weight . 'kg']['express']['url'] = get_bloginfo('home') . "/order?cf=" . $from_country_code . "&ct=" . $to_country_code . "&amp;pr=" . number_format($amount * 2, 2) . "&amp;kg=" . $ow . "&amp;protocol=express";
                    endif;
                endif;

                if ($rate->servicelevel->name == 'ECONOMY SELECT DOC'):
                    $markup = $this->get_markup_for_parcel_kg($from_country_code, $to_country_code, $weight, 'standard');
                    if ($rate->currency == 'GBP'):
                        $amount = $rate->amount;
                    else:
                        $amount = $rate->amount_local;
                    endif;
                    $currency = $rate->currency;

                    if ($amount < 1): continue;
                    endif;

                    if ($journey == 'single'):

                        if (isset($final_data[$weight . 'kg']['standard']['amount']) && $final_data[$weight . 'kg']['standard']['amount'] < $amount):
                            continue;
                        endif;
                    else:

                        $a2 = $amount * 2;
                        if (isset($final_data[$weight . 'kg']['standard']['amount']) && $final_data[$weight . 'kg']['standard']['amount'] < $a2):
                            continue;
                        endif;

                    endif;

                    $one_percent = $amount / 100;
                    $markup_amount = $one_percent * $markup;
                    $amount = number_format($markup_amount + $amount, 2);

                    if ($journey == 'single'):
                        $final_data[$weight . 'kg']['standard']['amount'] = $amount;
                        $final_data[$weight . 'kg']['standard']['currency'] = $currency;
                        $ow = str_replace('w', '', $weight);
                        $final_data[$weight . 'kg']['standard']['url'] = get_bloginfo('home') . "/order?cf=" . $from_country_code . "&ct=" . $to_country_code . "&amp;pr=" . $amount . "&amp;kg=" . $ow . "&amp;protocol=express";
                    else:
                        $final_data[$weight . 'kg']['standard']['amount'] = number_format($amount * 2, 2);
                        $final_data[$weight . 'kg']['standard']['currency'] = $currency;
                        $ow = str_replace('w', '', $weight);
                        $final_data[$weight . 'kg']['standard']['url'] = get_bloginfo('home') . "/order?cf=" . $from_country_code . "&ct=" . $to_country_code . "&amp;pr=" . number_format($amount * 2, 2) . "&amp;kg=" . $ow . "&amp;protocol=express";
                    endif;
                endif;



                if ($rate->servicelevel->name == 'EXPRESS WORLDWIDE EU DOC'):
                    $markup = $this->get_markup_for_parcel_kg($from_country_code, $to_country_code, $weight, 'express');
                    if ($rate->currency == 'GBP'):
                        $amount = $rate->amount;
                    else:
                        $amount = $rate->amount_local;
                    endif;
                    $currency = $rate->currency;
                    if ($amount < 1): continue;
                    endif;

                    if ($journey == 'single'):

                        if (isset($final_data[$weight . 'kg']['express']['amount']) && $final_data[$weight . 'kg']['express']['amount'] < $amount):
                            continue;
                        endif;
                    else:

                        $a2 = $amount * 2;
                        if (isset($final_data[$weight . 'kg']['express']['amount']) && $final_data[$weight . 'kg']['express']['amount'] < $a2):
                            continue;
                        endif;

                    endif;
                    $one_percent = $amount / 100;
                    $markup_amount = $one_percent * $markup;
                    $amount = number_format($markup_amount + $amount, 2);
                    if ($journey == 'single'):
                        $final_data[$weight . 'kg']['express']['amount'] = $amount;
                        $final_data[$weight . 'kg']['express']['currency'] = $currency;
                        $ow = str_replace('w', '', $weight);
                        $final_data[$weight . 'kg']['express']['url'] = get_bloginfo('home') . "/order?cf=" . $from_country_code . "&ct=" . $to_country_code . "&amp;pr=" . $amount . "&amp;kg=" . $ow . "&amp;protocol=express";
                    else:
                        $final_data[$weight . 'kg']['express']['amount'] = number_format($amount * 2, 2);
                        $final_data[$weight . 'kg']['express']['currency'] = $currency;
                        $ow = str_replace('w', '', $weight);
                        $final_data[$weight . 'kg']['express']['url'] = get_bloginfo('home') . "/order?cf=" . $from_country_code . "&ct=" . $to_country_code . "&amp;pr=" . number_format($amount * 2, 2) . "&amp;kg=" . $ow . "&amp;protocol=express";
                    endif;
                endif;


                if ($rate->servicelevel->name == 'DOMESTIC EXPRESS DOC' && $zone == 'domestic'):

                    $attributes = $rate->attributes;


                    $markup = $this->get_markup_for_parcel_kg($from_country_code, $to_country_code, $weight, 'standard');
                    if ($rate->currency == 'GBP'):
                        $amount = $rate->amount;
                    else:
                        $amount = $rate->amount_local;
                    endif;
                    $currency = $rate->currency;
                    if ($amount < 1): continue;
                    endif;

                    if ($journey == 'single'):

                        if (isset($final_data[$weight . 'kg']['standard']['amount']) && $final_data[$weight . 'kg']['standard']['amount'] < $amount):
                            continue;
                        endif;
                    else:

                        $a2 = $amount * 2;
                        if (isset($final_data[$weight . 'kg']['standard']['amount']) && $final_data[$weight . 'kg']['standard']['amount'] < $a2):
                            continue;
                        endif;

                    endif;
                    $one_percent = $amount / 100;
                    $markup_amount = $one_percent * $markup;
                    $amount = number_format($markup_amount + $amount, 2);
                    if ($journey == 'single'):
                        $final_data[$weight . 'kg']['standard']['amount'] = $amount;
                        $final_data[$weight . 'kg']['standard']['currency'] = $currency;
                        $ow = str_replace('w', '', $weight);
                        $final_data[$weight . 'kg']['standard']['url'] = get_bloginfo('home') . "/order?cf=" . $from_country_code . "&ct=" . $to_country_code . "&amp;pr=" . $amount . "&amp;kg=" . $ow . "&amp;protocol=express";
                    else:
                        $final_data[$weight . 'kg']['standard']['amount'] = number_format($amount * 2, 2);
                        $final_data[$weight . 'kg']['standard']['currency'] = $currency;
                        $ow = str_replace('w', '', $weight);
                        $final_data[$weight . 'kg']['standard']['url'] = get_bloginfo('home') . "/order?cf=" . $from_country_code . "&ct=" . $to_country_code . "&amp;pr=" . number_format($amount * 2, 2) . "&amp;kg=" . $ow . "&amp;protocol=express";
                    endif;


                    $markup = $this->get_markup_for_parcel_kg($from_country_code, $to_country_code, $weight, 'express');
                    if ($rate->currency == 'GBP'):
                        $amount = $rate->amount;
                    else:
                        $amount = $rate->amount_local;
                    endif;
                    $currency = $rate->currency;

                    $one_percent = $amount / 100;
                    $markup_amount = $one_percent * $markup;
                    $amount = number_format($markup_amount + $amount, 2);
                    if ($journey == 'single'):
                        $final_data[$weight . 'kg']['express']['amount'] = $amount;
                        $final_data[$weight . 'kg']['express']['currency'] = $currency;
                        $ow = str_replace('w', '', $weight);
                        $final_data[$weight . 'kg']['express']['url'] = get_bloginfo('home') . "/order?cf=" . $from_country_code . "&ct=" . $to_country_code . "&amp;pr=" . $amount . "&amp;kg=" . $ow . "&amp;protocol=express";
                    else:
                        $final_data[$weight . 'kg']['express']['amount'] = number_format($amount * 2, 2);
                        $final_data[$weight . 'kg']['express']['currency'] = $currency;
                        $ow = str_replace('w', '', $weight);
                        $final_data[$weight . 'kg']['express']['url'] = get_bloginfo('home') . "/order?cf=" . $from_country_code . "&ct=" . $to_country_code . "&amp;pr=" . number_format($amount * 2, 2) . "&amp;kg=" . $ow . "&amp;protocol=express";
                    endif;


                endif;





            endforeach;
        endforeach;
        $final_data['currency_symbol'] = $this->get_currency_symbol();
        echo json_encode($final_data);

        die();
    }

    /* public function do_quick_quote(){


      $from = $_POST['from'];
      $to = $_POST['to'];
      $journey = $_POST['journey'];

      global $wpdb;

      if(!is_numeric($from)):
      $from_country_code = $from;
      else:

      $sql = "SELECT * FROM ems_countries WHERE id = $from";
      $from_country = $wpdb->get_row($sql);
      $from_country_code = $from_country->country_code;

      endif;

      if(!is_numeric($to)):

      $to_country_code = $to;
      else:

      $sql = "SELECT * FROM ems_countries WHERE id = $to";
      $to_country = $wpdb->get_row($sql);
      $to_country_code = $to_country->country_code;

      endif;

      $eu_countries = [
      "BE", "BG", "CZ", "DK", "DE", "EE", "IE", "EL", "ES", "FR", "HR", "IT", "CY",
      "LV", "LT", "LU", "HU", "MT", "NL", "AT", "PL", "PT", "RO", "SI", "SK", "FI",
      "SE", "UK", "GB"
      ];

      if(in_array($from_country_code, $eu_countries)): $from_zone = 'europe'; else: $to_zone = 'worldwide'; endif;
      if(in_array($to_country_code, $eu_countries)): $to_zone = 'europe'; else: $to_zone = 'worldwide'; endif;

      if($from_zone == 'europe' && $to_zone == 'europe'): $zone = 'europe'; else: $zone = 'worldwide'; endif;

      $sql = "SELECT * FROM ems_pricing WHERE from_country = '$from_country_code' AND to_country = '$to_country_code' LIMIT 1";

      $row = $wpdb->get_row($sql);

      $price_5 = number_format($row->price_5, 2);
      $price_15 = number_format($row->price_15, 2);
      $price_30 = number_format($row->price_30, 2);
      $price_per_kilo = number_format($row->price_per_kilo, 2);

      if($price_5 < 1): die(); endif;

      $markup = $this->get_markup_for_parcel($from_country_code, $to_country_code);

      $price_5_one_percent = $price_5/100;
      $price_5_markup = $price_5*$price_5_one_percent;
      $price_5 = $price_5+$price_5_markup;

      $price_15_one_percent = $price_15/100;
      $price_15_markup = $price_15*$price_15_one_percent;
      $price_15 = $price_15+$price_15_markup;

      $price_30_one_percent = $price_30/100;
      $price_30_markup = $price_30*$price_30_one_percent;
      $price_30 = $price_30+$price_30_markup;

      $price_per_kilo_one_percent = $price_per_kilo/100;
      $price_per_kilo_markup = $price_per_kilo*$price_per_kilo_one_percent;
      $price_per_kilo = $price_per_kilo+$price_per_kilo_markup;


      $currency_symbol = $this->get_currency_symbol();
      if($journey == 'return'):

      $price_5 = $price_5*2;
      $price_15 = $price_15*2;
      $price_30 = $price_30*2;

      endif;

      $currency = $this->currency;

      if($currency != 'GBP'):

      $price_5 = $this->convert_gbp_currency($currency, $price_5);
      $price_15 = $this->convert_gbp_currency($currency, $price_15);
      $price_30 = $this->convert_gbp_currency($currency, $price_30);
      $price_per_kilo = $this->convert_gbp_currency($currency, $price_per_kilo);

      endif;


      $price_5 = number_format($price_5, 2);
      $price_15 = number_format($price_15, 2);
      $price_30 = number_format($price_30, 2);
      $price_per_kilo = number_format($price_per_kilo, 2);

      if($zone == 'europe'):

      ?>


      <div class="col-md-4">
      <div class="orangetext">Standard</div>

      </div>
      <div class="col-md-5">

      <div><span class="quick-quote-price-weight">Up to 5kg:</span> <span data-bind="text: Localise.formatCurrency(PriceIncTax())" class="quick-quote-price"><?php echo $currency_symbol;?><?php echo $price_5;?></span></div>

      <div><span class="quick-quote-price-weight">Up to 15kg:</span> <span data-bind="text: Localise.formatCurrency(PriceIncTax())" class="quick-quote-price"><?php echo $currency_symbol;?><?php echo $price_15;?></span></div>

      <div><span class="quick-quote-price-weight">Up to 30kg:</span> <span data-bind="text: Localise.formatCurrency(PriceIncTax())" class="quick-quote-price"><?php echo $currency_symbol;?><?php echo $price_30;?></span> + <?php echo $currency_symbol;?><?php echo $price_per_kilo;?> per extra kg</div>


      </div>
      <div class="col-md-3">

      <input name="" value="" type="hidden">

      <a href="<?php bloginfo('home');?>/order?cf=<?php echo $from_country_code;?>&amp;ct=<?php echo $to_country_code;?>" data-orig="<?php bloginfo('home');?>/order?cf=<?php echo $from_country_code;?>&amp;ct=<?php echo $to_country_code;?>" class="bnbtn btn btn-primary btn-lg" data-price="">Book Now</a>
      </div>
      <div style='height:1px; background-color:#000; clear:both; width:100%; margin:15px 0;'></div>
      <?php endif;?>

      <?php $express_countries = array('GB', 'US', 'DE', 'FR', 'IT', 'ES', 'AU', 'AT', 'CA', 'IE');
      if(in_array($from_country_code, $express_countries)):

      $price_5 = number_format($price_5*1.4, 2);
      $price_15 = number_format($price_15*1.4, 2);
      $price_30 = number_format($price_30*1.6, 2);

      $currency_symbol = $this->get_currency_symbol();
      ?>


      <div class="col-md-4">
      <div class="orangetext">Express</div>
      <div>2-3 working days</div>

      </div>
      <div class="col-md-5">

      <div><span class="quick-quote-price-weight">Up to 5kg:</span> <span data-bind="text: Localise.formatCurrency(PriceIncTax())" class="quick-quote-price"><?php echo $currency_symbol;?><?php echo $price_5;?></span></div>

      <div><span class="quick-quote-price-weight">Up to 15kg:</span> <span data-bind="text: Localise.formatCurrency(PriceIncTax())" class="quick-quote-price"><?php echo $currency_symbol;?><?php echo $price_15;?></span></div>

      <div><span class="quick-quote-price-weight">Up to 30kg:</span> <span data-bind="text: Localise.formatCurrency(PriceIncTax())" class="quick-quote-price"><?php echo $currency_symbol;?><?php echo $price_30;?></span> + <?php echo $currency_symbol;?><?php echo $price_per_kilo;?> per extra kg</div>


      </div>
      <div class="col-md-3">

      <input name="" value="" type="hidden">

      <a href="<?php bloginfo('home');?>/order?cf=<?php echo $from_country_code;?>&amp;ct=<?php echo $to_country_code;?>" data-orig="<?php bloginfo('home');?>/order?cf=<?php echo $from_country_code;?>&amp;ct=<?php echo $to_country_code;?>" class="bnbtn btn btn-primary btn-lg" data-price="">Book Now</a>
      </div>


      <?php endif;?>

      <?php

      die();
      } */

    public function update_country_markups() {

        global $wpdb;

        $data = array(
            'markup' => $_GET['dm'],
            'markup_5' => $_GET['m5'],
            'markup_15' => $_GET['m15'],
            'markup_30' => $_GET['m30'],
            'service_level' => $_GET['sl']
        );

        $where = array('id' => $_GET['markup_id']);

        $wpdb->update('ems_markups', $data, $where);

        header('Location: admin.php?page=ems_markup&u=yes');
    }

    public function get_sales_for_date($sql_date) {

        global $wpdb;

        $from_date = $sql_date . ' 00:00:00';
        $to_date = $sql_date . ' 23:59:59';

        $sql = "SELECT * FROM ems_shipments WHERE date >= '$from_date' AND date <= '$to_date' AND is_paid = 1";
        $results = $wpdb->get_results($sql);

        return count($results);
    }

    public function get_all_users() {

        global $wpdb;

        $sql = "SELECT * FROM ems_users";
        $results = $wpdb->get_results($sql);

        return $results;
    }

    public function get_unresolved_carts() {

        global $wpdb;

        $sql = "SELECT * FROM ems_shipments WHERE is_paid = 0";
        $results = $wpdb->get_results($sql);

        foreach ($results as $key => $row):

            if ($row->ems_user_id == 0):
                $results[$key]->xuser_name = $row->nouser_first_name . ' ' . $row->nouser_last_name;

            else:
                $user = $this->get_user($row->ems_user_id);
                $results[$key]->xuser_name = $user->first_name . ' ' . $user->last_name;
            endif;

        endforeach;


        return $results;
    }

    public function get_all_gb_rates() {

        global $wpdb;

        $sql = "SELECT * FROM ems_gb_rates ORDER BY kg ASC";

        $results = $wpdb->get_results($sql);

        return $results;
    }

    public function save_gb_rates() {

        global $wpdb;

        $sql = "DELETE FROM ems_gb_rates";
        $query = $wpdb->query($sql);

        $rates = $_POST['rates'];

        foreach ($rates as $rate):

            $data = array(
                'kg' => $rate['kg'],
                'lb' => $rate['lb'],
                'price' => $rate['price']
            );

            $wpdb->insert('ems_gb_rates', $data);


        endforeach;

        header('Location: admin.php?page=ems_domestic&sv');
    }

    public function get_shopping_option($key) {

        global $wpdb;

        $sql = "SELECT * FROM ems_shopping_settings WHERE meta_key = '$key' LIMIT 1";

        $row = $wpdb->get_row($sql);

        return $row->meta_value;
    }

    public function update_shopping_packages() {

        global $wpdb;

        $options = $_POST['newshoppingoption'];



        foreach ($options as $k => $option):
            if ($option['name'] == ''): unset($options[$k]);
            endif;
        endforeach;



        $options = serialize($options);


        $wpdb->update('ems_shopping_settings', array('meta_value' => $options), array('meta_key' => 'shopping_options'));
    }

    public function update_shopping_package_price() {

        global $wpdb;

        $price = $_POST['price_per_shopping_package'];

        $sql = "DELETE FROM ems_shopping_settings WHERE meta_key = 'price_per_shopping_package'";
        $wpdb->query($sql);

        $wpdb->insert('ems_shopping_settings', array('meta_key' => 'price_per_shopping_package', 'meta_value' => $price));
    }

    public function ems_buy_shopping() {

        global $wpdb;

        $user_id = $_POST['user_id'];
        $collection_address = serialize($_POST['collection_address']);
        $delivery_address = serialize($_POST['delivery_address']);
        $package_name = $_POST['package_name'];
        $package_index = $_POST['package_index'];
        $package_amount = $_POST['package_amount'];
        $ems_session = $_POST['ems_session'];
        $extra_packages = $_POST['extra_packages'];

        $data = array(
            'user_id' => $user_id,
            'ems_session' => $ems_session,
            'product_title' => $package_name,
            'product_index' => $package_index,
            'collection_address' => $collection_address,
            'delivery_address' => $delivery_address,
            'amount' => $package_amount,
            'extra_packages' => $extra_packages
        );

        $messages = array();

        if ($wpdb->insert('ems_shopping_purchases', $data)):
            $messages['status'] = 'SUCCESS';
        else:
            $messages['status'] = 'ERROR';
        endif;

        echo json_encode($messages);

        die();
    }

    public function send_shopping_email($ems_session) {


        global $wpdb;

        $sql = "SELECT * FROM ems_shopping_purchases WHERE ems_session = '$ems_session' LIMIT 1";
        $order = $wpdb->get_row($sql);

        $user_id = $order->user_id;

        $user = $this->get_user($user_id);

        $first_name = $user->first_name;
        $email = $user->email_address;
        $customer_name = $first_name . ' ' . $user->last_name;


        $message = "<html>
		<head>
		
		</head>
		<body style='background-color:#c9c9c9;'>
		<table width='600' align='center' border='0' bgcolor='#ffffff' cellpadding='20' style='background-color:#ffffff;'><tr><td>";

        $message .= "<table width='100%'><tr><td align='center'><a href='http://www.expressmystuff.co.uk'><img src='http://www.expressmystuff.co.uk/wp-content/uploads/2017/02/sitelogosmall.jpg' /></a></td></tr></table><hr />";

        $message .= "<table width='100%'><tr><td width='50%' align='left'><a href='http://www.expressmystuff.co.uk/quote/'><img src='http://www.expressmystuff.co.uk/wp-content/uploads/2017/07/beach-email-banner.jpg' /></a></td><td width='50%' align='right'><a href='http://www.expressmystuff.co.uk/express-my-shopping/'><img src='http://www.expressmystuff.co.uk/wp-content/uploads/2017/07/shopping-email-banner.png' /></a></td></tr></table><hr />";

        $message .= "<p>Dear " . $first_name . ",</p>
		
		<p>Thank you for your recent Shopping Experience order with Express My Stuff. We will be in touch shortly with your shipping labels and tracking information as soon as your order has been processed.</p>";








        $message .= "<tr><td align='left' bgcolor='#FFFFFF' style='background-color:#FFFFFF;'>
			Express My Stuff<br />		
			Montessoriuk Ltd <br />		
			Dalry<br />
			78 Cornwall Road<br />
			Harrogate,<br />
			North Yorkshire<br />
			HG1 2NE<br />
			VAT Number: 121 9285 24<br />
			Telephone: 0844 482 8665<br />
			sales@expressmystuff.co.uk<br />
			https://www.expressmystuff.co.uk<br />
		</td></tr>
		<p>Remember your entered sizes and dimensions need to be accurate otherwise you will be automatically charged for any excess charges. If you are unsure, use the DHL Drop Off Points: <a href='http://www.expressmystuff.co.uk/no-waiting-around-with-a-dhl-service-point-in-the-uk/'>UK</a> or <a href='http://www.expressmystuff.co.uk/why-use-a-dhl-service-point-overseas/'>Overseas</a></p>";
        $message .= "</td></tr></table></body></html>";


        require 'mailer/PHPMailerAutoload.php';

        $mail = new PHPMailer;


        $ems_user_id = $order->ems_user_id;




        $mail->setFrom('sales@expressmystuff.co.uk', 'Express My Stuff');
        $mail->addAddress($email, $customer_name);


        $mail->isHTML(true);

        $mail->Subject = 'Your Express My Stuff Shopping Experience Order';
        $mail->Body = $message;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
    }

    public function get_shopping_orders() {


        global $wpdb;

        $sql = "SELECT * FROM ems_shopping_purchases WHERE paid = 1 ORDER BY date DESC";
        $results = $wpdb->get_results($sql);

        foreach ($results as $key => $result):

            $user_id = $result->user_id;
            $user = $this->get_user($user_id);
            $results[$key]->user = $user;

        endforeach;

        return $results;
    }

    public function get_single_shopping_order($order_id) {

        global $wpdb;

        $sql = "SELECT * FROM ems_shopping_purchases WHERE id = $order_id";
        $row = $wpdb->get_row($sql);
        $user_id = $row->user_id;
        $row->user = $this->get_user($user_id);
        $row->delivery_address = unserialize($row->delivery_address);
        $row->collection_address = unserialize($row->collection_address);
        return $row;
    }

    public function get_parcels_for_order($order_id) {

        global $wpdb;

        $sql = "SELECT * FROM ems_shopping_rates WHERE order_id = $order_id";
        $results = $wpdb->get_results($sql);
        return $results;
    }

}
