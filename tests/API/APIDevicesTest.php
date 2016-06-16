<?php

class APIDevicesTest extends TestCase
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
    public function canSeeDeviceList()
    {
        $this->get('http://localhost/api/devices', ['Authorization' => 'Bearer: '.$this->token])
            ->seeStatusCode(200)
            ->seeJson(['name' => 'WOPR', 'ip' => '127.0.0.1', 'mac' => '00-14-22-01-23-45']);
    }

    /**
     * @test
     */
    public function canCreateDevice()
    {
        $this->post('/api/devices', ['name' => 'HAL', 'ip' => '192.168.0.100', 'mac' => 'AA-BB-CC-DD-00-11'],
                                  ['Authorization' => 'Bearer: '.$this->token])
            ->seeStatusCode(200);
        $this->seeInDatabase('devices', ['name' => 'HAL', 'ip' => '192.168.0.100', 'mac' => 'AA-BB-CC-DD-00-11']);
    }

    /**
     * @test
     */
    public function canUpdateDevice()
    {
        $this->put('/api/devices/1', ['name' => 'Watson', 'ip' => '192.168.0.105', 'mac' => 'CC-BB-AA-DD-00-11'],
            ['Authorization' => 'Bearer: '.$this->token])->seeStatusCode(200);
        $this->seeInDatabase('devices', ['name' => 'Watson', 'ip' => '192.168.0.105', 'mac' => 'CC-BB-AA-DD-00-11']);
    }

    /**
     * @test
     */
    public function canBootDevice()
    {
        $this->post('/api/devices/boot', ['mac' => 'CC-BB-AA-DD-00-11', 'ip' => '192.168.0.105'],
            ['Authorization' => 'Bearer: '.$this->token])->seeStatusCode(200);

    }
}
