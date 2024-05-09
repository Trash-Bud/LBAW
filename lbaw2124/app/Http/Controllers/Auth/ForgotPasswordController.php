<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Email;
use App\Models\User;
use App\Models\Admin;
use App\Models\Password_reset;
use Auth;

use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLinkRequestForm()
    {
        return view('auth.forgotpassword');
    }

    public function sendResetLinkEmail(Request $request)
    {
        if ($request->email == "deleted_user@example.com")return back()->withErrors([
            'email' => 'Email reservado.',
        ]);

        Password_reset::where("timestamp", "<", now()->addMinutes(-30)->toDateTimeString())->delete();
        $user =  User::where('users.email', '=', $request->email)->first();
        if ($user == null){
            $user =  Admin::where('admins.email', '=', $request->email)
            ->get();
            if ($user == null){
                return back()->withErrors([
                    'email' => 'O email inserido não está registado no website.',
                ]);
            }
        }
        $reminder = Password_reset::where('email', '=', $request->email)->first();
        if ($reminder != null){
            return back()->withErrors([
                'email' => 'Um email já foi enviado para esta conta.',
            ]);
        }
        $hash = bin2hex(random_bytes(32));

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $hash
        ]);

        $details = [
            'subject' => 'Pedido de nova password',
            'body' => 'Clique no link a baixo para alterar a sua password!',
            'link' => env('APP_URL') .'/password/reset/'.$hash
        ];

        Mail::to($request->email)->send(new Email($details));

        return redirect('/');

    }

}



