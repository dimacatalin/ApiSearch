<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\RolesTableSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesTableSeeder::class);
    }

    protected $apiBaseUrl = 'api/';
    protected $email = 'email@test.com';
    protected $name = 'name';
    protected $password = 'password';

    public function registerUser(): array
    {
        $response = $this->post($this->apiBaseUrl . 'register', [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'device_name' => 'testing'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $this->email,
            'name' => $this->name
        ]);

        $response->assertStatus(200);

        $user = User::where('email', $this->email)->first();
        $user->assignRole(User::USER_ROLE_ADMIN);

        return [
            'email' => $this->email,
            'password' => $this->password
        ];
    }

    protected function registerAndLoginUser()
    {
        $credentials = $this->registerUser();

        $response = $this->post($this->apiBaseUrl . 'login', [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'device_name' => 'testing'
        ]);

        $response->assertStatus(200);

        $response = json_decode($response->content());

        return $response->token;
    }
}
