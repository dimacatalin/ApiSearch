<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_success(): void
    {
        $this->registerUser();
    }

    public function test_login_success(): void
    {
        $this->registerAndLoginUser();
    }

    public function test_logout_success(): void
    {
        $token = $this->registerAndLoginUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->post($this->apiBaseUrl . 'logout');

        $response->assertStatus(302);
    }


    public function test_get_user_success(): void
    {
        $token = $this->registerAndLoginUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->get($this->apiBaseUrl . 'user');

        $response->assertStatus(200);

        $response = json_decode($response->content());

        $this->assertEquals($response->email, $this->email);
        $this->assertEquals($response->name, $this->name);
    }

    /**
     * @return string[]
     */
}
