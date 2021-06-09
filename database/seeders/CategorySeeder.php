<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('categories')->insert([
            'name' => 'Sayuran'
        ]);

        \DB::table('categories')->insert([
            'name' => 'Bumbu'
        ]);

        \DB::table('categories')->insert([
            'name' => 'Lauk Pauk'
        ]);

        \DB::table('categories')->insert([
            'name' => 'Frozen Food'
        ]);

        \DB::table('categories')->insert([
            'name' => 'Buah'
        ]);
    }
}
