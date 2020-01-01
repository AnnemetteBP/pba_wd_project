<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        User::create([
            'name' => 'SuperRootAdmin',
            'email' => 'ampirchert@gmail.com',
            'password' => Hash::make('kodeord'),
            'api_token' => Str::random(80),
        ]);
    }
}
