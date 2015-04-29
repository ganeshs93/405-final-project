<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\YelpAPI;
use Auth;
use Config;

class YelpResultsController extends Controller
{
    public function displaySearchResults(Request $request)
    {
        $data = $this->getYelpDataFromSearchTerms($request->term, $request->location, $request->category_filter, $request->deals_filter, $request->offset);
        if ($data)
        {
            $businesses = $data->businesses;
        }
        else
        {
            $businesses = null;
        }
        $user_logged_in = Auth::user();
        $deals_toggled = $this->getDealsToggledURLAndLinkTitle($request->term, $request->location, $request->category_filter, $request->deals_filter, $request->offset);
        return view('search_results', [
            'businesses' => $businesses,
            'currentUser' => $user_logged_in,
            'urlIfDealsToggled' => $deals_toggled[0],
            'linkTitleIfDealsToggled' => $deals_toggled[1]
        ]);
    }
    
    public function getYelpDataFromSearchTerms($term, $location, $category, $deals, $offset)
    {
        $api = new YelpAPI();
        $unsigned_url = $api->buildSearchURL($term, $location, $category, $deals, $offset);
        return $api->getJSON($unsigned_url);
    }
    
    public function sortBusinessesByRatingandReviewCount($array)
    {
        usort($array, array($this, 'compareBusinessRatingsDescending'));
        return $array;
    }
    
    public function compareBusinessRatingsDescending($a, $b)
    {
        if($b->rating == $a->rating)
        {
            return $b->review_count - $a->review_count;   
        }
        return $b->rating - $a->rating;
    }
    
    public function getDealsToggledURLAndLinkTitle($term, $location, $category, $deals, $offset)
    {
        $api = new YelpAPI();
        if ($deals)
        {
            $deals_toggled_url = $api->buildSearchURL($term, $location, $category, false, $offset);
            $deals_toggled_url = '/results' . substr($deals_toggled_url, strlen(Config::get('yelp.search_url')));
            $deals_toggled_link = 'View All Businesses';
        }
        else
        {
            $deals_toggled_url = $api->buildSearchURL($term, $location, $category, true, $offset);
            $deals_toggled_url = '/results' . substr($deals_toggled_url, strlen(Config::get('yelp.search_url')));
            $deals_toggled_link = 'Only View Businesses with Deals';
        }
        return array($deals_toggled_url, $deals_toggled_link);
    }
}