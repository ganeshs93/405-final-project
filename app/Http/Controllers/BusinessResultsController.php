<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\YelpAPI;
use App\Services\UberAPI;
use Auth;

class BusinessResultsController extends Controller
{
    public function displayBusinessDetails($business_id)
    {
        $data = $this->getBusinessDataFromYelp($business_id);
        $user_logged_in = Auth::user();
        
        $locu_url = 'https://api.locu.com/v1_0/venue/search/?locality=' . rawurlencode($data->location->city) . '&region=' . rawurlencode($data->location->state_code) . '&postal_code=' . rawurlencode($data->location->postal_code) .'&street_address=' . rawurlencode(implode(' ', $data->location->address)) . '&api_key=c141c64e3e6fe7d1d68cfee37d0fde3774f99d2f';
        $locu_data = json_decode(file_get_contents($locu_url));
        $locu_id = $locu_data->objects[0]->id;
        $menu_url = 'https://widget.locu.com/menuwidget/locu.widget.developer.v2.0.js?venue-id=' . $locu_id . '&script-id=-locu-widget&widget-key=913bdbb9bcf5dd628dfe86716c86fe7c721cc4f6';
        
        $uber_data = $this->getUberData($data);
        
        //$instagram = file_get_contents('https://api.instagram.com/v1/locations/search?lat=34.090488&lng=-118.367165&access_token=18d3e66a27794277be584c98feaa8b8c');
        
        //dd($instagram);
        
        return view('business_info', [
            'currentUser' => $user_logged_in,
            'business' => $data,
            'menu_url' => $menu_url,
            'uber' => $uber_data
        ]);
    }
    
    public function getBusinessDataFromYelp($business_id)
    {
        $api = new YelpAPI();
        $unsigned_url = $api->buildBusinessURL($business_id);
        return $api->getJSON($unsigned_url);
    }
    
    public function getUberData($yelp_business_data)
    {
        if(property_exists($yelp_business_data->location, 'coordinate'))
        {
            $api = new UberAPI();
            $url = $api->buildProductsURL($yelp_business_data->location->coordinate->latitude, $yelp_business_data->location->coordinate->longitude);
            return $api->getJSON($url);
        }
        else
        {
            return null;
        }
    }
}