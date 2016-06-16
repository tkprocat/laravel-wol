<?php

use Illuminate\Database\Seeder;
use LaravelWOL\Device;

class DevicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('devices')->truncate();

        Device::create([
            'name' => 'WOPR',
            'ip' => '127.0.0.1',
            'mac' => '00-14-22-01-23-45',
        ]);
    }
}
