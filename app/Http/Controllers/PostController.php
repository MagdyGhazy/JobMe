<?php

namespace App\Http\Controllers;

use App\Http\Requests\Posts\PostIdExistRequest;
use App\Http\Requests\Posts\PostStatusRequest;
use App\Http\Requests\Posts\StorePostRequest;
use App\Services\Post\PostServices;

class PostController extends Controller
{
    protected $postServices;

    public function __construct(PostServices $postServices)
    {
        $this->postServices = $postServices;
    }


    public function allPosts()
    {
        return $this->postServices->allPosts();
    }


    public function showPost($id)
    {
        return $this->postServices->showPost($id);
    }


    public function postStatus(PostStatusRequest $request)
    {
        return $this->postServices->postStatus($request);

    }


    public function store(StorePostRequest $request)
    {
        return $this->postServices->store($request);
    }
}
