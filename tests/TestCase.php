<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    use DatabaseMigrations;
    
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }


    /**
     * Adds test data to the database.
     */
    public function setUp()
    {
        parent::setUp();
        $this->artisan('db:seed');
    }


    /**
     * Log in user and return JWT token.
     *
     * @return JWT token
     */
    public function login($email, $password)
    {
        return JWTAuth::attempt(['email' => $email, 'password' => $password]);
    }
}
