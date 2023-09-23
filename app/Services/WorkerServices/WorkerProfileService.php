<?php

namespace App\Services\WorkerServices;

use App\Models\Post;
use App\Models\Review;
use App\Models\Worker;
use GuzzleHttp\Psr7\UploadedFile;

class WorkerProfileService
{
    protected $model;

    public function __construct()
    {
        $this->model = Worker::find(auth()->guard('worker')->id());
    }


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

    public function password($data)
    {
        if (request()->has('password')){
            $data['password'] = bcrypt(request()->password);
            return $data;
        }

        $data['password'] = $this->model->password;

        return $data;
    }

    public function photo($data)
    {
        if (request()->has('photo')){
            $data['photo'] =  request()->file('photo')->store('img/workers');
            return $data;
        }
        $data['photo'] = $this->model->photo;
        return $data;
    }

    public function update($request)
    {
        $data = $request->all();
        $data = $this->password($data);
        $data = $this->photo($data);
        $this->model->update($data);
        return response()->json([
            "message" => "updated",
        ]);
    }


}
