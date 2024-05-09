<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Address;
use App\Models\User;
use Validator;

class CheckoutController extends Controller
{
    public function index() {
        if(Auth::guard('admin')->check()) return redirect('/products');
        if (!Auth::check()) return redirect('/login');
        return redirect(route('shoppingcart'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if (!Auth::check()) return redirect('/login');
        $user = Auth::user();
        $addresses = $user->getUserAddresses();
        return view('shopping_cart.checkout')->with('total', $request->total)->with('addresses', $addresses);
    }

    public function placeOrder(Request $request)
    {
        if (!Auth::check()) return redirect('/login');

        $validator  = Validator::make([], []);

        try {
            Order::placeOrder(Auth::id(), $request);
        }
        catch (\Exception $e) {
            $validator->getMessageBag()->add('no_stock', 'Não há stock disponível de um ou mais produtos');
            return redirect(route('shoppingcart'))->withErrors($validator);
        }


        return redirect(route('orders'));

        //track_number, date_of_order, id_user, date_of_departure, date_of_arrival, order_status, total_price, charge_id, id_address, payment
    }

}
