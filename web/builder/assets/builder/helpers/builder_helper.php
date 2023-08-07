<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Function to generate template view
 *
 * @param array $data
 */
if( ! function_exists('load_template') ):
    function load_template($data = array()){
        $CI =& get_instance();

        $trialStatus = FALSE;
        /*if($CI->session->userdata('user_role') == 3){

            $subscription_data 	= modules::run('payment/subscription/getActiveSubscription');

            if(!empty($subscription_data) && count($subscription_data) == 1){

                $payment_data = modules::run('payment/getPaymentByInv', $subscription_data[0]->inv_number);

                if($payment_data){
                    $trialStatus    = $payment_data->payment_type == 127 ? TRUE : FALSE;
                }
            }
        }*/
        
        $templateName   = "main";
        
        $data           = array_merge_recursive($data, array(
            /* Additional Settings */
            'trial' => $trialStatus
        ));

        $CI->load->view($templateName, $data);
    }
endif;

/**
 * Function to generate template view
 *
 * @param array $data
 */
if( ! function_exists('themes_front') ):
    function themes_front($data = array()){
        $CI =& get_instance();
        
        $templateName   = "main_front";
        $data           = array_merge_recursive($data, array(
            /* Additional Settings */
        ));

        $CI->load->view($templateName, $data);
    }
endif;


/**
 * Function to generate random number
 *
 * @param $digits = How many number wants to outputed
 */
if (! function_exists('random_number')) {
	function random_number($digits) {
	    $min = pow(10, $digits - 1);
	    $max = pow(10, $digits) - 1;
	    return mt_rand($min, $max);
	}
}

/**
 * Function to sent email easily
 *
 */
if (! function_exists('email_helper')) {
    function email_helper($to = '', $subject = '', $message = '', $cc = '', $type = ''){
        $CI =& get_instance();

        $CI->load->library('email');

        $configs = array(
          'protocol'  => 'smtp',
          'smtp_host' => 'ssl://smtp.googlemail.com',
          'smtp_user' => 'ukmdigitalindonesia@gmail.com', // SMTP EMAIL
          'smtp_pass' => 'iclFANTASTIC', // SMTP PASS
          'smtp_port' => '465',
          'crlf'      => "\r\n",
          'newline'   => "\r\n"
        );

        // if using template from HTML source
        if (!empty($type)) :
            $configHTML = array(
                'charset'   => 'utf-8',
                'wordwrap'  => TRUE,
                'mailtype'  => 'html'
            );

            $configs = array_merge_recursive($configs, $configHTML);
        endif;

        $CI->email->initialize($configs);
        $CI->email->set_mailtype("html");
        $CI->email->from('ukmdigitalindonesia@gmail.com', 'Telkom CMS');
        $CI->email->to($to);

        if (!empty($cc)) :
            $CI->email->cc($cc);
        endif;
        
        $CI->email->subject($subject);
        $CI->email->message($message);
        
        if (! $CI->email->send() ) {  
            echo $CI->email->print_debugger();
            return 'Failed to send email';   
        }else{  
            return 'Success to send email';   
        }
    }
}


/**
 * function to get settings, use optional value if wants another condition 
 *
 * @param   $name   = view_page_name || $optional = option value || $notAjax = to make sure this is not ajax request
 * @return  $return = datafield by option name
**/
function get_setting( $name = null, $optional = [], $notAjax = ''){
        $builderFunction = new Builder_Function();
        return $builderFunction->getConfig($name);
        // base query
        /*$params = array(
            "where" => array(
                "setting_name" => $name
            ),
            "single" => TRUE
        );

        // if there's optional query
        if (!empty($optional)) :
        	$params = array_merge_recursive($params, $optional);
        endif;

        $options = $this->setting->get_all_setting($params);

        if ($notAjax == true || !$this->input->is_ajax_request()) {
        	return !empty($options) ? $options : '';
        }else{
        	// if setting data is serialized
        	if (!empty($options) && is_serialized($options->setting_value)) :
        		$options->setting_value = unserialize($options->setting_value);
        	endif;
        	
        	echo json_encode($options);exit;
        }*/
    }

/**
 * Function to get option based on their name_value
 *
 */
if (! function_exists('get_option')) {
    function get_option($name = null){
        if(empty($name)) return false;

        if (is_object(get_setting($name, '', true))) {
            return get_setting($name, '', true)->setting_value;
        }
        else{
            return false;
        }
        // return get_setting($name, '', true)->setting_value;
    }
}
 
  
 

if (! function_exists('floating_button')) {
    function floating_button(){
        $CI =& get_instance();
        $segment = isset($CI->uri->rsegments[3]) ? $CI->uri->rsegments[3] : $CI->uri->rsegments[1];
        $whitelist = array(
            'templates',
            'features',
            'support',
            'sites',
            'pricing',
            'landing'
        );

        if (in_array($segment, $whitelist)) {
            return true;
        }

        return false;
    }
}

if (! function_exists('pricing_format')) {
    function pricing_format($number = null){
        if($number > 0){
            $array_number = explode(',', number_format($number));
            $slice = array_slice($array_number, 0, count($array_number)-1, true);
    
            $bilangan = join(".", $slice);
            $ribuan   = end($array_number);

            return "<sup>Rp.</sup><strong>{$bilangan}.<span>{$ribuan}</span></strong>";
        } else {
            return "<sup>Rp.</sup><strong>0</strong>";
        }
    }
}

if (! function_exists('promo_pricing_format')) {
    function promo_pricing_format($number = null){
        if($number > 0){
            $array_number = explode(',', number_format($number));
            $slice = array_slice($array_number, 0, count($array_number)-1, true);
    
            $bilangan = join(".", $slice);
            $ribuan   = end($array_number);

            return "<strong>Rp. {$bilangan}.<span>{$ribuan}</span></strong>";
        } else {
            return "<strong>Rp. 0</strong>";
        }
    }
}

if (! function_exists('pricing_title')) {
    function pricing_title($title = null){
        $array_string = explode(' ', $title);

        $main_word = $array_string[0];
        $sub_word  = join(' ', array_slice($array_string, 1));

        return $title;
    }
}
  
  
if(! function_exists('activeMenu') ) {
    function activeMenu($string){
        $CI     =& get_instance();
        $uri    = $CI->uri->uri_string();

        return ($string == $uri ) ? "active" : '';
    }
}


if( ! function_exists('openSubmenu') ):
	function openSubMenu($stringtoCompare = array()){
		$CI			=& get_instance();
		$uri_string	= $CI->uri->uri_string();

		foreach($stringtoCompare AS $uri):
			if( strpos($uri_string, $uri) !== FALSE ):
				return ' in ';
			endif;
		endforeach;

		return "";
	}
endif;

if( !function_exists('complte_url')) : 
    function complete_url(){
        return $page = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
endif;
  

if(! function_exists('isDomain')){
    function isDomain(){
        $CI =& get_instance();
  
       return empty($GLOBALS['website_domain']) ?  FALSE : TRUE;
    }
    
}
 
if (! function_exists('user_server_prefix')) {
    function user_server_prefix(){
        $CI =& get_instance();

        $link = '';

        if( $_SERVER['HTTP_HOST'] === 'builder.icreativelabs.com' OR $_SERVER['HTTP_HOST'] === 'www.builder.icreativelabs.com' ){
            $server = explode('.', $_SERVER['HTTP_HOST']);
            if($server[0] != 'www'){
                array_unshift($server, 'wwww');
            }
            
            foreach ($server as $index => $entity) {
                if ($index == 0) {
                    $link = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", ""));
                }else{
                    $link .= '.'.$entity;
                }    
            }
        
            return ($link) ? $link : '';
        }else{
            $server = explode('.', $_SERVER['HTTP_HOST']);
            if($server[0] != 'www'){
                array_unshift($server, 'wwww');
            }
            
            foreach ($server as $index => $entity) {
                if ($index == 0) {
                    $link = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", ""));
                }else{
                    $link .= '.'.$entity;
                }    
            }
        
            return ($link) ? $link : '';
        }
	}
}

/**
 * Checking in multidimensional array
 *
 */
if (! function_exists('in_array_r')) {
    function in_array_r($item = 'needle', $array = array()){
        return preg_match('/"'.preg_quote($item, '/').'"/i' , json_encode($array));
    }
}

/**
 * Function reformat word 
 */
if (! function_exists('format_word')) {
    function format_word($word){
        return ucfirst(str_replace('_', ' ', $word));
    }
}

/**
 * Function to strpos using in_array 
 */
if (! function_exists('strpos_arr')) {
    function strpos_arr($haystack, $needle, $offset=0) {
        if(!is_array($needle)) $needle = array($needle);
        foreach($needle as $query) {
            if(strpos($haystack, $query, $offset) !== false) return true; // stop on first true result
        }
        return false;
    }
}
/**
 * Function counting active ticket user have 
 */
if (! function_exists('countTickets')) {
    function countTickets(){
        $CI =& get_instance();

        $CI->load->model('support/support_model', 'support');

        $options = array(
            "count"     => TRUE,
            "select"    => "*",
            "where"     => array("ticket_parent" => 0, "ticket_status" => 0, "created_by" => $CI->session->userdata('user_id')),
            "order"     => array(
                "key"   => "bd_support_ticket.ticket_id",
                "sort"  => "DESC"
            )
        );

        $dataSupport = $CI->support->get_data($options);

        return $dataSupport;
    }
}

if (! function_exists('user_avatar')) {
    function user_avatar($attachment_id = null){
        $CI =& get_instance();

        $CI->load->model('attachment/attachment_model', 'attachment');

        $options = array(
            "single"    => TRUE,
            "select"    => "*",
            "where"     => array('attachment_id' => $attachment_id),
            "order"     => array(
                "key"   => "attachment_id",
                "sort"  => "DESC"
            )
        );

        $dataBerkas = $CI->attachment->get_all($options);
       
        if (!empty($dataBerkas)) :
            // return base_url().'image.php?src='.base_url().'builder_uploads/'.$dataBerkas->attachment_location.'&h=500';
            return base_url() . 'attachment/thumbnail/image?src=builder_uploads/' . $dataBerkas->attachment_location . '&w=500&h=500';
        else:
            
            return "https://gravatar.com/avatar/" . md5( $CI->session->userdata("user_email") );
        endif;
    }
}
 
if (! function_exists('website_favicon')) {
    function website_favicon($attachment_id = null){
        $CI =& get_instance();

        $CI->load->model('attachment/attachment_model', 'attachment');

        $options = array(
            "single"    => TRUE,
            "select"    => "*",
            "where"     => array('attachment_id' => $attachment_id),
            "order"     => array(
                "key"   => "attachment_id",
                "sort"  => "DESC"
            )
        );

        $dataBerkas = $CI->attachment->get_all($options);
       
        if (!empty($dataBerkas)) :
            // return base_url().'image.php?src='.base_url().'builder_uploads/'.$dataBerkas->attachment_location.'&h=500';
            return get_base_url() . 'builder_uploads/' . $dataBerkas->attachment_location_tiny;
        else:
            return get_base_url().'favicon.png';
        endif;
    }
}

if (! function_exists('website_logo')) {
    function website_logo($attachment_id = null){
        $CI =& get_instance();

        $CI->load->model('attachment/attachment_model', 'attachment');

        $options = array(
            "single"    => TRUE,
            "select"    => "*",
            "where"     => array('attachment_id' => $attachment_id),
            "order"     => array(
                "key"   => "attachment_id",
                "sort"  => "DESC"
            )
        );

        $dataBerkas = $CI->attachment->get_all($options);
       
        if (!empty($dataBerkas)) :
            // return base_url().'image.php?src='.base_url().'builder_uploads/'.$dataBerkas->attachment_location.'&h=500';
            return get_base_url() . 'builder_uploads/' . $dataBerkas->attachment_location;
        else:
            return get_base_url().'assets/builder/blocks-assets/imgs/logo.svg';
        endif;
    }
}

if (! function_exists('website_image_meta')) {
    function website_image_meta($attachment_id = null){
        $CI =& get_instance();

        $CI->load->model('attachment/attachment_model', 'attachment');

        $options = array(
            "single"    => TRUE,
            "select"    => "*",
            "where"     => array('attachment_id' => $attachment_id),
            "order"     => array(
                "key"   => "attachment_id",
                "sort"  => "DESC"
            )
        );

        $dataBerkas = $CI->attachment->get_all($options);
       
        if (!empty($dataBerkas)) :
            // return base_url().'image.php?src='.base_url().'builder_uploads/'.$dataBerkas->attachment_location.'&h=500';
            return get_base_url() . 'builder_uploads/' . $dataBerkas->attachment_location;
        else:
            return get_base_url().'favicon.png';
        endif;
    }
}

if (! function_exists('website_checkout_background')) {
    function website_checkout_background($attachment_id = null){
        $CI =& get_instance();

        $CI->load->model('attachment/attachment_model', 'attachment');

        $options = array(
            "single"    => TRUE,
            "select"    => "*",
            "where"     => array('attachment_id' => $attachment_id),
            "order"     => array(
                "key"   => "attachment_id",
                "sort"  => "DESC"
            )
        );

        $dataBerkas = $CI->attachment->get_all($options);
       
        if (!empty($dataBerkas)) :
            // return base_url().'image.php?src='.base_url().'builder_uploads/'.$dataBerkas->attachment_location.'&h=500';
            return get_base_url() . 'builder_uploads/' . $dataBerkas->attachment_location;
        else:
            return get_base_url().'assets/images/checkout.jpg';
        endif;
    }
}
 
   

if (! function_exists('get_image')) {
    function get_image($image = null, $image_size = "original"){

        $arr_image = is_serialized( $image ) ? unserialize($image) : $image;

        if (empty($arr_image[0])) :
            $return_image = base_url().'assets/backend/img/image_placeholder.jpg';
        endif;
        if(!empty($image_data)){
            switch($image_size){
                case "original" :
                    $return_image = base_url().'builder_uploads/'.$image_data->attachment_location;
                break;
                case "medium" :
                    $return_image = base_url().'builder_uploads/'.$image_data->attachment_location_medium;
                break;
                case "small" :
                    $return_image = base_url().'builder_uploads/'.$image_data->attachment_location_small;
                break;
                case "tiny" :
                    $return_image = base_url().'builder_uploads/'.$image_data->attachment_location_tiny;
                break;
                case "half" :
                    $return_image = base_url().'builder_uploads/'.$image_data->attachment_location_half;
                break;
                default:
                    $return_image = base_url().'assets/backend/img/image_placeholder.jpg';
                break;
            }
        } else {
            $return_image = base_url().'assets/backend/img/image_placeholder.jpg';
        }

        return $return_image;
    }
}

if (! function_exists('shortcode')) {
    function shortcode($data = array(), $type = null, $from = 'builder'){
        $CI =& get_instance();

        switch ($type) {
            case 'post_list':
                $dataReturn = getPostListSlider($data, $from);
            break;

            case 'post_detail':
                $dataReturn = getPostListSlider($data, $from);
            break;

            case 'post_related':
                $dataReturn = getPostListSlider($data, $from);
            break;

            case 'post_featured':
                $dataReturn = getPostListSlider($data, $from);
            break;

            case 'post_slider':
                $dataReturn = getPostListSlider($data, $from);
            break;

            case 'product_list':
                $dataReturn = getPostListSlider($data, $from);
            break;
            
            case 'product_list_image':
                $dataReturn = getPostListSlider($data, $from);
            break;

            case 'product_detail':
                $dataReturn = getPostListSlider($data, $from);
            break;

            case 'product_related':
                $dataReturn = getPostListSlider($data, $from);
            break;

            case 'product_recommendation':
                $dataReturn = getPostListSlider($data, $from);
            break;

            case 'portofolio_detail_header':
                $dataReturn = getPostListSlider($data, $from);
            break;

            case 'comment_list':
                $dataReturn = getPostListSlider($data, $from);
            break;

            default:
                $dataReturn = $data;
            break;
        }

        return $dataReturn;
    }
}

 

if (! function_exists('view_shorcode')) {
    function shortcode_view($view_name = null, $data = array(), $backup_view = null){
        //$CI =& get_instance();
        $builderFunction = new Builder_Function();
        return $builderFunction->view('pages/post_list_views/'.strtolower($view_name), $data); 
    }
}


if (! function_exists('support')) {
    function support($params){
        $CI =& get_instance();
        $builderFunction = new Builder_Function();
        if($params == 'support'){
            return $builderFunction->getConfig('support_url');
        }else{
            return $builderFunction->getConfig('default_url');
        }
    }
}

if (!function_exists('subdomain')){
    /**
	 * 
	 * For developer 
	 * use this function / use subdomain
	 * @ params user_id
	 */

	 function getSubdomain($user_id = false){
        //$CI         =& get_instance();
        //$subDomain  = $CI->session->userdata('user_name'); //this is the original subdomain
        $subDomain  = "telkom";
		$origin		= $_SERVER['HTTP_HOST'];
        $toPart		= explode('.', $origin);        
        $replace	= str_replace($toPart[0], $subDomain , $toPart);
        $protocol 	= (isset( $_SERVER['HTTPS'] ) AND $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
    
        $compile	= implode('.',$replace);
        $rebuild	= $protocol . $compile .'/';
    
		return $rebuild; 
		
    }
}

/**
 * Convert number with unit byte to bytes unit
 * @link https://en.wikipedia.org/wiki/Metric_prefix
 * @param string $value a number of bytes with optinal SI decimal prefix (e.g. 7k, 5mb, 3GB or 1 Tb)
 * @return integer|float A number representation of the size in BYTES (can be 0). otherwise FALSE
 * source : https://gist.github.com/Chengings/9597366
 */
if(!function_exists('str2bytes')){
    function str2bytes($value) {
        // only string
        $unit_byte = preg_replace('/[^a-zA-Z]/', '', $value);
        $unit_byte = strtolower($unit_byte);
        // only number (allow decimal point)
        $num_val = (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        switch ($unit_byte) {
            case 'p':   // petabyte
            case 'P':
            case 'pb':
            case 'PB':
                $num_val *= 1024;
            case 't':   // terabyte
            case 'T':
            case 'tb':
            case 'TB':
                $num_val *= 1024;
            case 'g':   // gigabyte
            case 'G':
            case 'gb':
            case 'GB':
                $num_val *= 1024;
            case 'm':   // megabyte
            case 'M':
            case 'mb':
            case 'MB':
                $num_val *= 1024;
            case 'k':   // kilobyte
            case 'K':
            case 'kb':
            case 'KB':
                $num_val *= 1024;
            case 'b':   // byte
            case 'B':
            return $num_val *= 1;
                break; // make sure
            default:
                return FALSE;
        }
        return FALSE;
    }
}

if (! function_exists('isImage')) {
    function isImage($page){
        $params = array(
            'http' => array('method' => 'HEAD')
        );
        $ctx = stream_context_create($params);
        $fp = @fopen($page, 'rb', false, $ctx);
        
        if (!$fp) return false;  // Problem with url

        $meta = stream_get_meta_data($fp);
        if ($meta === false){
            fclose($fp);
            return false;  // Problem reading data from url
        }

        $wrapper_data = $meta["wrapper_data"];
        if(is_array($wrapper_data)){
          foreach(array_keys($wrapper_data) as $hh){
              if (substr($wrapper_data[$hh], 0, 19) == "Content-Type: image") // strlen("Content-Type: image") == 19 
              {
                fclose($fp);
                return true;
              }
          }
        }

        fclose($fp);
        return false;
    }
}

/**
 * Tests if an input is valid PHP serialized string.
 *
 * Checks if a string is serialized using quick string manipulation
 * to throw out obviously incorrect strings. Unserialize is then run
 * on the string to perform the final verification.
**/
if (!function_exists('is_serialized')) {
    function is_serialized( $value, &$result = null ) {
        // Bit of a give away this one
        if ( ! is_string( $value ) ) {
            return FALSE;
        }
        // Serialized FALSE, return TRUE. unserialize() returns FALSE on an
        // invalid string or it could return FALSE if the string is serialized
        // FALSE, eliminate that possibility.
        if ( $value === 'b:0;' ) {
            $result = FALSE;
            return TRUE;
        }
        $length = strlen($value);
        $end    = '';
        
        if ( isset( $value[0] ) ) {
            switch ($value[0]) {
                case 's':
                    if ( $value[$length - 2] !== '"' )
                        return FALSE;
                    
                case 'b':
                case 'i':
                case 'd':
                    // This looks odd but it is quicker than isset()ing
                    $end .= ';';
                case 'a':
                case 'O':
                    $end .= '}';
        
                    if ($value[1] !== ':')
                        return FALSE;
        
                    switch ($value[2]) {
                        case 0:
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                        case 5:
                        case 6:
                        case 7:
                        case 8:
                        case 9:
                        break;
        
                        default:
                            return FALSE;
                    }
                case 'N':
                    $end .= ';';
                
                    if ( $value[$length - 1] !== $end[0] )
                        return FALSE;
                break;
                
                default:
                    return FALSE;
            }
        }
        
        if ( ( $result = @unserialize($value) ) === FALSE ) {
            $result = null;
            return FALSE;
        }
        
        return TRUE;
    }
}

/**
 * Function to make clean slug
 * Source : https://stackoverflow.com/a/9535967
 */
function format_uri( $string, $separator = '-' ){
    $accents_regex	= '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
    $special_cases	= array( '&' => 'and', "'" => '');
    $string			= strtolower(trim( $string ));
    $string 		= str_replace( array_keys($special_cases), array_values( $special_cases), $string );
    $string	 		= preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
    $string 		= preg_replace("/[^a-z0-9]/u", "$separator", $string);
    $string 		= preg_replace("/[$separator]+/u", "$separator", $string);

	return $string;
}

if ( ! function_exists('slugify')) {
    // Slugify a string
    function slugify($string) {
        // Get an instance of $this
        $CI =& get_instance(); 

        $CI->load->helper('text');
        $CI->load->helper('url');

        // Replace unsupported characters (add your owns if necessary)
        $string = str_replace("'", '-', $string);
        $string = str_replace(".", '-', $string);
        $string = str_replace("²", '2', $string);

        // Slugify and return the string
        $string = url_title(convert_accented_characters($string), '-', true);
        // trim
        $string = trim($string, '-');
        // remove duplicate -
        $string = preg_replace('~-+~', '-', $string);

        return $string;
    }
}

if (!function_exists('add_month')) {
    function add_month($date_str, $months){
        $date = $date_str;

        // We extract the day of the month as $start_day
        $start_day = $date->format('j');

        // We add 1 month to the given date
        $date->modify("+{$months} month");

        // We extract the day of the month again so we can compare
        $end_day = $date->format('j');

        if ($start_day != $end_day)
        {
            // The day of the month isn't the same anymore, so we correct the date
            $date->modify('last day of last month');
        }

        return $date;
    }
}

// https://www.malasngoding.com/membuat-format-tanggal-indonesia-dengan-php/
if (!function_exists('tgl_indo')) {
    function tgl_indo($tanggal){
        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);
        
        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun
    
        return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    }
}
 
 

 

 

 

if (!function_exists('is_template')) {
    function is_template(){
        //$CI =& get_instance();
        //$referer = $_SERVER['HTTP_REFERER'] ?? null;

        //if($CI->session->userdata('user_role') < 3 && (strpos($referer, 'templates/editor'))){
            return TRUE;
        //}

        //return FALSE;
    }
}

if (!function_exists('generate_uuid')) {
    function generate_uuid() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}

if (!function_exists('isValidUuid')) {
    function isValidUuid( $uuid ) {
        
        if (!is_string($uuid) || (preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $uuid) !== 1)) {
            return false;
        }
        return true;
    }
}

if (!function_exists('generate_random_text')) {
    function generate_random_text($type = 'alnum', $length = 64) {
        switch ( $type ) {
            case 'alnum':
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'alpha':
                $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'hexdec':
                $pool = '0123456789abcdef';
                break;
            case 'numeric':
                $pool = '0123456789';
                break;
            case 'nozero':
                $pool = '123456789';
                break;
            case 'distinct':
                $pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
                break;
            default:
                $pool = (string) $type;
                break;
        }
    
    
        $crypto_rand_secure = function ( $min, $max ) {
            $range = $max - $min;
            if ( $range < 0 ) return $min; // not so random...
            $log    = log( $range, 2 );
            $bytes  = (int) ( $log / 8 ) + 1; // length in bytes
            $bits   = (int) $log + 1; // length in bits
            $filter = (int) ( 1 << $bits ) - 1; // set all lower bits to 1
            do {
                $rnd = hexdec( bin2hex( openssl_random_pseudo_bytes( $bytes ) ) );
                $rnd = $rnd & $filter; // discard irrelevant bits
            } while ( $rnd >= $range );
            return $min + $rnd;
        };
    
        $token = "";
        $max   = strlen( $pool );
        for ( $i = 0; $i < $length; $i++ ) {
            $token .= $pool[$crypto_rand_secure( 0, $max )];
        }
        return $token;
    }
}

if (!function_exists('style_generator')) {
    function style_generator( $jsonStyle ) {
        
        $styleOutput    = '<style id="editor-style">:root {';
        $fontArray      = array();
        $fontUrl        = null;

        $array = json_decode($jsonStyle);
        foreach($array as $component){
            foreach($component->settings as $key => $setting){
                if($key == 'textFontSize'){
                    $styleOutput .= "font-size:" . $setting->value . "px;";
                } else {
                    $styleOutput .= "--" . $key . ":" . $setting->value . ";";
                }

                if($key == 'headingFont' || $key == 'textFont' || $key == 'quoteFont'){
                    array_push($fontArray, $setting->value);
                }
            }
        }

        $styleOutput    .= '}</style>';
        $fontArray      = array_unique($fontArray);

        foreach($fontArray as $font){
            $font       = str_replace(" ", "+", $font);
            $fontUrl    .= $font . ":400,400i,700,700i|";
        }

        $fontUrl    = substr($fontUrl, 0, -1);
        $fontUrl    = '<link class="editor-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=' . $fontUrl .'&amp;display=swap">';
        return $styleOutput . $fontUrl;
    }
}


if (!function_exists('process_stock_image')) {
    function process_stock_image($json_data){
        //$CI =& get_instance();
        $builderFunction = new Builder_Function();
        $pattern    = '/(?:https\:\/\/images\.unsplash\.com\/photo\-)(?:[^\"]+)|(?:https\:\/\/images\.unsplash\.com\/flagged\/photo\-)(?:[^\"]+)/';
        
        $check_hash = preg_match_all($pattern, $json_data, $unsplashURI);
        foreach ($unsplashURI[0] as $uri){
            $parse_uri  = parse_url($uri);
            parse_str($parse_uri['query'], $query);
            $width      = $query['w'] ?? 'auto';
            $height     = $query['h'] ?? 'auto';
            $imgName    = basename($parse_uri['path']) . "_" . $width . "_" . $height . ".jpeg";
            //$imgURI     = modules::run('attachment/uploadFromUri', $uri, $imgName);
            $imgURI     = $builderFunction->uploadFromUri($uri, $imgName);
            // when upload success, imgURI is string, else array
            if(!is_array($imgURI)){
                $json_data  = str_replace($uri, $imgURI, $json_data);
            }
        }

        return $json_data;
    }
}

 

if (!function_exists('get_base_url')) {
    function get_base_url() {

        return $_ENV['APP_URL']."/";
        
        $base_url = get_option('base_url');
        
        if(!empty($base_url)){
            $protocol 	= (isset( $_SERVER['HTTPS'] ) AND $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
            $page       =  $protocol . $base_url . '/';
        } else {
            // $page        = getenv('APP_URL') ? getenv('APP_URL')."/" : 'https://ukm.digital/';
            $page        = 'https://ukm.digital/';
        }

        return $page;
    }
}

if (!function_exists('user_site_base_url')) {
    /**
     * get landing base url
     */
    function user_site_base_url() {
        
        $protocol 	= (isset( $_SERVER['HTTPS'] ) AND $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';

        global $default_request_uri; //this is from endpoint to load landingpage (...\modules\media_upload\src\Controller\LandingPageController.php)
        
        if (str_starts_with($default_request_uri, '/landingpage/')) {
          global $landing_home_slug;
          global $is_in_preview;
          if ($is_in_preview) {
            $path = "/preview/landingpage/$landing_home_slug/";
          }
          else{
            $path = "/landingpage/$landing_home_slug/";
          }
          $base_url = $protocol . $_SERVER['SERVER_NAME'] . $path;
        } 
        else{
          $base_url   =  $protocol . $_SERVER['SERVER_NAME'];
        }


        return $base_url;
    }
}

if (!function_exists('delete_cache')) {
    function delete_cache($uri) {
        $CI =& get_instance();
	    $path = $CI->config->item('cache_path');
        
        $cache_path = ($path == '') ? APPPATH.'cache/' : $path;
	    $cache_path .= md5($uri);
        
        return @unlink($cache_path);
    }
}

if (!function_exists('delete_site_cache')) {
    function delete_site_cache($site_uuid) {
        $CI =& get_instance();
        $path = $CI->config->item('cache_path');

        $site_data  = modules::run('pages/sites/getSitesByUUid', $site_uuid);
        $page_data  = modules::run('pages/getPageByWebsite', $site_data->website_id);
        $protocols  = array('https://','http://');
        
        foreach($protocols as $protocol){
            foreach($page_data as $page){
                $uri    = $protocol . $site_data->website_full . "/";
    
                if($page->page_type == 0){
                    $uri .= $page->website_pages_slug;
                }
    
                $cache_path = ($path == '') ? APPPATH.'cache/' : $path;
                $cache_path .= md5($uri);
    
                cache_log('delete - uri', $uri);
                cache_log('delete - path', $cache_path);
    
                @unlink($cache_path);
            }
        }
        
        return;
    }
}

if (!function_exists('create_log')) {
    function create_log($string) {
        $myfile = fopen("C:/Temp/log_x.txt", "a+") or die("Unable to open file!");
        $txt = $string."\n";
        fwrite($myfile, $txt);
        fclose($myfile);
    }
}

if (!function_exists('cache_log')) {
    function cache_log($title, $response){
        //Something to write to txt log
        $log  = "$title : ".$response.' - '.date("F j, Y, g:i a").PHP_EOL.
        "-------------------------".PHP_EOL;

        // check directory exist or not
        if( ! file_exists('./builder_application/logs/cache') ):
            if( ! mkdir('./builder_application/logs/cache', 0777, true) ):
                $failed = true; // flagging failed to crate new dir
            endif;
        endif;
        
        //Save string to log, use FILE_APPEND to append.
        file_put_contents('./builder_application/logs/cache/log_cache_'.date("j.n.Y").'.log', $log, FILE_APPEND);
    }
} 

   
 
 
 

if (!function_exists('get_image_thumbnail')) {
    function get_image_thumbnail($image_url, $thumnail_size) {

        if (!strpos($image_url, 'builder_uploads') || strpos($image_url, 'stock_image')){
            return $image_url;
        }

        $fileparts  = pathinfo($image_url);
        switch ($thumnail_size) {
            case 'medium':        
                $image_url = $fileparts['dirname'] . '/' . $fileparts['filename'] . '-600x600.' . $fileparts['extension'];
            break;

            case 'small':
                $image_url = $fileparts['dirname'] . '/' . $fileparts['filename'] . '-300x300.' . $fileparts['extension'];
            break;

            case 'tiny':
                $image_url = $fileparts['dirname'] . '/' . $fileparts['filename'] . '-60x60.' . $fileparts['extension'];
            break;

            case 'half':
                $image_url = $fileparts['dirname'] . '/' . $fileparts['filename'] . '-half.' . $fileparts['extension'];
            break;
        }

        return $image_url;
    }
}

if (!function_exists('convert_to_webp')) {
    function convert_to_webp($source) {
        $fileparts      = pathinfo($source);
        $extension      = strtolower($fileparts['extension']);
        $image_name     = $fileparts['filename'] . '.webp';
        $destination    = $fileparts['dirname'] . '/' . $image_name;
        
        if ($extension == 'jpeg' || $extension == 'jpg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($extension == 'gif') {
            $image = imagecreatefromgif($source);
        } elseif ($extension == 'png') {
            $image = imagecreatefrompng($source);
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        }
        imagewebp($image, $destination, 80);

        return $image_name;
    }
}

if (!function_exists('generate_array_svg')) {
    function generate_array_svg() {
        $svg_files = glob('assets/svg/*/*.svg');

        $fab_arr    = array();
        $fa_arr     = array();
        $fas_arr    = array();
        $output     = "result.php";

        file_put_contents($output, "");
        file_put_contents($output, "<?php" . PHP_EOL, FILE_APPEND);
        file_put_contents($output, "function font_awesome_array(){" . PHP_EOL, FILE_APPEND);

        foreach($svg_files as $file) {

            $array_directory    = explode('/', $file);
            $file_name          = basename($file, ".svg");

            switch($array_directory[2]){
                case 'brands':
                    $fab_arr['fa-'.$file_name] = file_get_contents($file);
                break;

                case 'solid' :
                    $fas_arr['fa-'.$file_name] = file_get_contents($file);
                break;

                case 'regular':
                    $fa_arr['fa-'.$file_name] = file_get_contents($file);
                break;
            }
        }

        $arr_output = array(
            'fab'   => $fab_arr,
            'fas'   => $fas_arr,
            'fa'    => $fa_arr 
        );

        file_put_contents($output, 'return ' . var_export($arr_output, true) . ';' . PHP_EOL, FILE_APPEND);
        file_put_contents($output, "}" . PHP_EOL, FILE_APPEND);
    }
}

if (!function_exists('get_font_awesome_svg')) {
    function get_font_awesome_svg($class_name = null, $classes = null) {
        //$CI =& get_instance();
        //$CI->load->helper('font_awesome_helper');

        $return_data  = null;

        if(!empty($class_name)){
            $class_arr          = explode(' ', $class_name);
            $font_awesome_arr   = font_awesome_array();
            $data_svg           = $font_awesome_arr[$class_arr[0]][$class_arr[1]];

            $return_data = "<i class='ukm-icon $classes'>$data_svg</i>";
        }

        return $return_data;
    }
}

if (!function_exists('minify_css')) {
    function minify_css($css){
        $css = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $css); // negative look ahead
        $css = preg_replace('/\s{2,}/', ' ', $css);
        $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
        $css = preg_replace('/;}/', '}', $css);
        return $css;
    }
}

if (!function_exists('oy_url')) {
    function oy_url(){
        $oy_server_type = get_option('oy_api_type');

        if($oy_server_type == 'production'){
            return 'https://partner.oyindonesia.com/';
        } else {
            return 'https://api-stg.oyindonesia.com/';
        }
    }
} 
/* End of file builder_helpers.php */
/* Location: ./application/helpers/builder_helpers.php */


function format_heading( $text ) {
    $text = str_replace("<p>", '<span class="nl">', $text);
    $text = str_replace("</p>", "</span>", $text);
    return strip_tags($text, '<a><em><strong><b><i><span>');
}

function get_value( $data, $key, $default ) {
    if ( isset( $data->$key ) ) return $data->$key->value;
    return $default;
}

if (!function_exists("render_button")) {
  function render_button($opts, $attrs, $class = null) { 
  if ( isset( $opts->enable ) && $opts->enable == false ) return;
  $js_function = null;
  $additional_argument = null;
  if ( !empty( $opts->fb_event ) ):
    if ( $opts->fb_event == 'Purchase' ):
        $additional_argument = ", value: {$opts->fb_value},
        currency: '{$opts->fb_currency}'";
    endif;
    $js_function = " onClick=\"fbq('track', '{$opts->fb_event}', {content_name: '{$opts->fb_content_name}', 
        content_ids: ['{$opts->fb_content_ids}']
        {$additional_argument} });\" ";
  endif;
    ?>
    <a
      href="<?php echo $opts->url; ?>"
      target="<?php echo $opts->new_window ? '_blank' : ''; ?>"
      class="button <?php echo $opts->size; ?> <?php echo $opts->style; ?> <?php echo $opts->corner; ?> <?php echo $class; ?>"
      style="--background: <?php echo $opts->background;  ?>;--foreground: <?php echo $opts->color; ?>; white-space: normal;"
      <?php echo $js_function; ?>
      <?php echo $attrs; ?>
      >
      <?php if( !empty($opts->icon)  && $opts->icon_position== 'left' ): ?>
        <span class="icon"><?php echo get_font_awesome_svg($opts->icon); ?></span>
      <?php endif; ?>
      <span><?php echo $opts->label;  ?></span>
      <?php  if( !empty($opts->icon)  && $opts->icon_position== 'right' ): ?>
        <span class="icon"><?php echo get_font_awesome_svg($opts->icon); ?></span>
      <?php endif; ?>
    </a>
  <?php 
  }
}

if (!function_exists("render_categories")) {
    function render_categories($post_categories, $category, $current_slug) { 
        if(!empty($post_categories)):
            if(!empty($category)):
            ?>
              <!-- <button class="button is-primary is-rounded"></button> -->
              <a href="<?php echo base_url() . $current_slug ?>" class="button is-primary is-rounded mb1">
                <?php echo $post_categories->content_category_title; ?>
                <span class="icon is-small" style="margin-left:10px;">
                    <?php echo get_font_awesome_svg("fas fa-times"); ?>
                </span>
              </a>
            <?php
            else:
              foreach($post_categories as $post_category):
              ?>
                <a href="<?php echo base_url() . $current_slug . '?category=' . $post_category->content_category_slug;?>" class="button is-primary is-rounded"><?php echo $post_category->content_category_title; ?></a>
              <?php
              endforeach;
            endif;
        endif;
    }
}

if (!function_exists("render_search_box")) {
    function render_search_box() { 
            ?>
              <form action="" class="my2">
                <input class="input" type="text" name="query" placeholder="Cari postingan" />
              </form>
            <?php
    }
}