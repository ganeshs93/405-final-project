<?php

class UserControllerTest extends TestCase
{
    public function test_login_page_displays_if_user_not_logged_in()
    {
        $response = $this->call('GET', '/login');
        $this->assertFalse($response->isRedirection());
    }
    
    public function test_going_to_login_page_redirects_if_user_logged_in()
    {
        $user = new App\User(['name' => 'John']);
        $this->be($user);
        $response = $this->call('GET', '/login');
        $this->assertTrue($response->isRedirection());
    }
}