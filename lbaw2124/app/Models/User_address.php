<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_address extends Model
{

  protected $primaryKey = ['id_address', 'id_user'];
  public $incrementing = false;
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

}
