<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use LaravelWOL\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'admin',
            'email' => 'test@example.com',
            'password' => Hash::make('secret')
        ]);
    }
}
