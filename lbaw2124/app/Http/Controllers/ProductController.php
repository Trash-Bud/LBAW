<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Attribute;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{


    public function index()
    {
        $products = Product::select('id', 'id_product', 'name', 'category',
            'description', 'price', 'category', 'photo')->get();
        $categories = DB::table('products')->select('category')->distinct()->get();
        return view('products.homepage')->with('products', $products)->with('categories', $categories);
    }


    public function searchProducts()
    {
        $search = request()->get('search');
        $categories = DB::table('products')->select('category')->distinct()->get();
        $product1 = Product::select('id', 'id_product', 'name', 'category',
            'description', 'price', 'category', 'photo')->where('name', 'ilike', "%{$search}%")->get();

        if ($search == trim($search) && str_contains($search, ' ')) {
            $product = $product1;
        } else {
            $product2 = Product::search($search)->get();

            $merged = $product2->merge($product1);

            $product = $merged->all();
        }

        return view('products.homepage')->with('products', $product)->with('categories', $categories);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $product = Product::findOrFail($id);
        $attributes = Product::attributes($id);
        $reviews = $product->reviews->where('hidden', 0);
        $rating = $product->reviews->avg('rating');
        $rating = number_format($rating, 1);

        $has_bought = false;
        $my_review = null;

        if (Auth::user()) {
            $user_products = User::bougthProducts(Auth::user()->id);
            $has_bought = !(count($user_products->where('id_product', '=', $id)) == 0);

            $my_review = Auth::user()->reviews->where('id_product', $id)->first();
            $reviews = $reviews->where('id_user', '!=', Auth::user()->id);
        }

        return view('products.details')->with('product', $product)
            ->with('attributes', $attributes)->with('reviews', $reviews)->with('rating', $rating)
            ->with('has_bought', $has_bought)->with('my_review', $my_review);
    }


    public function getPrice(int $product_id)
    {
        $product = Product::findOrFail($product_id);

        return $product->price;
    }


}
