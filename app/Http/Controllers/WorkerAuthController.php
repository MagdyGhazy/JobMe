<?php
namespace App\Http\Controllers;
use App\Http\Requests\Worker\WorkerLoginRequest;
use App\Http\Requests\Worker\WorkerRegisterRequest;
use App\Models\Worker;
use App\Services\WorkerServices\WorkerLoginService;
use App\Services\WorkerServices\WorkerRegisterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkerAuthController extends Controller
{

    protected $workerLoginService;
    protected $workerRegisterService;
    public function __construct(WorkerLoginService $workerLoginService,WorkerRegisterService $workerRegisterService) {
        $this->middleware('auth:worker', ['except' => ['login', 'register']]);
        $this->workerLoginService = $workerLoginService;
        $this->workerRegisterService = $workerRegisterService;
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
        return response()->json(auth()->guard('worker')->user());
    }

}
