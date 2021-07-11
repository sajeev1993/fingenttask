<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TermsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 3; $i++) {
            \DB::table('terms')->insert([
                [
                    'name' => 'Term ' . $i,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            ]);
        }
    }
}
