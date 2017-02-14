<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //判断该用户是否具有写日志权限
    public function write(User $user){
        if($user->name == 'asd'){
            return true;
        }
    }

}
