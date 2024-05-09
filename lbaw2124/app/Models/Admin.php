<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class Admin extends Authenticatable
{
    use Notifiable;
    protected $guard = 'admin';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function delete_user($id){
        DB::select(
            'call delete(?,?)',
            array($id,$this->id)
        );
    }

    public function block_user($id){
        DB::select(
            'call block(?,?)',
            array($id,$this->id)
        );
    }

    public function unblock_user($id){
        DB::select(
            'call unblock(?,?)',
            array($id,$this->id)
        );
    }

}
