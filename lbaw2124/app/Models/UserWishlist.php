<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWishlist extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $primaryKey = array('id_user', 'id_product');
    public $table = 'wish_list';
    public $incrementing = false;

    protected $fillable = [
        'id_user', 'id_product',
    ];

    protected $hidden = [
    ];

}
