<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_user_register()
    {
        $user =
            $response = $this->post('/register', [
                'name' => 'hello',
                'email' => 'hello@hello123.com',
                'password' => 'password',
                'password_confirmation' => 'password'
            ]);
        $this->assertAuthenticated();

        $response->assertRedirect('/dashboard');
        $response->assertStatus(302);
    }
}
