<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function canLogin()
    {
        $token = JWTAuth::attempt(['email' => 'test@example.com', 'password' => 'secret']);
        $this->assertNotFalse($token);
    }

    /**
     * @test
     */
    public function failsWithInvalidCredentials()
    {
        $token = JWTAuth::attempt(['email' => 'hacker@example.com', 'password' => 'pwned']);
        $this->assertFalse($token);
    }

    /**
     * @test
     */
    public function canGetUserFromToken()
    {
        $token = $this->login('test@example.com', 'secret');
        $user = JWTAuth::toUser($token);
        $this->assertInstanceOf(LaravelWOL\User::class, $user);
        $this->assertEquals('admin', $user->username);
        $this->assertEquals('test@example.com', $user->email);
    }
}
