<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class OrderController extends Controller
{

    public function index()
    {
        if (!Auth::check()) return redirect('/login');

        $this->authorize('viewAny', Order::class);

        $user = auth()->user();
        $orders = $user->orders;

        return view('profile.myaccount.orders')->with('orders', $orders);
    }



    public function show(int $order_id)
    {
        $order = Order::findOrFail($order_id);

        if(!Auth::guard('web')->check() && !Auth::guard('admin')->check())
            return redirect('/login');

        if (!Auth::guard('admin')->check()) $this->authorize('view', $order);

        $address = Address::find($order->id_address);

        $products = DB::table('order_info')->join('products','id', '=', 'order_info.id_product')->where('order_id','=',$order->id)->get(); //coupled with amount and current_price
        return view('orders.orderdetails')->with('order', $order)->with('address', $address)->with('products', $products);
    }

    public function cancelOrder($id)
    {
        $validator  = Validator::make([], []);
        $order = Order::findOrFail($id);
        if(!Auth::guard('web')->check() && !Auth::guard('admin')->check())
            return redirect('/login');
        
        try {
            $order->cancelOrder();
        }
        catch (\Exception $e) {
            $validator->getMessageBag()->add('invalid_order', 'Não é possível cancelar esta encomenda.');
            return redirect()->back()->withErrors($validator);
        }
        return redirect()->back();
    }


    //public function store(Request $request);
    //public function edit(Order $order);
    //public function update(Request $request, Order $order)
    //public function destroy(Order $order);
    //public function create();
}
