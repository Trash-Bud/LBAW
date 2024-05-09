<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Admin;
use App\Models\User;
use App\Models\Order;
use File;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::guard('admin')->check()) return redirect('/login');

        return view('admin.personalinfo');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!Auth::guard('admin')->check()) return redirect('/login');

        $user = Auth::guard('admin')->user();
        $hashed_input_password = bcrypt($request->current_password);

        $validator  = Validator::make([], []);

        $request->validate([
            'current_password' => 'required',
            'name' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'password_confirmation' => 'required_with:password',
            'email' => 'nullable|string|email|max:255|unique:users,email,'.$user->id,
        ]);

        if (!password_verify($request->current_password, $user->password)) {
            $validator->getMessageBag()->add('current_password', 'Password input does not match current password');
            return redirect()->back()->withErrors($validator);
        }

        if ($request->filled('name')) {
            $user->update([
            'name' => $request->name]);
        }
        if ($request->filled('email')) {
            $user->update([
            'email' => $request->email]);
        }
        if ($request->filled('password')) {
            $user->update([
            'password' => bcrypt($request->password)]);
        }

        return redirect()->back();
    }

    /**
     * Display the users list.
     *
     * @return \Illuminate\Http\Response
     */
    public function showUsers()
    {
        if (!Auth::guard('admin')->check()) return redirect('/login');

        $users = User::orderBy('name', 'ASC')->where('id','!=','0')->get();
        return view('admin.users', ['users'=>$users]);
    }

    /**
     * Display user search results.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchUser()
    {
        if (!Auth::guard('admin')->check()) return redirect('/login');

        $search = request()->get('search');
        $users = User::where('name', 'ilike', "%{$search}%")->orderBy('name', 'ASC')->get();

        return view('admin.users', ['users'=>$users]);
    }

    /**
     * Display create user page.
     *
     * @return \Illuminate\Http\Response
     */
    public function createUserForm()
    {
        if (!Auth::guard('admin')->check()) return redirect('/login');

        return view('admin.createuser');
    }

    /**
     * Creates user.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     *
     */
    public function createUser(Request $request)
    {
        $data=$request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        return redirect('users');
    }

    /**
     * Display user page.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewUser($id)
    {
        if ($id == 0) return redirect(403);
        if (!Auth::guard('admin')->check()) return redirect('/login');

        $user = User::where('id', $id)->first();

        return view('admin.edituser', ['user'=>$user]);
    }

    /**
     * Edit user.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editUser(Request $request, $id)
    {
        if ($id == 0) return redirect(403);

        if (!Auth::guard('admin')->check()) return redirect('/login');

        $admin = Auth::guard('admin')->user();
        $hashed_input_password = bcrypt($request->current_password);

        $user = User::where('id', $id)->first();

        $validator  = Validator::make([], []);

        $request->validate([
          'profile_pic' => 'image|mimes:jpeg,jpg,png|dimensions:min_width=100,min_height=100',
          'name' => 'nullable|string|max:255',
          'email' => 'nullable|string|email|max:255|unique:users,email,'. $user->id,
        ]);

        if (!password_verify($request->current_password, $admin->password)) {
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

        return redirect()->back();
    }

    /**
     * Block user.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function blockUser(Request $request, $id)
    {
        if (!Auth::guard('admin')->check()) return redirect('/login');

        $user = Auth::guard('admin')->user();
        $user->block_user($id);

        return redirect()->back();
    }

    /**
     * Unblock user.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unblockUser(Request $request, $id)
    {
        if (!Auth::guard('admin')->check()) return redirect('/login');

        $user = Auth::guard('admin')->user();
        $user->unblock_user($id);

        return redirect()->back();
    }

        /**
     * Display the users list.
     *
     * @return \Illuminate\Http\Response
     */
    public function showOrders()
    {
        if (!Auth::guard('admin')->check()) return redirect('/login');

        $orders = Order::get();
        return view('admin.orders', ['orders'=>$orders]);
    }

            /**
     * Display the users list.
     *
     * @return \Illuminate\Http\Response
     */
    public function showUserOrders($id)
    {
        if (!Auth::guard('admin')->check()) return redirect('/login');

        $orders = Order::where('id_user','=',$id)->get();
        return view('admin.orders', ['orders'=>$orders]);
    }

    /**
     * Display user search results.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchOrders()
    {
        if (!Auth::guard('admin')->check()) return redirect('/login');

        $search = request()->get('search');
        $orders = Order::where('track_number', 'ilike', "%{$search}%")->get();

        return view('admin.orders', ['orders'=>$orders]);
    }

        /**
     * Display user search results.
     *
     * @return \Illuminate\Http\Response
     */
    public function editOrderForm($id)
    {
        if (!Auth::guard('admin')->check()) return redirect('/login');
        $orders = Order::find($id);

        return view('admin.editorders',['order'=>$orders]);
    }

     /**
     * Display user search results.
     *
     * @return \Illuminate\Http\Response
     */
    public function editOrder(Request $request, $id)
    {
        $validator  = Validator::make([], []);
        if (!Auth::guard('admin')->check()) return redirect('/login');

        try{
            DB::select(
                'call update_status(?,?,?)',
                array($id, $request->status,Auth::guard('admin')->id())
            );
        }
        catch (\Exception $e) {
            $validator->getMessageBag()->add('status', 'Não é possivel alterar para o estado da encomenda para o escolhido.');
            return back()->withErrors($validator);
        }

        return redirect('/order/'.$id);
    }

    public function showHistory(){
        if (!Auth::guard('admin')->check()) return redirect('/login');

        $history = DB::table('admin_history')
        ->select('admins.email as admin_email', 'blocked.email as user_email','deleted.email as user_email','id_order',
        'id_review','id_product', 'name', 'comment', 'date')
        ->join('admins','admins.id','admin_history.id_admin')
        ->leftjoin('modification','modification.id','admin_history.id')
        ->leftjoin('hidden_review','hidden_review.id','admin_history.id')
        ->leftjoin('blocked','blocked.id','admin_history.id')
        ->leftjoin('deleted','deleted.id','admin_history.id')
        ->leftjoin('order_update','order_update.id','admin_history.id')
        ->orderby('date')
        ->get();

        return view('admin.history',['history'=>$history]);
    }

    public function showDashboard(){
        if (!Auth::guard('admin')->check()) return redirect('/login');

        $notif = DB::table('admin_history')
        ->leftjoin('dashboard_review','dashboard_review.id','admin_history.id')
        ->leftjoin('dashboard_user','dashboard_user.id','admin_history.id')
        ->join('users','users.id','dashboard_user.id_user')
        ->orderby('date')
        ->get();

        return view('admin.dashboard',['notifications'=>$notif]);
    }

    /**
     * Show manage products page.
     *
     * @return \Illuminate\Http\Response
     */
    public function manageProducts()
    {
        if (!Auth::guard('admin')->check()) return redirect('/login');

        return view('admin.manageproducts');
    }

    /**
     * Show add product page.
     *
     * @return \Illuminate\Http\Response
     */
    public function addProductForm()
    {
        if (!Auth::guard('admin')->check()) return redirect('/login');

        $warehouses = DB::table('warehouse')->get(); //Alterar para model
        $categories = Product::getCategories();

        return view('admin.addproduct')->with('warehouses', $warehouses)->with('categories', $categories);
    }

    /**
     * Add product.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addProduct(Request  $request) {

        $data=$request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,jpg,png|dimensions:min_width=100,min_height=100'
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $filename;
        if ($data['photo']) {
            $img = $request->file('photo');
            $ext = $img->getClientOriginalExtension();
            $filename = time() . "." . $ext;
            $img->move('product_pictures',$filename);
        }

        Product::create([
            'name' => $data['name'],
            'category' => $data['category'],
            'stock' => $data['stock'],
            'original_price' => $data['original_price'],
            'id_warehouse' => $data['id_warehouse'],
            'description' => $data['description'],
            'photo' => $filename
        ]);

        return redirect('products')->with('products', Product::get());
    }

    /**
     * Show add product page.
     *
     * @return \Illuminate\Http\Response
     */
    public function editProductForm($id)
    {
        if (!Auth::guard('admin')->check()) return redirect('/login');

        $product = Product::where('id', $id)->first();
        return view('admin.editproduct')->with('product', $product);
    }

    /**
     * Add product.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editProduct(Request $request, $id) {

        if (!Auth::guard('admin')->check()) return redirect('/login');

        $product = Product::where('id', $id)->first();

        $validator  = Validator::make([], []);

        $request->validate([
          'photo' => 'image|mimes:jpeg,jpg,png|dimensions:min_width=100,min_height=100',
          'name' => 'nullable|string|max:255'
        ]);

        if($request->hasFile('photo')){
          if (File::exists($product->photo)) File::delete($product->photo);
          $img = $request->file('photo');
          $ext = $img->getClientOriginalExtension();
          $filename = time() . "." . $ext;
          $img->move('product_pictures',$filename);
          $product->update([
            'photo' => $filename]);
        }

        if ($request->filled('name')) {
          $product->update([
          'name' => $request->name]);
        }
        if ($request->filled('description')) {
          $product->update([
          'description' => $request->description]);
        }
        if ($request->filled('stock')) {
            $product->productOnStock($request->stock,
            Auth::guard('admin')->id());
        }
        if ($request->filled('price')) {
            if($request->price < $product->price) {
                $product->productOnSale($request->price/$product->original_price,
                                        Auth::guard('admin')->id());
            }

            $product->update([
                'price' => $request->price]);

            if ($request->price >= $product->original_price){
                $product->update([
                    'on_sale' => false]);
            }

        }

        return redirect()->back();
    }

     /**
     * Show manage categories page.
     *
     * @return \Illuminate\Http\Response
     */
    public function manageCategories()
    {
        if (!Auth::guard('admin')->check()) return redirect('/login');

        return view('admin.managecategories')->with('categories', Product::getCategories());
    }

    /**
     * Show manage categories page.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function addCategory(Request $request)
    {
        if (!Auth::guard('admin')->check()) return redirect('/login');

        $category = $request->name;
        $errors = new MessageBag();

        if(!Product::addCategory($category))
            $errors->add('add_category', 'A categoria que tentou adicionar já existe');

        return redirect('manage_categories')->withErrors($errors);
    }

     /**
     * Show manage categories page.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function removeCategory(Request $request)
    {
        if (!Auth::guard('admin')->check()) return redirect('/login');

        $category = $request->name;
        $errors = new MessageBag();

        if(!Product::removeCategory($category))
            $errors->add('remove_category', 'Não é possível eliminar uma categoria com produtos associados');

        return redirect('manage_categories')->withErrors($errors)->withErrors($errors);
    }

}
