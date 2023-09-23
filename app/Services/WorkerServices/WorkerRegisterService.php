<?php

namespace App\Services\WorkerServices;

use App\Models\Worker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;

class WorkerRegisterService
{
    protected $model;
    public function __construct(Worker $worker)
    {
        $this->model = $worker;
    }

    public function validation($request)
    {
        $validator = Validator::make($request->all(),$request->rules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        return $validator;
    }

    public function tokenGenerator($email)
    {
        $token = substr(md5(rand(0,9) . $email . time()),0,32);
        $worker = $this->model->where('email', $email)->first();
        $worker->verification_token = $token;
        $worker->save();
        return$worker;
    }
    public function store($data,$request)
    {
        $worker = $this->model->create(array_merge(
            $data->validated(),
            [
                'password' => bcrypt($request->password),
                'photo' => $request->file('photo')->store('img/workers'),
            ]
        ));
        return $worker->email;
    }

    public function sendEmail()
    {

    }

    public function register($request)
    {
        try {
            DB::beginTransaction();
            $validator = $this->validation($request);
            $email = $this->store($validator,$request);
            $storeToken = $this->tokenGenerator($email);
            $this->sendEmail();
            DB::commit();
            return response()->json([
                'message'=>'account has been created check your email'
            ]);
        }catch (Exception $e){
            DB::rollBack();
        }

    }

}
