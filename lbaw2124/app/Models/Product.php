<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Attribute;


class Product extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id', 'id_product', 'name', 'photo', 'category', 'description', 'stock', 'price', 'original_price', 'id_warehouse', 'on_sale'
    ];

    protected $hidden = [
        'original_price', 'on_sale'
    ];

    public function warehouse(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Warehouse', 'id_warehouse');
    }


    /*public function attributes(){
        return $this->hasMany(Attribute::class, 'id_product', 'id');
    }*/
    public static function attributes($id){
        return DB::table('attributes')->get()->where('id_product', $id);;
    }


    public function shoppingCartUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this
            ->belongsToMany(Order::class, 'shopping_cart_info', 'id_product', 'id_user')
            ->withPivotValue(['amount']);
    }

    public function usersInWishlist()
    {
        return $this->
        belongsToMany(User::class, 'wish_list', 'id_product', 'id_user');
    }

    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }
        return $query->whereRaw('tsvectors @@ to_tsquery(\'english\', ?)', [$search])
            ->orderByRaw('ts_rank(tsvectors, to_tsquery(\'english\', ?)) DESC', [$search]);
    }

    public function reviews(){
        return $this->hasMany(Review::class, 'id_product', 'id');
    }

    public static function getCategories()
    {
        $objects = DB::select("SELECT unnest(enum_range(null, null::categories));");

        $categories = [];
        foreach ($objects as $object)
            array_push($categories, (string) $object->unnest);

        return $categories;
    }

    public static function getProductCategories()
    {
        return DB::table('products')->select('category')->distinct()->get();
    }

    public static function findCategory($category)
    {
        $objects = DB::select("SELECT unnest(enum_range(null, null::categories));");

        $categories = [];
        foreach ($objects as $object) {
            if ((string) $object->unnest == $category)
                return true;
        }

        return false;
    }

    public static function addCategory($category)
    {
        if(self::findCategory($category))  return false;

        DB::statement("ALTER TYPE categories ADD VALUE '{$category}'");
        return true;
    }

    public static function removeCategory($category)
    {
        $product_categories = self::getProductCategories();

        foreach($product_categories as $product_category) {
            if($product_category->category == $category)
                return false;
        }

        DB::select('call remove_category_transaction(?)', array($category));
        return true;
    }

    public function productOnSale($discount, $id_admin)
    {
        return DB::select('call product_on_sale(?,?,?)', array($discount, $this->id, $id_admin));
    }

    public function productOnStock($new_stpck, $id_admin)
    {
        return DB::select('call product_on_stock(?,?,?)', array($new_stpck, $this->id, $id_admin));
    }
}
