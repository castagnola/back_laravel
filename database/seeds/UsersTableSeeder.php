<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
                'name' => 'Admin',
                'email' => 'admin@m.com',
                'password' => Hash::make['123456'],
                'photo' => 'user.png',
                'role_id' => 1,
                'status' => 1
            ]
        );
    }
}
