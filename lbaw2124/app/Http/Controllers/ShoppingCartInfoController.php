<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShoppingCartInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Collection;

/**
 * Since it interacts with both the user and product ,
 * this class serves as a model to shopping cart (list and individual item)
 */
class ShoppingCartInfoController extends Controller
{


    public function createCart()
    {
        if (is_null(session('cart')))
            session(['cart' => collect([])]);
    }


    public function index()
    {

        if (Auth::guard('admin')->check()) return redirect('/products'); //change to policy or middleware clause

        if (Auth::user()) {
            $user = auth()->user();

            $this->authorize('viewAny', ShoppingCartInfo::class);

            $products = $user->productsInShoppingCart;

            $sum = DB::select('SELECT get_total(?) AS nb', [$user->id])[0]->nb;

            /*$sum = $products->sum(function ($product) {
                return $product->pivot->amount * $product->price;
            });*/
        } else {
            $this->createCart();

            $cart = session('cart');

            $products = $cart->map(function ($key, $value) {
                $item = Product:: find($key["id_product"]);
                return collect(["item" => $item, "amount" => $key["amount"]]);
            });

            $sum = $this->getSessionCartSumFromProducts($products);
            return view('shopping_cart.shopping_list')->with('products', $products)->with('total', $sum)->with("cart", $cart);
        }

        return view('shopping_cart.shopping_list')->with('products', $products)->with('total', $sum);
    }

    private function getSessionCartSumFromProducts($products)
    {
        $sum = $products->sum(function ($product) {
            return $product["amount"] * $product["item"]->price;
        });
        return $sum;
    }


    private function getSessionCartSum($cart)
    {
        $products = $cart->map(function ($key, $value) {
            $item = Product:: find($key["id_product"]);

            return collect(["item" => $item, "amount" => $key["amount"]]);
        });

        return $this->getSessionCartSumFromProducts($products);
    }

    public function destroy($id)
    {
        $product = Product::find((int)$id);

        if (Auth::user()) {
            $user = Auth::user();

            $item = ShoppingCartInfo::where('id_user', $user->id)->where('id_product', $product->id)->get();

            /*if ($user->cannot('delete', ShoppingCartInfo::class, $item))
                abort(403);*/

            ShoppingCartInfo::where('id_user', $user->id)->where('id_product', $product->id)->delete();

            $item["price"] = $product->price;

            return response()->json(["total" => $user->getCartTotal(), "id_product" => $product->id]);

        } else {
            $this->createCart();
            $cart = session('cart');

            $amount = 0;

            if (count($cart->firstWhere('id_product', $product->id)) > 0) {
                $amount = $cart->firstWhere('id_product', $product->id)["amount"];
                $cart = $cart->filter(function ($item) use ($product) {
                    return $item["id_product"] != $product->id;
                });
            }
            $cart = $cart->filter(function ($item) use ($product) {
                return $item["id_product"] != $product->id;
            });

            session(["cart" => $cart]);
            return response()->json(["total" => $this->getSessionCartSum($cart), "id_product" => $product->id]);
        }

    }


    public function update(Request $request)
    {
        $product_id = $request->input('id');
        $new_amount = $request->input('quantity');

        $product = Product::find($product_id);


        if ($product->stock < $new_amount) {
            abort(403);
        }

        if (Auth::user()) {
            $user = Auth::user();
            $item = ShoppingCartInfo::where('id_user', $user->id)->where('id_product', $product->id)->get();
            /*if ($user->cannot('update', ShoppingCartInfo::class, $item))
                abort(403);*/

            ShoppingCartInfo::where('id_user', $user->id)
                ->where('id_product', $product->id)
                ->update(['amount' => ((int)$new_amount)]);

            $item["total"] = $user->getCartTotal();

            return $item;
        } else {
            $this->createCart();
            $cart = session('cart');

            $new_cart = $cart->map(function ($key, $value) use ($product_id, $new_amount) {
                if ($product_id == $key["id_product"]) {
                    $key["amount"] = $new_amount;
                }
                return $key;
            });
            session(["cart" => $new_cart]);
            $res["total"] = $this->getSessionCartSum($new_cart);
            return $res;
        }
    }

    public function addToCart(Request $request)
    {
        $product_id = $request->input('id');
        $product = Product::findOrFail($product_id);
        $add_amount = $request->input('quantity');

        if ($product->stock == 0)
            abort(403);

        if (Auth::check()) {
            $user = Auth::user();

            $item = ShoppingCartInfo::where('id_user', $user->id)->where('id_product', $product->id)->first();

            /*if (isset($item))
                if ($user->cannot('update', ShoppingCartInfo::class, $item))
                    abort(403);*/

            $amount = (isset($item)) ? ($item->amount + $add_amount) : $add_amount; //adds one product only

            DB::table('shopping_cart_info')
                ->updateOrInsert(['id_user' => $user->id, 'id_product' => $product->id],
                    ['amount' => $amount]);
            //for JS DOM modifications
            return ShoppingCartInfo::where('id_user', $user->id)->where('id_product', $product->id)->first();
        } else {
            $this->createCart();
            $cart = session('cart');

            $new_cart = $cart->map(function ($key, $value) use ($product_id, $add_amount) {
                if ($product_id == $key["id_product"]) {
                    $key["amount"] = $key["amount"] + $add_amount;
                }
                return $key;
            });

            if (count($cart->where('id_product', $product_id)->toArray()) == 0) {
                $new_cart = $cart;
                $new_cart->push(['id_product' => $product_id, 'amount' => $add_amount]);
            }

            session(["cart" => $new_cart]);
            return response()->json(["id_product" => $product_id]);
        }

    }

    public function emptyCart()
    {

        if (Auth::user()) {
            $user = Auth::user();

            $products = $user->productsInShoppingCart;
            $amount = 0;

            if (isset($products)) {
                $amount = $user->productsInShoppingCart->count();
                ShoppingCartInfo::where('id_user', $user->id)->delete();
            } else {
                abort("404");
            }
        } else {
            $this->createCart();
            $amount = count(session('cart'));
            session(['cart' => collect([])]);
        }
        return $amount;
    }

    //public function create();
    //public function store(Request $request);

}


