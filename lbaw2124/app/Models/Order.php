<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $primaryKey = 'id';

    //add filable and hidden

    protected $fillable = [
        'track_number', 'date_of_order', 'id_user', 'date_of_departure', 'date_of_arrival', 'order_status', 'total_price', 'id_address', 'payment'
    ];

    protected $hidden = [
        'charge_id', 'id'
    ];


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('user', 'id', 'id_user');
    }


    public function products(){
        return $this ->
        belongsToMany(Product::class, 'order_info', 'order_id', 'id_product') ->
        withPivot(['quantity','current_price']);
    }

    public function productsList(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        //Should we use hasManyTrough here?

        return $this->hasManyThrough(
            'App\Models\Product',
            OrderInfo::class,
            'id_product',
            'id_product',
            'id',
            'order_id'
        );
    }

    public static function placeOrder($id, $request) {
        return DB::select('call place_order_transaction(?,?,?)',
                          array($id, $request['address'], $request['payment']));
    } 

    public function cancelOrder(){
        return DB::select('call remove_order_transaction(?)',
                          array($this->id));
    }

}
