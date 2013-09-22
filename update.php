<?php
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

}
?>