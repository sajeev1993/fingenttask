<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i=1; $i < 4; $i++) {
            \DB::table('users')->insert([
                [
                    'name' => 'Teacher ' . $i,
                    'email' => 'teacher' . $i . '@test.com',
                    'password' => bcrypt('password'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            ]);
        }
    }
}
