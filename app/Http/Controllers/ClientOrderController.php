<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\ClientOrederRequest;
use App\Models\ClientOrder;
use App\Repositories\ClientOrderRepository;
use Illuminate\Http\Request;

class ClientOrderController extends Controller
{
    protected $crudRepo;

    public function __construct(ClientOrderRepository $crudRepo)
    {
        $this->crudRepo = $crudRepo;
    }
    public function Order(ClientOrederRequest $request)
    {
      return  $this->crudRepo->store($request);
    }

    public function workerOrder()
    {
        $orders = ClientOrder::with('client','post')
            ->whereStatus('pending')
            ->whereHas('post',function ($query){
                $query->where('worker_id',auth()->guard('worker')->id());
            })
            ->get()
            ->makehidden(['updated_at','created_at','post_id','client_id']);
        return response()->json([
            "message"=>$orders,
        ]);
    }

    protected function updateStatus(Request $request)
    {
        $order = ClientOrder::find($request->order_id);
        $order->setAttribute('status',$request->status)->save();
        return response()->json([
            "message"=>"the post "."{$request->status}"
        ]);
    }
}
