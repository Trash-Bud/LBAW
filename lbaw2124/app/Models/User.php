<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps = false;
    public $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'nif', 'profile_pic', 'blocked'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'id'
    ];


    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Order', 'id_user');
    }

    public function cartInfos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\ShoppingCartInfo', 'id_user');
    }


    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Review', 'id_user');
    }

    public function productsInShoppingCart()
    {
        return $this->
        belongsToMany(Product::class, 'shopping_cart_info', 'id_user', 'id_product')->
        withPivot(['amount']);
    }


    public function productsInCart(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough('App\Models\Product',
            'App\Models\ShoppingCartInfo',
            'id_product',
            'id',
            'id',
            'id_user'
        );
    }

    public function getWishList()
    {
        return DB::table('wish_list')
            ->join('products', 'id', '=', 'wish_list.id_product')
            ->where('id_user', $this->id)
            ->select('photo', 'name', 'category', 'products.id', 'price')
            ->get();
    }

    public function getNotifications()
    {
        return DB::table('notifications')
            ->leftjoin('flagged_notification', 'flagged_notification.id_notification', 'notifications.id')
            ->leftjoin('order_status_notification', 'order_status_notification.id_notification', 'notifications.id')
            ->leftjoin('product_update_notification', 'product_update_notification.id_notification', 'notifications.id')
            ->where('id_user', $this->id)
            ->orderby('date')
            ->get();
    }

    public function getReviews()
    {
        return DB::table('review')
            ->join('products', 'products.id', '=', 'review.id_product')
            ->where('id_user', $this->id)
            ->select('rating', 'date', 'review.description', 'review.id_product', 'photo', 'name')
            ->get();
    }

    public function delete()
    {
        DB::select(
            'call delete(?,?)',
            array($this->id, null)
        );
    }

    public function getCartTotal()
    {

        return DB::select('SELECT get_total(?) AS nb', [$this->id])[0]->nb;
    }

    public function productsInWishlist()
    {
        return $this->
        belongsToMany(Product::class, 'wish_list', 'id_user', 'id_product');
    }

    public function getUserAddresses()
    {
        return DB::table('user_addresses')->select('addresses.id', 'addresses.street')
            ->join('addresses', 'addresses.id', '=', 'user_addresses.id_address')
            ->where('user_addresses.id_user', '=', $this->id)
            ->get();
    }

    public function allowedUnblockNotif()
    {
        return (count(DB::table('admin_dashboard')
                ->join('dashboard_user', 'dashboard_user.id', 'admin_dashboard.id')
                ->where([["date", ">", now()->addHours(-24)->toDateTimeString()], ["id_user", "=", $this->id]])
                ->get()) != 0);
    }

    public static function bougthProducts($user_id)
    {
        return DB::table('orders')->where('id_user', '=', $user_id)
            ->join('order_info', 'order_id', '=', 'orders.id')->where('order_status', 'Entregue')->select('id_product', 'id_user')->get();
    }

    public function reportedReview($review_id){
        return DB::table('dashboard_review')->where('id_review', $review_id)->where('id_user', $this->id)->get();
    }

}
