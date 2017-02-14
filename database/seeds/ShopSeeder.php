<?php

use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('shops')->insert([['shopname' => 's1'],
            ['shopname' => 's2'],
            ['shopname' => 's3']
        ]);
    }
}
