<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use App\User;


class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index(){
//        $users = User::all();

        $users = User::where('name','qcc')->get();

        foreach ($users as $item){
            echo $item->name . "\n";
        }

        dd($users);
    }

    public function chunk(){

        User::chunk(2,function($users){
            foreach ($users as $user){
                echo 'name:' . $user->name . "\n";
            }
        });

    }


    public function insert()
    {

//        DB::insert('insert into users (id, name, email, password) values (?, ?, ? , ? )',
//            [1, 'Laravel','laravel@test.com','123']);
//        DB::insert('insert into users (id, name, email, password) values (?, ?, ?, ? )',
//            [2, 'Academy','academy@test.com','123']);

        DB::insert('insert into users (id, name, email, password) values(?, ?, ?, ?)',[3,'qcc','qcc@test.com','123']);
    }

    public function update(){

        DB::update('update users set password = "123456" where name = ?', ['qcc']);

    }

    public function delete()
    {
        DB::delete('delete from users where name = ? ',['qcc']);
    }

    public function transaction(){
        DB::transaction(function(){

            DB::insert('insert into users (id, name, email, password) values(?, ?, ?, ?)',[4,'kfc','kfc@test.com','123']);
            DB::update('update users set password = "456" where name = ?', ['qcc']);
        });
    }

    /**
     * save可以定义赋值在guarded字段中的值
     */
    public function save(){
        $user = new User();
        $user->name = 'bbc';
        $user->email = 'bbc@test.com';
        $user->password = '456';

        if($user->save()){
            echo "添加成功";
        }else{
            echo "添加失败";
        }

    }

    public function create(){

        $input = ['name' => 'cnn','email' => 'cnn@test.com', 'password' => '456'];

        User::create($input);
    }


    public function trash(){

        $user = User::where('name','qcc')->get();
        $user->delete();
        if($user->trashed()){
            echo '删除成功';
            dd($user);
        }else{
            echo '删除失败';
        }



    }


    public function hasOne(){

//        $obj = User::find(1)->account();
        $obj = User::where('id', 1)->first()->account;
        dd($obj);
    }

    public function write(){

        $this->authorize('write');

        echo "write access";

    }




}