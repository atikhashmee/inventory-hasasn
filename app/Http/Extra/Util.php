<?php
namespace App\Http\Extra;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ShopInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait Util {
    private $customer_types = [
        "Vendor",
        "Hospital/ Clinic",
        "Diagnostic center/ Lab",
        "Doctor",
        "Customer",
    ];

    public function deleteDraftOrders(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                "order_ids" => "required:array",
                "order_ids*.*" => "exists:orders,id",
            ]); 
            
            if ($validator->fails()) {
                throw new \Exception(json_encode($validator->getMessageBag()->all()), 1);
            }
            \DB::beginTransaction();
            $orderIds = $validator->validated()["order_ids"];
            $allOrder = Order::with("orderDetail", "orderDetail.inventory")->whereIn("id", $orderIds)->get();
            $orderDetailIds = [];
            $inventoryIds = [];
            if (!empty($allOrder)) {
                foreach ($allOrder as $order) {
                    if (!empty($order->orderDetail)) {
                        foreach ($order->orderDetail as $detail) {
                            $orderDetailIds[$detail->id] = $detail->id;
                            if (!empty($detail->inventory)) {
                                $inventoryIds[$detail->inventory->id] = $detail->inventory->id;
                            }
                        }
                    
                    }
                }
            }
            Order::whereIn("id", $orderIds)->delete();
            OrderDetail::whereIn("id", array_values($orderDetailIds))->delete();
            ShopInventory::whereIn("id", array_values($inventoryIds));
            \DB::commit();
            return response()->json([
                "status" => 1,
                "message" => "success",
                "data" => []
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                "status" => 0,
                "message" => $e->getMessage()." ".$e->getLine()
            ]);
        }
    }
}

?>