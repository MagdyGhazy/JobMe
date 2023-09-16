<?php

namespace App\Services\Post;

use App\Http\Requests\Posts\PostStatusRequest;
use App\Models\Admin;
use App\Models\Post;
use App\Models\PostPhotos;
use App\Notifications\PostNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Mockery\Exception;


class PostServices
{
    protected $model;

     public function __construct(Post $post)
     {
         $this->model = $post;
     }


    public function allPosts()
    {
       return $this->model->get()->makeHidden('status');
    }

    public function showPost($id)
    {
        return $this->model->find($id);
    }

    public function postStatus($request)
    {
        $post = $this->model->find($request->post_id);
        $post->update([
            'status'=>$request->status ,
            'rejected_reason'=>$request->rejected_reason
        ]);
        Notification::sendNow($post->worker, new PostNotification($post->worker,$post));
        return response()->json(['message'=>'status changed successfully']);
    }


     public function storePost($request)
     {
         $data = $request->except('photo');
         $data['worker_id'] = auth()->guard('worker')->id();
         $post = Post::create($data);
         return $post;
     }

     public function storePhoto($request,$post)
     {
         if ($request->hasFile('photos')){
             foreach ($request->file('photos') as $photo){
                 $postPhoto = new PostPhotos();
                 $postPhoto->post_id = $post->id;
                 $postPhoto->photo = $photo->store('posts/photos');
                 $postPhoto->save();
             }
         }
     }
    public function sendNotification($post)
    {
        $admins = Admin::all();
        Notification::sendNow($admins, new PostNotification(auth()->guard('worker')->user(),$post));
    }
     public function store($request)
     {
         try {
             DB::beginTransaction();
             $post = $this->storePost($request);
             $this->storePhoto($request, $post);
             $this->sendNotification($post);
             DB::commit();
             return response()->json(['message'=>'post created successfully']);
         }catch (Exception $e){
             DB::rollBack();
             return $e->getMessage();
         }
     }
}
