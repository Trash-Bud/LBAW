<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderInfo extends Model
{
    use HasFactory;
    protected $primaryKey = array('order_id', 'id_product');
    protected $table = 'order_info';
    public $timestamps = false;
    public $incrementing = false;


    //add filable and hidden

    //what relation should exist here??
    /*public function order(){
        return $this->hasMany();
    }*/
}
