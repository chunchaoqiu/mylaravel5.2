<?php

use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

//        DB::table('jobs')->insert(['jobName' => 'job' . rand(1, 10)]);//手动创建

        factory('App\Models\Job', 50)->create();//利用模型工厂进行创建
    }
}







