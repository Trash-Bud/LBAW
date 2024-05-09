<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCartInfo extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $primaryKey = array('id_user', 'id_product');
    public $table = 'shopping_cart_info';
    public $incrementing = false;

    protected $fillable = [
        'id_user', 'id_product', 'amount',
    ];

    protected $hidden = [
    ];

    public static function findCartInfo(User $user, Product $product)
    {
        return ShoppingCartInfo::where('id_product', $product)->where('id_user', $user)->get();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('user', 'id', 'id_user');
    }

    //defined through many to many pivot table in user and product

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('products', 'id', 'id_product');
    }
}
