<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
use App\Models\WareHouse;
use App\Models\ShopToShop;
use App\Models\ShopProduct;
use Illuminate\Http\Request;
use App\Models\ShopProductStock;

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
            
            $data['shops'] =  Shop::where('status', 'active')->get();
            $data['warehosues'] =  WareHouse::get();
            $data['products'] = [];
            if ($request->shop_id !=null && $request->warehouse_id!=null) {
                $warehouse_id = $request->warehouse_id;
                $product_sql =  Product::select('products.*', 
                \DB::raw('IFNULL(S.stock_quantity, 0) - (IFNULL(SWW.shops_stock_quantity_two, 0) + IFNULL(SP.shops_product_stock_quantity, 0)) AS warehouse_quantity'),
                \DB::raw('shop_products.product_id as isAdded'),
                \DB::raw('((IFNULL(SW.shops_stock_quantity, 0) + IFNULL(SPP.shop_product_stock_quantity, 0) + IFNULL(TA.total_transfer_added, 0)) - (IFNULL(TT.total_transfer, 0) + IFNULL(OD.total_out, 0))) AS shop_quantity')
                )
                ->leftJoin(\DB::raw('(SELECT SUM(quantity) as stock_quantity, product_id FROM `stocks` WHERE warehouse_id ='.$warehouse_id.' AND deleted_at is NULL GROUP BY product_id) as S'), 'S.product_id', '=', 'products.id')
                ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shops_stock_quantity_two, product_id FROM `shop_product_stocks` WHERE warehouse_id ='.$warehouse_id.' GROUP BY product_id) as SWW'), 'SWW.product_id', '=', 'products.id')
                ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shops_stock_quantity, product_id FROM `shop_product_stocks` WHERE warehouse_id ='.$warehouse_id.' AND shop_id='.$request->shop_id.' GROUP BY product_id) as SW'), 'SW.product_id', '=', 'products.id')
                ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shops_product_stock_quantity, product_id FROM `shop_products` GROUP BY product_id) as SP'), 'SP.product_id', '=', 'products.id')
                ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shop_product_stock_quantity, product_id, shop_id FROM `shop_products` GROUP BY product_id, shop_id) as SPP'), function($q) use($request){
                    $q->on('SPP.product_id', '=', 'products.id');
                    $q->where('SPP.shop_id', $request->shop_id);
                })
                ->leftJoin('shop_products', function($q) use($request) {
                    $q->on('shop_products.product_id', '=', 'products.id');
                    $q->where('shop_products.shop_id', $request->shop_id);
                })
                
                ->leftJoin(\DB::raw('(SELECT SUM(ODD.final_quantity) as total_out, ODD.product_id FROM order_details AS ODD LEFT JOIN orders ON ODD.order_id = orders.id WHERE orders.shop_id='.$request->shop_id.' GROUP BY ODD.product_id) as OD'), 'OD.product_id', '=', 'products.id')
                ->leftJoin(\DB::raw('(SELECT SUM(quantity) as total_transfer, product_id FROM shop_to_shops WHERE shop_from='.$request->shop_id.' GROUP BY product_id) AS TT'), 'TT.product_id', '=', 'products.id')
                ->leftJoin(\DB::raw('(SELECT SUM(quantity) as total_transfer_added, product_id FROM shop_to_shops WHERE shop_to='.$request->shop_id.' GROUP BY product_id) AS TA'), 'TA.product_id', '=', 'products.id');
                $data['products'] = $product_sql->get();
            }
            return response()->json(['status'=> true, 'data'=>$data]);
        } catch (\Exception $e) {
            return response()->json(['status'=> false, 'data'=>$e->getMessage()]);
        }
    }


    public function updateShopToShopProduct(Request $request) {
       try {
            $data = $request->all();
            $shop_product = ShopProduct::where('product_id', $data['product_id'])->where('shop_id', $data['shop_to'])->first();
            if ($shop_product) {
                $shoptoshop = ShopToShop::create([
                    'shop_from'=> $data['shop_from'],
                    'shop_to'=> $data['shop_to'],
                    'product_id'=> $data['product_id'],
                    'quantity'=> $data['quantity'],
                    'price'=> 0,
                ]);
            } else {
                return response()->json(['status'=>false, 'data'=> 'This product is not available at shop to, please add it first']);
            }
        
            return response()->json(['status'=>true, 'data'=>$shoptoshop]);
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
        try {
            $data = $request->all();
            $warehouse_id = $request->warehouse_id;
            $products = json_decode($data['products'], true);
            if (count($products) > 0) {
                foreach ($products as $product) {
                   $shopProduct =  ShopProduct::where('shop_id', $data['shop_id'])
                    ->where('product_id', $product['id'])->first();
                    if ($shopProduct) {
                        ShopProductStock::create([
                            'warehouse_id' => $warehouse_id,
                            'shop_id' => $data['shop_id'],
                            'product_id' => $product['id'],
                            'quantity' => $product['new_quantity']??0,
                            'price' => 0,
                        ]);
                    } else {
                        ShopProduct::create([
                            'warehouse_id' => $warehouse_id,
                            'shop_id' => $data['shop_id'],
                            'product_id' => $product['id'],
                            'quantity' => $product['new_quantity']??0,
                            'price' => 0,
                        ]);
                    }
                }
            }
            return response()->json(['status'=>true, 'data'=>null]);
        } catch (\Exception $e) {
            return response()->json(['status'=>false, 'data'=>$e->getMessage()]);
        }
    }


    public function getShopProducts($shop_id) {
       try {
        $data = [];
        $product_sql = Product::select('products.*', \DB::raw('((IFNULL(shop_products.quantity, 0) + IFNULL(ST.total_Stock, 0)) - (IFNULL(OD.total_out, 0) + IFNULL(TT.total_transfer, 0))) as available_quanity'))
            ->join('shop_products', 'shop_products.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as total_Stock, product_id FROM shop_product_stocks GROUP BY product_id) as ST'), 'ST.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw('(SELECT SUM(ODD.final_quantity) as total_out, ODD.product_id FROM order_details AS ODD LEFT JOIN orders ON ODD.order_id = orders.id WHERE orders.shop_id='.$shop_id.' GROUP BY ODD.product_id) as OD'), 'OD.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as total_transfer, product_id FROM shop_to_shops WHERE shop_from='.$shop_id.' GROUP BY product_id) AS TT'), 'TT.product_id', '=', 'products.id')
            ->where('shop_products.shop_id', $shop_id);
        $data['products'] = $product_sql->get();
        return response()->json(['status'=>true, 'data'=>$data]);
        } catch (\Exception $e) {
            return response()->json(['status'=>false, 'data'=>$e->getMessage()]);
        }
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
