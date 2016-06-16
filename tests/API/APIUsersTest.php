<?php

class APIUsersTest extends TestCase
{

    private $token;

    public function setUp()
    {
        parent::setUp();

        $this->token = $this->login('test@example.com', 'secret');
    }

    /**
     * @test
     */
    public function canSeeUsersList()
    {

        $this->get('http://localhost/api/users', ['Authorization' => 'Bearer: '.$this->token])
            ->seeStatusCode(200)
            ->seeJson(['username' => 'admin']);
    }

    /**
     * @test
     */
    public function canCreateUser()
    {
        $this->post('/api/users', ['username' => 'test_user', 'email' => 'test_user@example.com',
                                   'password' => 'secret123', 'password_confirmation' => 'secret123'],
                                  ['Authorization' => 'Bearer: '.$this->token])
            ->seeStatusCode(200);
        $this->seeInDatabase('users', ['username' => 'test_user', 'email' => 'test_user@example.com']);
    }

    /**
     * @test
     */
    public function canUpdateUser()
    {
        $this->put('/api/users/1', ['username' => 'super_admin', 'email' => 'admin@example.com',
                                    'password' => 'secret123', 'password_confirmation' => 'secret123'],
            ['Authorization' => 'Bearer: '.$this->token])->seeStatusCode(200);
        $this->seeInDatabase('users', ['username' => 'super_admin', 'email' => 'admin@example.com']);
    }

    /**
     * @test
     */
    public function canChangePassword()
    {
        $this->put('/api/users/1/changePassword', ['old_password' => 'secret', 'password' => 'secret456', 'password_confirmation' => 'secret456'],
            ['Authorization' => 'Bearer: '.$this->token])->seeStatusCode(200);
        //Check it's actually updated.
        $user = LaravelWOL\User::find(1);
        $this->assertTrue(\Hash::check('secret456', $user->password));
    }
}
