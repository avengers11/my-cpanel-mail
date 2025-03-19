<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AmazonReview;
use App\Models\AmazonReviewTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AmazonReviewController extends Controller
{
    //reviewAll
    public function reviewAll()
    {
        $user = Auth::user();
        $reviews = AmazonReview::latest()->where("user_id", $user->id)->get();
        if($user->role == "admin"){
            $reviews = AmazonReview::latest()->get();
        }

        return view("admin.pages.reviews.all", compact("reviews", "user"));
    }
    // addReview
    public function addReview(Request $request)
    {
        $user = Auth::user();

        if($request->isMethod("POST")){
            $file = $request->file('book_image');
            $file_ext = $file->getClientOriginalExtension();
            $name = 'images/reviews-'. Str::random(10) . '.' . $file_ext;
            Storage::put($name, file_get_contents($file), 'public');

            $review = new AmazonReview();
            $review->user_id = $user->id;
            $review->book_name = $request->book_name;
            $review->book_image = $name;
            $review->book_url = $request->book_url;
            $review->frequency = $request->frequency;
            $review->type = $request->type;
            $review->total_review = $request->total_review;
            $review->save();

            return redirect(route("admin.review.all"))->with("success", "You are successfully add a new review!");
        }

        return view("admin.pages.reviews.add-review");
    }

    //todaysTask
    public function todaysTask()
    {
        $user = Auth::user();
        $reviews = AmazonReviewTask::latest()->where("user_id", $user->id)->get();
        if($user->role == "admin"){
            $reviews = AmazonReviewTask::latest()->get();
        }

        return view("admin.pages.reviews.todays", compact("reviews", "user"));
    }
}
