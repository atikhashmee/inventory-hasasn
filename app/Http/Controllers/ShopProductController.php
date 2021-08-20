<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
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
            $warehouse_id = 1;
            $data['shops'] =  Shop::where('status', 'active')->get();
            $product_sql =  Product::select('products.*', \DB::raw('(IFNULL(S.stock_quantity, 0) + IFNULL(products.quantity, 0)) -(IFNULL(SW.shops_stock_quantity, 0) + IFNULL(SP.shops_product_stock_quantity, 0)) AS warehouse_quantity'))
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as stock_quantity, product_id FROM `stocks` WHERE warehouse_id ='.$warehouse_id.' GROUP BY product_id) as S'), 'S.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shops_stock_quantity, product_id FROM `shop_product_stocks` WHERE warehouse_id ='.$warehouse_id.' GROUP BY product_id) as SW'), 'SW.product_id', '=', 'products.id');
            $product_sql->leftJoin(\DB::raw('(SELECT SUM(quantity) as shops_product_stock_quantity, product_id FROM `shop_products` GROUP BY product_id) as SP'), 'SP.product_id', '=', 'products.id');
            if ($request->shop_id) {
                $product_sql->addSelect(\DB::raw('(IFNULL(SW.shops_stock_quantity, 0) + IFNULL(SPP.shop_product_stock_quantity, 0)) AS shop_quantity'));
                $product_sql->addSelect(\DB::raw('shop_products.product_id as isAdded'));
                $product_sql->leftJoin(\DB::raw('(SELECT SUM(quantity) as shop_product_stock_quantity, product_id, shop_id FROM `shop_products` GROUP BY product_id, shop_id) as SPP'), function($q) use($request){
                    $q->on('SPP.product_id', '=', 'products.id');
                    $q->where('SPP.shop_id', $request->shop_id);
                });
                $product_sql->leftJoin('shop_products', function($q) use($request) {
                    $q->on('shop_products.product_id', '=', 'products.id');
                    $q->where('shop_products.shop_id', $request->shop_id);
                });
            }
    
            $data['products'] = $product_sql->get();
            return response()->json(['status'=>true, 'data'=>$data]);
        } catch (\Exception $e) {
            return response()->json(['status'=>false, 'data'=>$e->getMessage()]);
        }
    }

    public function getProductCurrentQuantity($product_id, $shop_id) {
        try {
            $warehouse_id = 1;
            $product_sql =  Product::select('products.*', \DB::raw('(IFNULL(S.stock_quantity, 0) + IFNULL(products.quantity, 0)) -(IFNULL(SW.shops_stock_quantity, 0) + IFNULL(SP.shops_product_stock_quantity, 0)) AS warehouse_quantity'))
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as stock_quantity, product_id FROM `stocks` WHERE warehouse_id ='.$warehouse_id.' GROUP BY product_id) as S'), 'S.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shops_stock_quantity, product_id FROM `shop_product_stocks` WHERE warehouse_id ='.$warehouse_id.' GROUP BY product_id) as SW'), 'SW.product_id', '=', 'products.id');
            $product_sql->leftJoin(\DB::raw('(SELECT SUM(quantity) as shops_product_stock_quantity, product_id FROM `shop_products` GROUP BY product_id) as SP'), 'SP.product_id', '=', 'products.id');
            if ($shop_id) {
                $product_sql->addSelect(\DB::raw('(IFNULL(SW.shops_stock_quantity, 0) + IFNULL(SPP.shop_product_stock_quantity, 0)) AS shop_quantity'));
                $product_sql->addSelect(\DB::raw('shop_products.product_id as isAdded'));
                $product_sql->leftJoin(\DB::raw('(SELECT SUM(quantity) as shop_product_stock_quantity, product_id, shop_id FROM `shop_products` GROUP BY product_id, shop_id) as SPP'), function($q) use($product_id, $shop_id){
                    $q->on('SPP.product_id', '=', 'products.id');
                    $q->where('SPP.shop_id', $shop_id);
                });
                $product_sql->leftJoin('shop_products', function($q)  use($product_id, $shop_id) {
                    $q->on('shop_products.product_id', '=', 'products.id');
                    $q->where('shop_products.shop_id', $shop_id);
                });
            }
            $data= $product_sql->where('products.id', $product_id)->first();
            return response()->json(['status'=>true, 'data'=>$data]);
        } catch (\Exception $e) {
            return response()->json(['status'=>false, 'data'=>$e->getMessage()]);
        }
    }

    public function updateShopToShopProduct(Request $request) {
       try {
            $data = $request->all();
            ShopToShop::create([
                'shop_from'=> $data['shop_from'],
                'shop_to'=> $data['shop_to'],
                'product_id'=> $data['product_id'],
                'quantity'=> $data['quantity'],
                'price'=> $data['price'],
            ]);
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
            $warehouse_id = 1;
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
                            'price' => $product['new_price']??$product['price'],
                        ]);
                    } else {
                        ShopProduct::create([
                            'shop_id' => $data['shop_id'],
                            'product_id' => $product['id'],
                            'quantity' => $product['new_quantity']??0,
                            'price' => $product['new_price']??$product['price'],
                        ]);
                    }
                }
            }
            return response()->json(['status'=>true, 'data'=>null]);
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
