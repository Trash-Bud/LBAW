<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Email;
use Auth;
use App\Models\User;
use App\Models\Admin;
use App\Models\Password_reset;

use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
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

    public function showResetForm($token)
    {
        Password_reset::where("timestamp", "<", now()->addMinutes(-30)->toDateTimeString())->delete();
        $reminder =  Password_reset::where('token', '=', $token)->first();
        if ($reminder == null) return redirect(404);

        return view('auth.newpassword',['token'=>$token]);
    }

    public function reset(Request $request, $token)
    {

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $reset = Password_reset::find($token);


        $user = User::where('email', '=', $reset->email)->first();
        if ($user == null) {
            $user = Admin::where('email', '=', $reset->email)->first();
            if ($user == null) {
                $reset->delete();
                return redirect('/');
            };
        }

        $user->update([
            'password' => bcrypt($request->password)]);

        $reset->delete();

        return redirect('/login');
    }

}



