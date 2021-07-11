<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SubjectsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 7; $i++) {
            \DB::table('subjects')->insert([
                [
                    'name' => 'Subject ' . $i,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            ]);
        }
    }
}
