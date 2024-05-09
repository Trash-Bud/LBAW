<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{


    public function create(Request $request, $product_id)
    {

        if (!Auth::user() && !Auth::guard('admin')->check()) return redirect('/products');


        if (is_null($product_id)) abort(500);

        $new_description = $request->input('description');
        $new_rating = $request->input('rating');

        $product = Product::findOrFail($product_id);


        $validator = Validator::make([], []);

        $review = new Review();
        $review->rating = $new_rating;
        $review->description = $new_description;
        $review->id_product = $product_id;
        $review->id_user = Auth::user()->id;
        $review->date = date('Y-m-d');


        //Implicit database policy/trigger
        try {
            $review->save();
        } catch (\Exception $e) {
            $validator->getMessageBag()->add('review', 'Não é possível adicionar a review');
            return back()->withErrors($validator);
        }

        return redirect()->back()->with('new_review', $product_id);
    }

    public function edit(Request $request, $review_id)
    {
        if (!Auth::user()) return redirect('/products');

        $review = Review::findOrFail($review_id);

        $new_description = $request->input('description');
        $new_rating = $request->input('rating');

        $review->user;
        $validator = Validator::make([], []);

        if (!is_null($new_description) && !is_null($new_rating)) {

            $this->authorize('update', $review);

            try {
                $review->update(["description" => $new_description, "rating" => $new_rating, "date" => date('Y-m-d')]);
            } catch (\Exception $e) {
                $validator->getMessageBag()->add('status', 'Não é possivel alterar para o estado da encomenda para o escolhido.');
                return back()->withErrors($validator);
            }
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        if (!Auth::user()) return redirect('/products'); //change to policy or middleware clause


        $review = Review::findOrFail($id);
        $product_id = $review->id_product;

        $this->authorize('delete', $review);

        $review->delete();

        return redirect()->action([ProductController::class, 'show'], [$product_id]);
    }


    public function report($id)
    {
        $validator = Validator::make([], []);
        if (!Auth::check()) return redirect('/login');

        Review::findOrFail($id);

        if(count(Auth::user()->reportedReview($id)) == 0){
            try {
                DB::select(
                    'call report_review(?, ?)',
                    array($id, Auth::id())
                );
            } catch (\Exception $e) {
                $validator->getMessageBag()->add('status', 'Não é possivel alterar para o estado da encomenda para o escolhido.');
                return abort(404);
            }
        }

        return response()->json(["review" => $id]);
    }
}
