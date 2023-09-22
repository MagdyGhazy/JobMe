<?php
namespace App\Http\Controllers;
use App\Http\Requests\Worker\WorkerLoginRequest;
use App\Http\Requests\Worker\WorkerRegisterRequest;
use App\Models\Worker;
use App\Services\WorkerServices\WorkerLoginService;
use App\Services\WorkerServices\WorkerProfileService;
use App\Services\WorkerServices\WorkerRegisterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkerAuthController extends Controller
{

    protected $workerLoginService;
    protected $workerRegisterService;
    protected $workerProfileService;
    public function __construct(WorkerLoginService $workerLoginService,WorkerRegisterService $workerRegisterService,WorkerProfileService $workerProfileService) {
        $this->middleware('auth:worker', ['except' => ['login', 'register']]);
        $this->workerLoginService = $workerLoginService;
        $this->workerRegisterService = $workerRegisterService;
        $this->workerProfileService = $workerProfileService;
    }

    public function login(WorkerLoginRequest $request){
        return $this->workerLoginService->login($request);
    }

    public function register(WorkerRegisterRequest $request) {
        return $this->workerRegisterService->register($request);
    }

    public function logout() {
        auth()->guard('worker')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh() {
        return $this->createNewToken(auth()->guard('worker')->refresh());
    }

    public function userProfile() {
        return $this->workerProfileService->userProfile();
    }

    public function edit() {
        return response()->json([
           "worker" => Worker::find(auth()->guard('worker')->id())
        ]);
    }

    public function update() {
        return ($this->workerProfileService)->userProfile();
    }

}
