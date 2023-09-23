<?php

namespace App\Services\WorkerServices;

use App\Models\Worker;
use Illuminate\Support\Facades\Validator;

class WorkerLoginService
{
    protected $worker;
    public function __construct(Worker $worker)
    {
     $this->worker = $worker;
    }

    public function validation($request)
    {
        $validator = Validator::make($request->all(),$request->rules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        return $validator;
    }

    public function isValidData($data)
    {
        if ($token = auth()->guard('worker')->attempt($data->validated())) {
            return $token;

        }
        return response()->json(['error' => 'Invalid Data'], 401);

    }

    public function getStatus($email)
    {
        $worker = $this->worker->where('email', $email)->first();
        return $worker->status;
    }

    public function isVerified($email)
    {
        $worker = $this->worker->where('email', $email)->first();
        $verified = $worker->verified_at;
        return $verified;
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->guard('worker')->user()
        ]);
    }

    public function login($request)
    {
        $validator = $this->validation($request);
        $token = $this->isValidData($validator);
        if ($this->isVerified($request->email) == null)
        {
            return response()->json(['message' => 'your account is not verified'], 422);
        }
        elseif($this->getStatus($request->email) == 0)
        {
            return response()->json(['message' => 'your account is pending'], 422);
        }
        return $this->createNewToken($token);
    }
}
