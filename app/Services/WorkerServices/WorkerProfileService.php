<?php

namespace App\Services\WorkerServices;

use App\Models\Post;
use App\Models\Review;
use App\Models\Worker;

class WorkerProfileService
{
    public function userProfile() {

        $workerId = auth()->guard('worker')->id();
        $worker = Worker::with('Posts.review')->find($workerId);
        $posts = Post::whereHas('review')->where('worker_id',$workerId)->pluck('id');
        $review = Review::whereIn("post_id",$posts)->get();
        $averageRate =round($review->sum('rate') / $review->count(),1) ;
        return response()->json([
            "data" => array_merge($worker->toArray(),["rate"=>$averageRate])
        ]);

    }

}
