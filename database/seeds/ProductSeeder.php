<?php

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('products')->insert([['productname' => 'p1'],
            ['productname' => 'p1'],
            ['productname' => 'p1']
        ]);

    }
}
