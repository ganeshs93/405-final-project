<?php

namespace App\Services;

use App\Services\OAuthToken;
use Config;

require_once('OAuth.php');

class InstagramAPI
{
    public function buildLocationSearchURL($latitude, $longitude)
    {
        return Config::get('instagram.api_url') . 'locations/search?lat=' . urlencode($latitude) . '&lng=' . urlencode($longitude) . '&access_token=' . Config::get('instagram.access_code');
        
    }
    
    public function buildLocationMediaURL($id)
    {
        return Config::get('instagram.api_url') . 'locations/' . urlencode($id) . '/media/recent?access_token=' . Config::get('instagram.access_code');
    }
    
    public function buildUserSearchURL($username)
    {
         return Config::get('instagram.api_url') . 'users/search?q=' . urlencode($username) . '&access_token=' . Config::get('instagram.access_code');   
    }
    
    public function buildUserMediaURL($id)
    {
        return Config::get('instagram.api_url') . 'users/' . urlencode($id) . '/media/recent?access_token=' . Config::get('instagram.access_code'); 
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
            $data = json_decode($json_string);
            return $data;
        }
        else
        {
            return null;
        }    
    }
}