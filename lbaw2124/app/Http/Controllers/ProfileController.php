<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Models\User_address;
use App\Models\User;
use App\Models\Admin_notif;
use Validator;
use File;

class ProfileController extends Controller
{
    /**
     * Shows the card for a given id.
     *
     * @param  int  $id
     * @return Response
     */
    public function showPI()
    {
      if (!Auth::check()) return redirect('/login');
      return view('profile.myaccount.personalinfo');
    }

    public function showWishList()
    {
      if (!Auth::check()) return redirect('/login');
        $user = auth()->user();
        $data = $user->getWishlist();
      return view('profile.wishlist.wishlist',['wish_list'=>$data]);
    }

    public function showNotifications()
    {
      if (!Auth::check()) return redirect('/login');
        $user = auth()->user();
        $data = $user->getNotifications();
      return view('profile.notifications.notifications',['notification_list'=>$data]);
    }

    public function showReviews()
    {
      if (!Auth::check()) return redirect('/login');
        $user = auth()->user();
        $data = $user->getReviews();
      return view('profile.reviews.reviews',['review_list'=>$data]);
    }


  /**
   * Updates the state of an individual item.
   *
   * @param  UpdateProfileRequest request containing the new state
   * @return Request
   */
  public function update(Request $request)
  {
    $user = auth()->user();
    $hashed_input_password = bcrypt($request->current_password);

    $validator  = Validator::make([], []);

    $request->validate([
      'profile_pic' => 'image|mimes:jpeg,jpg,png|dimensions:min_width=100,min_height=100',
      'current_password' => 'required',
      'name' => 'nullable|string|max:255',
      'password' => 'nullable|string|min:6|confirmed',
      'password_confirmation' => 'required_with:password',
      'email' => 'nullable|string|email|max:255|unique:users,email,'.$user->id,
      'nif' => 'nullable|unique:users'
    ]);

    if (!password_verify($request->current_password, $user->password)){
      $validator->getMessageBag()->add('current_password', 'Password input does not match current password');
      return redirect()->back()->withErrors($validator);
    }

    if($request->hasFile('profile_pic')){
      if (File::exists($user->profile_pic)) File::delete($user->profile_pic);
      $img = $request->file('profile_pic');
      $ext = $img->getClientOriginalExtension();
      $filename = time() . "." . $ext;
      $img->move('profile_pictures',$filename);
      $user->update([
        'profile_pic' => $filename]);
    }

    if ($request->filled('name')) {
      $user->update([
      'name' => $request->name]);
    }
    if ($request->filled('email')) {
      $user->update([
      'email' => $request->email]);
    }
    try {
      if ($request->filled('nif')) {
        $user->update([
        'nif' => $request->nif]);
      }
    }
    catch (\Exception $e) {
      $validator->getMessageBag()->add('invalid_nif', 'O NIF inserido não é válido');
      return redirect()->back()->withErrors($validator);
    }

    if ($request->filled('password')) {
      $user->update([
      'password' => bcrypt($request->password)]);
    }

    return redirect()->back();
  }

  public function listAddresses()
  {
    if (!Auth::check()) return redirect('/login');
    $user = auth()->user();
    $data = DB::table('addresses')
      ->join('user_addresses', 'id', '=', 'user_addresses.id_address')
      ->where('id_user',$user->id)
      ->select('street','country','postal_code','id')
      ->get();
    return view('profile.myaccount.addresses.addresses',['address_list'=>$data]);
  }

  public function add_adress_form()
  {
    return view('profile.myaccount.addresses.add_address');
  }

  public function edit_adress_form($id)
  {
    $data = Address::find($id);
    return view('profile.myaccount.addresses.edit_address',['address'=>$data]);
  }

  public function deleteAddress($id)
  {
    $validator  = Validator::make([], []);

    $data = Address::find($id);
    try {
      $data->delete();
    }
    catch (\Exception $e) {
      $validator->getMessageBag()->add('delete_address', 'Não é possível eliminar a morada de uma encomenda em trânsito');
      return redirect()->back()->withErrors($validator);
    }
    return redirect('addresses');
  }

  public function addAddress(Request $req){

    $address = new Address;
    $address_user = new User_address;

    $validator  = Validator::make([], []);

    $req->validate([
      'street' => 'required|string',
      'country' => 'required|string',
      'postal_code' => 'required|integer',
    ]);

    $address->street = $req->street;
    $address->country = $req->country;
    $address->postal_code = $req->postal_code;

    try {
      $address->save();    }
    catch (\Exception $e) {
      $validator->getMessageBag()->add('postal_code', 'O código postal inserido não é válido');
      return redirect()->back()->withErrors($validator);
    }

    $address->refresh();

    $address_user->id_user = auth()->user()->id;
    $address_user->id_address = $address->id;
    $address_user->save();

    return redirect('addresses');
  }

  public function editAddress($id, Request $request)
  {
    $address = Address::find($id);

    $validator  = Validator::make([], []);

    $request->validate([
      'street' => 'required|string',
      'country' => 'required|string',
      'postal_code' => 'required|integer',
    ]);

    if ($request->filled('street')) {
      $address->update([
      'street' => $request->street]);
    }
    if ($request->filled('country')) {
      $address->update([
      'country' => $request->country]);
    }
    try {
      if ($request->filled('postal_code')) {
        $address->update([
        'postal_code' => $request->postal_code]);
      }
    }
    catch (\Exception $e) {
      $validator->getMessageBag()->add('postal_code', 'O código postal inserido não é válido');
      return redirect()->back()->withErrors($validator);
    }

    $address->save();

    return redirect('addresses');
  }

  public function delete_user($id){
    $redirectTo = '/';
    if ($id == 0) return redirect(403);
    if(!Auth::guard('admin')->check()){
        if ((Auth::check() && auth()->user()->id != $id) || (!Auth::check())){
            return redirect(403);
        }
        else{
            $user = auth()->user();
            $user->delete();
            $redirectTo = '/logout';
        }
    }
    else{
        $redirectTo = '/users';
        $user = Auth::guard('admin')->user();
        $user->delete_user($id);
    }

    return redirect($redirectTo);
  }

  public function appeal_unblock(){
    if (!Auth::check()) return redirect(403);
    if (!auth()->user()->blocked) return redirect(403);
    $user = auth()->user();

    if($user->allowedUnblockNotif()){
        return back()->withErrors([
            'block' => 'Só podes fazer um pedido a cada 24 horas.',
        ]);
    }

    $notif = new Admin_notif('O utilizador fez um pedido para poder voltar a fazer avaliações.');
    $notif->createUserNotification(auth()->user()->id);

    return redirect('/personalinfo');
  }
}
