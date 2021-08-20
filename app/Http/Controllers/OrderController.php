<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
        return view('admin.orders.index');
    }

    public function create() {
        return view('admin.orders.create');
    }

    public function getResources() {
        try {
            $data = [];
            $data['products'] = Product::get();
            return response()->json(['status'=>true, 'data'=>$data]);
        } catch (\Exception $e) {
            return response()->json(['status'=>false, 'data'=>$e->getMessage()]);
        }
        return $data;
    }

}
