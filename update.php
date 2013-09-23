<?php

$running = true;
function rb_agency_notify_installation(){

    include_once(ABSPATH . 'wp-includes/pluggable.php');        
    $json_url = 'http://agency.rbplugin.com/rb-license-checklist/';
    
    $client_domain = network_site_url('/');
    $client_sitename = get_bloginfo( 'name' );
    $client_admin_email = get_bloginfo('admin_email');
    $client_plugin_version = get_option('rb_agency_version');
    
     
    $data = array(
    "client_domain" => $client_domain,
    "client_admin_email"  => $client_admin_email,
    "client_sitename" =>$client_sitename,
    "client_plugin_version" => $client_plugin_version,
    "client_plugin_name" =>"RB Plugin");                                                                    
    $data_string = json_encode($data);
        if(function_exists("rb_agencyinteract_install")){
            $client_interact_exist = get_option('rb_agency_version');   
            array_push($data,array("client_interact_exist" => $client_interact_exist)); 
        }
        
    // Initializing curl
    $ch = curl_init( $json_url );
     
    // Configuring curl options
    $options = array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
    CURLOPT_POSTFIELDS => $data_string
    );
     
    // Setting curl options
    curl_setopt_array( $ch, $options );
     
    // Getting results
    $result =  curl_exec($ch); // Getting jSON result string
    $isReported = get_option("rb_agency_notify");   
         
    if($result){
    
        $message .= "RB Plugin was installed in the server that is not a member of or registered to the list of clients.". "\r\n\r\n";
        $message .= sprintf('Domain: %s',$client_domain). "\r\n\r\n";  
        $message .= sprintf('Date: %s',date('l jS \of F Y h:i:s A')) . "\r\n\r\n";  
        $message .= sprintf('Admin Email: %s', get_option('admin_email')) . "\r\n";  
        
        $headers = array();
        $headers[] = 'Cc: Rob <rob@clearlym.com>';
        $headers[] = 'Cc: Operations <operations@clearlym.com>'; // note you can just use a simple email address           
        
    //  wp_mail("champ.kazban25@gmail.com", sprintf('RB Plugin Installed - Unknown Server/Domain[%s]', get_option('blogname')), $message,$headers);
    }           
}



class RBAgency_Update {

    public static function check_update($option, $cache=true){

        $version_info = self::get_version_info($cache);

        if (!$version_info)
            return $option;

        $plugin_path = "gravityforms/gravityforms.php";
        if(empty($option->response[$plugin_path]))
            $option->response[$plugin_path] = new stdClass();

        //Empty response means that the key is invalid. Do not queue for upgrade
        if(!$version_info["is_valid_key"] || version_compare(GFCommon::$version, $version_info["version"], '>=')){
            unset($option->response[$plugin_path]);
        }
        else{
            $option->response[$plugin_path]->url = "http://www.gravityforms.com";
            $option->response[$plugin_path]->slug = "gravityforms";
            $option->response[$plugin_path]->package = str_replace("{KEY}", GFCommon::get_key(), $version_info["url"]);
            $option->response[$plugin_path]->new_version = $version_info["version"];
            $option->response[$plugin_path]->id = "0";
        }

        return $option;

    }


    public static function get_version_info($cache=true){

        $raw_response = get_transient("gform_update_info");
        if(!$cache)
            $raw_response = null;

        if(!$raw_response){
            //Getting version number
            $options = array('method' => 'POST', 'timeout' => 20);
            $options['headers'] = array(
                'Content-Type' => 'application/x-www-form-urlencoded; charset=' . get_option('blog_charset'),
                'User-Agent' => 'WordPress/' . get_bloginfo("version"),
                'Referer' => get_bloginfo("url")
            );
            $request_url = GRAVITY_MANAGER_URL . "/version.php?" . self::get_remote_request_params();
            $raw_response = wp_remote_request($request_url, $options);

            //caching responses.
            set_transient("gform_update_info", $raw_response, 86400); //caching for 24 hours
        }

         if ( is_wp_error( $raw_response ) || 200 != $raw_response['response']['code'])
            return array("is_valid_key" => "1", "version" => "", "url" => "");
         else
         {
             $ary = explode("||", $raw_response['body']);
             $info = array("is_valid_key" => $ary[0], "version" => $ary[1], "url" => $ary[2]);
             if(count($ary) == 4)
                $info["expiration_time"] = $ary[3];

             return $info;
         }

    }


    //Displays current version details on Plugin's page
    public static function display_changelog(){
        if($_REQUEST["plugin"] != "gravityforms")
            return;

        $page_text = self::get_changelog();
        echo $page_text;

        exit;
    }


    public static function get_changelog(){
        $key = GFCommon::get_key();
        $body = "key=$key";
        $options = array('method' => 'POST', 'timeout' => 3, 'body' => $body);
        $options['headers'] = array(
            'Content-Type' => 'application/x-www-form-urlencoded; charset=' . get_option('blog_charset'),
            'Content-Length' => strlen($body),
            'User-Agent' => 'WordPress/' . get_bloginfo("version"),
            'Referer' => get_bloginfo("url")
        );

        $raw_response = wp_remote_request(GRAVITY_MANAGER_URL . "/changelog.php?" . GFCommon::get_remote_request_params(), $options);

        if ( is_wp_error( $raw_response ) || 200 != $raw_response['response']['code']){
            $page_text = __("Oops!! Something went wrong.<br/>Please try again or <a href='http://www.gravityforms.com'>contact us</a>.", 'gravityforms');
        }
        else{
            $page_text = $raw_response['body'];
            if(substr($page_text, 0, 10) != "<!--GFM-->")
                $page_text = "";
        }
        return stripslashes($page_text);
    }


}
?>