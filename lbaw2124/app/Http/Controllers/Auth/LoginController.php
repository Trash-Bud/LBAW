<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\ShoppingCartInfo;
use Auth;

use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectToUser = '/personalinfo';
    protected $redirectToAdmin = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function getUser()
    {
        return $request->user();
    }

    public function home()
    {
        if (is_null(session('cart')))
            session(['cart' => collect([])]);

        return redirect('/products');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($request->email == "deleted_user@example.com")return back()->withErrors([
            'email' => 'Email reservado.',
        ]);


        if (Auth::attempt($credentials, $request->filled('remember'))) {

            //TODO:update cart with session stored one

            if (!is_null(session('cart'))) {
                $cart = session('cart');

                foreach ($cart as $item) {
                    DB::table('shopping_cart_info')
                        ->updateOrInsert(['id_user' => Auth::user()->id, 'id_product' => $item["id_product"]],
                            ['amount' => $item["amount"]]);
                }
            }

            $request->session()->regenerate();

            return redirect()->intended($this->redirectToUser);
        }

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended($this->redirectToAdmin);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showLoginForm()
    {
        if(Auth::guard('admin')->check()) return redirect("/products");
        return view('auth.login');
    }

}
