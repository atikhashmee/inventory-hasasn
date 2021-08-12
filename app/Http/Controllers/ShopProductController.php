<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
use App\Models\ShopProduct;
use Illuminate\Http\Request;

class ShopProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.shop_products.index');
    }

    public function getResource(Request $request) {
        try {
            $warehouse_id = 1;
            $data['shops'] =  Shop::where('status', 'active')->get();
            $product_sql =  Product::select('products.*', \DB::raw('((IFNULL(S.stock_quantity, 0) + IFNULL(products.quantity, 0)) - IFNULL(SW.shops_stock_quantity,0)) as warehouse_quantity'))
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as stock_quantity, product_id FROM `stocks` WHERE warehouse_id ='.$warehouse_id.' GROUP BY product_id) as S'), 'S.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shops_stock_quantity, product_id FROM `shop_product_stocks` WHERE warehouse_id ='.$warehouse_id.' GROUP BY product_id) as SW'), 'SW.product_id', '=', 'products.id');
            if ($request->shop_id) {
                $product_sql->addSelect('SSW.shop_stock_quantity as shop_quantity');
                $product_sql->leftJoin(\DB::raw('(SELECT SUM(quantity) as shop_stock_quantity, product_id, shop_id FROM `shop_product_stocks` WHERE warehouse_id ='.$warehouse_id.' GROUP BY product_id, shop_id) as SSW'), 'SSW.product_id', '=', 'products.id');
                $product_sql->where('SSW.shop_id', $request->shop_id);
            }
            $data['products'] = $product_sql->get();
            return response()->json(['status'=>true, 'data'=>$data]);
        } catch (\Exception $e) {
            return response()->json(['status'=>false, 'data'=>$e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShopProduct  $shopProduct
     * @return \Illuminate\Http\Response
     */
    public function show(ShopProduct $shopProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopProduct  $shopProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopProduct $shopProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopProduct  $shopProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopProduct $shopProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopProduct  $shopProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopProduct $shopProduct)
    {
        //
    }
}
