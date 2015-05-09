<?php

class YelpResultsControllerTest extends TestCase
{
    public function test_search_with_no_location_causes_redirect_with_error_message()
    {
        \Session::start();
        $response = $this->call('GET', '/results?term=food&location=');
        $error_message = \Session::get('error_message');
        $this->assertTrue($response->isRedirection());
        $this->assertSessionHas('error_message', 'Location is required');
    }
    
    public function test_search_with_no_term_but_with_location_is_succesful()
    {
        $response = $this->call('GET', '/results?term=&location=Los+Angeles%2C+CA');
        $view = $response->getOriginalContent();
        $this->assertTrue($response->isOK());
    }
}