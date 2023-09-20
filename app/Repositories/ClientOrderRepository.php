<?php

namespace App\Repositories;

use App\Interfaces\CrudRepoInterface;
use App\Models\ClientOrder;

class ClientOrderRepository implements CrudRepoInterface
{
    public function store($request)
    {
        $data = $request->all();
        $client_id = auth()->guard('client')->id();
        if (ClientOrder::where('client_id',$client_id)->where('post_id',$data['post_id'])->exists()){
            return response()->json([
                "message"=>"cannot duplicate order",
            ],406);
        }
        $data['client_id'] = $client_id;
        $order = ClientOrder::create($data)->makehidden(['updated_at','created_at']);
        return response()->json([
            "message"=>"success",
            "order"=>$order,
        ]);
    }
}
