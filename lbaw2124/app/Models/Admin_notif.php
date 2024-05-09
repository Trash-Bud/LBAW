<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Admin_notif extends Model
{

  public $id;

  public function __construct($comment)
  {
    $this->id = DB::table('admin_dashboard')->insertGetId(
        [ 'comment' => $comment]
    );
  }

  public function createUserNotification($id_user){
    DB::table('dashboard_user')->insert(
        [ 'id' => $this->id, 'id_user' => $id_user]
    );
  }
}
