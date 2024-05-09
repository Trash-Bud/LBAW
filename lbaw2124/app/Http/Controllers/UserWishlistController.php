<?php

namespace App\Http\Controllers;

use App\Models\UserWishlist;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ShoppingCartInfo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserWishlistController extends Controller
{
    public function index()
    {

        if (Auth::guard('admin')->check() || !Auth::user()) return redirect('/products'); //change to policy or middleware clause

        $user = auth()->user();

        //$this->authorize('viewAny', UserWishlist::class);

        $products = $user->productsInWishlist;

        return view('wishlist.show_list')->with('products', $products);
    }

    public function store(Request $request)
    {
        if (Auth::guard('admin')->check() || !Auth::user()) return redirect('/products'); //change to policy or middleware clause

        $user = auth()->user();

        $product_id = $request->input('id');
        $product = Product::findOrFail($product_id);

        /*if ($user->cannot('store', UserWishlist::class, $item))
                abort(403);*/

        DB::table('wish_list')
            ->updateOrInsert(['id_user' => $user->id, 'id_product' => $product->id]);

        return response()->json(["id_product" => $product->id_product]);
        //TODO: add a find for duplication?
    }

    public function destroy(Request $request)
    {
        if (Auth::guard('admin')->check() || !Auth::user()) return redirect('/products'); //change to policy or middleware clause

        $user = auth()->user();
        $product_id = $request->input('id');
        $product = Product::findOrFail($product_id);

        //TODO: find it first?
        /*if ($user->cannot('delete', UserWishlist::class, $item))
                abort(403);*/

        UserWishlist::where('id_user', $user->id)->where('id_product', $product->id)->delete();

        return response()->json(["id_product" => $product->id_product]);

    }

    public function emptyWishlist(Request $request)
    {
        if (Auth::guard('admin')->check() || !Auth::user()) return redirect('/products'); //change to policy or middleware clause

        $user = auth()->user();

        $products = $user->productsInWishlist;

        if (isset($products)) {
            UserWishlist::where('id_user', $user->id)->delete();
        } else {
            abort("404");
        }
    }


}
