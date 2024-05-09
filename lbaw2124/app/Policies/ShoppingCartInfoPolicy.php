<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\ShoppingCartInfo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class ShoppingCartInfoPolicy
{
    use HandlesAuthorization;


    public function before(User $user, $ability)
    {
        if (!is_null(Admin::find($user->id)))
            return true;

    }

    public function viewAny(User $user){
        //TODO: add later option for non-auth user

        if(!is_null(User::find($user->id) && Auth::check()))
            return true;
    }

    public function show(User $user, $shoppingCartInfo)
    {
        return $user->id === $shoppingCartInfo->id_user
            ? Response::allow()
            : Response::deny('You do not own this item.');
    }


    public function update(User $user, $item) :bool
    {
       return $user->id == $item->id_user;
    }


    public function delete(User $user, $item): bool
    {
        return $user->id == $item->id_user;
    }

    //public function create(User $user);
    //public function restore(User $user, ShoppingCartInfo $shoppingCartInfo);
    //public function forceDelete(User $user, ShoppingCartInfo $shoppingCartInfo);
}
