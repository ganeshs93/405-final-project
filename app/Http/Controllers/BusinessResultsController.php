<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\YelpAPI;
use App\Services\UberAPI;
use App\Services\InstagramAPI;
use App\Models\Businesses;
use App\Models\Suggestions;
use Auth;
use Session;

class BusinessResultsController extends Controller
{
    public function displayBusinessDetails($business_id)
    {
        $data = $this->getBusinessDataFromYelp($business_id);
        $user_logged_in = Auth::user();
        $success_message = Session::get('success_message');
        $error_message = Session::get('error_message');
        
        $locu_url = 'https://api.locu.com/v1_0/venue/search/?locality=' . rawurlencode($data->location->city) . '&region=' . rawurlencode($data->location->state_code) . '&postal_code=' . rawurlencode($data->location->postal_code) .'&street_address=' . rawurlencode(implode(' ', $data->location->address)) . '&api_key=c141c64e3e6fe7d1d68cfee37d0fde3774f99d2f';
        $locu_data = json_decode(file_get_contents($locu_url));
        if (count($locu_data->objects) > 0)
        {
            $locu_id = $locu_data->objects[0]->id;
            $menu_url = 'https://widget.locu.com/menuwidget/locu.widget.developer.v2.0.js?venue-id=' . $locu_id . '&script-id=-locu-widget&widget-key=913bdbb9bcf5dd628dfe86716c86fe7c721cc4f6';
        }
        else
        {
            $menu_url = 'https://widget.locu.com/menuwidget/locu.widget.developer.v2.0.js?venue-id=&script-id=-locu-widget&widget-key=913bdbb9bcf5dd628dfe86716c86fe7c721cc4f6';
        }
        $uber_data = $this->getUberData($data);
        $instagram_location_data = $this->getInstgramImagesFromYelpData($data);
        $instagram_id = Businesses::where('id', '=', $data->id)->first();
        if ($instagram_id)
        {
            $instagram_user_data = $this->getInstagramMediaFromUserID($instagram_id->instagram_id);
        }
        else
        {
            $instagram_user_data = null;
        }
        //dd($instagram_user_data);
        
        return view('business_info', [
            'currentUser' => $user_logged_in,
            'success_message' => $success_message,
            'error_message' => $error_message,
            'business' => $data,
            'menu_url' => $menu_url,
            'uber' => $uber_data,
            'instagram_location' => $instagram_location_data,
            'instagram_user' => $instagram_user_data
        ]);
    }
    
    public function addUsernameSuggestion($business_id, Request $request)
    {
        $validator = Validator::make($request->all(), ['username' => 'required']);
        if ($validator->passes())
        {
            $user_logged_in = Auth::user();
            if ($user_logged_in)
            {
                $check_suggestion = Suggestions::where('user_id', '=', $user_logged_in->id)
                                ->where('business_id', '=', $business_id)
                                ->where('instagram_username', '=', $request->username)
                                ->first();
                if ($check_suggestion)
                {
                    return redirect("/business/$business_id")->with('error_message', 'You already suggested this username for this restaurant');
                }
                $suggestion = new Suggestions();
                $suggestion->business_id = $business_id;
                $suggestion->instagram_username = $request->username;
                $suggestion->user_id = $user_logged_in->id;
                $suggestion->save();
                return redirect("/business/$business_id")->with('success_message', 'Successfully Suggested Username');
            }
            return redirect("/business/$business_id")->with('error_message', 'You were logged out');
        }
        return redirect("/business/$business_id")->withInput()->withErrors($validator->errors());
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
    
    public function getInstgramImagesFromYelpData($yelp_business_data)
    {
        $all_ids = $this->getInstagramLocationID($yelp_business_data);
        if (property_exists($all_ids, 'data') && count($all_ids->data) > 0)
        {
            $images = array();
            foreach ($all_ids->data as $info)
            {
                if(levenshtein ($info->name, $yelp_business_data->name) < 5)
                {
                    $media = $this->getInstagramMediaFromLocationID($info->id);
                    $images[] = $media;
                }
            }
            return $images;
        }
        return null;   
    }
    
    public function getInstagramLocationID($yelp_business_data)
    {
        if(property_exists($yelp_business_data->location, 'coordinate'))
        {
            $api = new InstagramAPI();
            $url = $api->buildLocationSearchURL($yelp_business_data->location->coordinate->latitude, $yelp_business_data->location->coordinate->longitude);
            return $api->getJSON($url);
        }
        return null;
    }
    
    public function getInstagramMediaFromLocationID($location_id)
    {
        $api = new InstagramAPI();
        $url = $api->buildLocationMediaURL($location_id);
        return $api->getJSON($url);
    }
    
    public function getInstagramMediaFromUserID($user_id)
    {
        $api = new InstagramAPI();
        $url = $api->buildUserMediaURL($user_id);
        return $api->getJSON($url);
    }
}