<?php

namespace App\Services;

use App\Services\OAuthToken;
use Config;

require_once('OAuth.php');

class UberAPI
{
    public function buildProductsURL($latitude, $longitude)
    {
        return Config::get('uber.products_url') . '?server_token=' . Config::get('uber.server_token') . '&latitude=' . urlencode($latitude) . '&longitude=' . urlencode($longitude);
        
    }
    
    public function getHTTPResponseCode($url)
    {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }
    
    public function getJSON($url)
    {
        if ($this->getHTTPResponseCode($url) == '200')
        {
            $json_string = file_get_contents($url);
            $uber_data = json_decode($json_string);
            return $uber_data;
        }
        else
        {
            return null;
        }    
    }
}