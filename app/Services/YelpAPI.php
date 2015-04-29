<?php

namespace App\Services;

use App\Services\OAuthToken;
use Config;

require_once('OAuth.php');

class YelpAPI
{
    public function buildSearchURL($term, $location, $category, $deal, $offset)
    {
        $url = Config::get('yelp.search_url');
        $url_params = array();
        if ($term)
        {
            $url_params['term'] = $term;
        }
        if ($location)
        {
            $url_params['location'] = $location;
        }
        if ($category)
        {
            $url_params['category_filter'] = $category;
        }
        if ($deal and $deal == true)
        {
            $url_params['deals_filter'] = true;
        }
        if ($offset)
        {
            $url_params['offset'] = $offset;    
        }
        $url .= '?' . http_build_query($url_params);
        return $url;
    }
    
    public function buildBusinessURL($businessID)
    {
        $url = Config::get('yelp.business_url');
        if ($businessID)
        {
            $url .= '/' . urlencode($businessID);
        }
        return $url;
    }
    
    public function oAuthRequest($unsigned_url)
    {
        $token = new OAuthToken(Config::get('yelp.token'), Config::get('yelp.token_secret'));
        // Consumer object built using the OAuth library
        $consumer = new OAuthConsumer(Config::get('yelp.consumer_key'), Config::get('yelp.consumer_secret'));
        // Yelp uses HMAC SHA1 encoding
        $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
        $oauthrequest = OAuthRequest::from_consumer_and_token(
            $consumer, 
            $token, 
            'GET', 
            $unsigned_url
        );
    
        // Sign the request
        $oauthrequest->sign_request($signature_method, $consumer, $token);
    
        // Get the signed URL
        return $oauthrequest->to_url();
    }
    
    public function getHTTPResponseCode($url)
    {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }
    
    public function getJSON($url)
    {
        $signed_url = $this->oAuthRequest($url);
        if ($this->getHTTPResponseCode($signed_url) == '200')
        {
            $json_string = file_get_contents($signed_url);
            $yelp_data = json_decode($json_string);
            return $yelp_data;
        }
        else
        {
            return null;
        }
    }
}