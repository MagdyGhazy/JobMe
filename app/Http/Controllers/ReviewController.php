<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ReviewController extends Controller
{
    protected function store(ReviewRequest $request)
    {
        $data = $request->all();
        $data['client_id'] = auth()->guard('client')->id();
        $review = Review::create($data);
        return response()->json([
            "data"=>$review,
        ]);
    }

    protected function showPostReview($id)
    {

        $review = QueryBuilder::for(Review::class)
            ->allowedFilters(['comment', 'rate'])
            ->wherePostId($id)
            ->get();


//        $review = Review::wherePostId($id)->get();
        $average = $review->sum('rate') / $review->count();
        return response()->json([

            "rates"=> round($average,1),
            "data"=>ReviewResource::collection($review),

        ]);
    }
}
