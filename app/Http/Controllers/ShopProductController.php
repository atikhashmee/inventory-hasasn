<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Stock;
use App\Models\Product;
use App\Models\WareHouse;
use App\Models\ShopProduct;
use Illuminate\Http\Request;
use App\Models\ShopInventory;
use App\Models\ShopProductStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\TransactionBeginning;

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
                \DB::raw('(IFNULL(S.stock_quantity, 0) - IFNULL(SWW.shops_stock_quantity_two, 0)) AS warehouse_quantity'),
                \DB::raw('shop_products.product_id as isAdded'),
                \DB::raw('(IFNULL(spQ.shop_stock_in, 0) - IFNULL(spO.shop_stock_out, 0)) AS shop_quantity')
                )
                ->leftJoin(\DB::raw('(SELECT SUM(quantity) as stock_quantity, product_id FROM `stocks` WHERE warehouse_id ='.$warehouse_id.' AND deleted_at is NULL GROUP BY product_id) as S'), 'S.product_id', '=', 'products.id')
                ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shops_stock_quantity_two, product_id FROM `shop_product_stocks` WHERE warehouse_id ='.$warehouse_id.' GROUP BY product_id) as SWW'), 'SWW.product_id', '=', 'products.id')
                
                ->leftJoin('shop_products', function($q) use($request) {
                    $q->on('shop_products.product_id', '=', 'products.id');
                    $q->where('shop_products.shop_id', $request->shop_id);
                })
                ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shop_stock_in, product_id, shop_id FROM shop_product_stocks GROUP BY shop_id, product_id) as spQ'), function($q) use($request) {
                    $q->on('spQ.product_id', '=', 'products.id');
                    $q->where('spQ.shop_id', $request->shop_id);
                })
                ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shop_stock_out, product_id, shop_id FROM shop_inventories GROUP BY shop_id, product_id) as spO'), function($q) use($request) {
                    $q->on('spO.product_id', '=', 'products.id');
                    $q->where('spO.shop_id', $request->shop_id);
                });
                $data['products'] = $product_sql->orderBy('shop_quantity', 'DESC')->get();
            }
            return response()->json(['status'=> true, 'data'=>$data]);
        } catch (\Exception $e) {
            return response()->json(['status'=> false, 'data'=>$e->getMessage()]);
        }
    }


    public function updateShopToShopProduct(Request $request) {
        dd($request->all());
       try {
            $user = auth()->user();
            $data = $request->all();
            ShopProduct::updateOrCreate(
                [
                    'product_id' => $data['product_id'],
                    'shop_id' => $data['shop_to']
                ],
                [
                'shop_id' => $data['shop_to'],
                'product_id' => $data['product_id'],
                'quantity' => 0,
                'price' => 0,
            ]);


            $settle_quantity = $data['quantity'];
            $stocks = ShopProductStock::select('shop_product_stocks.*', 'ST.total_stock_out', DB::raw('(shop_product_stocks.quantity - IFNULL(ST.total_stock_out, 0)) AS stockQty'))
            ->leftJoin(DB::raw("(SELECT SUM(quantity) as total_stock_out, stock_id FROM shop_inventories GROUP BY stock_id) as ST"), 'shop_product_stocks.id', '=', 'ST.stock_id')
            ->where('shop_id', $data['shop_from'])
            ->where('product_id', $data['product_id'])
            ->having('stockQty', '>', 0)
            ->get();

            foreach ($stocks as $key => $st) {
                $checkQty = ($settle_quantity - $st->stockQty);
                if ($checkQty > 0) {
                    $stQty = $st->stockQty;
                } else {
                    $stQty = $settle_quantity;
                }

                $shop_stock = ShopProductStock::create([
                    'user_id' => $user->id,
                    'shop_id' => $data['shop_to'], 
                    'product_id' => $data['product_id'],
                    'supplier_id' => $st->supplier_id,
                    'quantity' => $stQty,
                    'type' => 'shop_transfer',
                    'price' => 0,
                ]);

                if ($shop_stock) {
                    $stOut =  ShopInventory::create([
                        'type' => 'shop_transfer',
                        'transfer_id' => $shop_stock->id,
                        'stock_id' => $st->id,
                        'product_id' => $st->product_id,
                        'shop_id' =>  $st->shop_id,
                        'quantity' =>  $stQty
                    ]);
                }

                if ($stOut) {
                    $settle_quantity = $settle_quantity-$stOut->quantity;
                }

                if ($settle_quantity == 0) {
                    break;
                }
            }
        
            return response()->json(['status'=>true, 'data'=>$shop_stock]);
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
            \DB::beginTransaction();
            $data = $request->all();
            $warehouse_id = $request->warehouse_id;
            $products = json_decode($data['products'], true);
            $user = auth()->user();
            if (count($products) > 0) {
                foreach ($products as $product) {
                   $shopProduct = ShopProduct::where('shop_id', $data['shop_id'])->where('product_id', $product['id'])->first();
                    if (!$shopProduct) {
                        ShopProduct::create([
                            'warehouse_id' => $warehouse_id,
                            'shop_id' => $data['shop_id'],
                            'product_id' => $product['id'],
                            'quantity' => 0,
                            'price' => 0,
                        ]);
                    }
                    if (isset($product['new_quantity']) && $product['new_quantity'] > 0) {
                        $settle_quantity = $product['new_quantity']; 
                        $stocks = Stock::select('stocks.*', 'ST.total_stock_out', DB::raw('(stocks.quantity - IFNULL(ST.total_stock_out, 0)) AS stockQty'))                  
                        ->leftJoin(DB::raw("(SELECT SUM(quantity) as total_stock_out, stock_id FROM shop_product_stocks GROUP BY stock_id) as ST"), 'stocks.id', '=', 'ST.stock_id')
                        ->where('warehouse_id', $warehouse_id)
                        ->where('product_id', $product['id'])
                        ->having('stockQty', '>', 0)
                        ->get();
                        // if ($product['id'] == 150) {
                        //     dd($stocks->toArray(), 'stocksss..');
                        // }
                        foreach ($stocks as $key => $st) {
                            $checkQty = ($settle_quantity - $st->stockQty);
                            if ($checkQty > 0) {
                                $stQty = $st->stockQty;
                            } else {
                                $stQty = $settle_quantity;
                            }
                            $stOut = ShopProductStock::create([ 
                                'stock_id' => $st->id,
                                'warehouse_id' => $warehouse_id,
                                'user_id' => $user->id,
                                'shop_id' => $data['shop_id'],
                                'supplier_id' => $st->supplier_id,
                                'product_id' => $product['id'],
                                'quantity' => $stQty,
                                'type' => 'warehouse_transfer',
                                'price' => 0,
                            ]);

                            if ($stOut) {
                                $settle_quantity = $settle_quantity-$stOut->quantity;
                            }

                            if ($settle_quantity == 0) {
                                break;
                            }
                        }
                        if ($settle_quantity != 0) {
                            throw new \Exception($product['name']." Given Quantity exceeds stock", 1);
                        }
                        
                    }
                }
            }
            \DB::commit();
            return response()->json(['status'=>true, 'data'=>null]);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['status'=>false, 'data'=>$e->getMessage()]);
        }
    }


    public function getShopProducts($shop_id) {
       try {
        $data = [];
        $product_sql = Product::select('products.*', \DB::raw('(IFNULL(spQ.shop_stock_in, 0) - IFNULL(spO.shop_stock_out, 0)) AS available_quanity'))
            ->join('shop_products', 'shop_products.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shop_stock_in, product_id, shop_id FROM shop_product_stocks GROUP BY shop_id, product_id) as spQ'), function($q) use($shop_id) {
                $q->on('spQ.product_id', '=', 'products.id');
                $q->where('spQ.shop_id', $shop_id);
            })
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shop_stock_out, product_id, shop_id FROM shop_inventories GROUP BY shop_id, product_id) as spO'), function($q) use($shop_id) {
                $q->on('spO.product_id', '=', 'products.id');
                $q->where('spO.shop_id', $shop_id);
            })
            // ->leftJoin(\DB::raw('(SELECT SUM(quantity) as total_Stock, product_id FROM shop_product_stocks GROUP BY product_id) as ST'), 'ST.product_id', '=', 'products.id')
            // ->leftJoin(\DB::raw('(SELECT SUM(ODD.final_quantity) as total_out, ODD.product_id FROM order_details AS ODD LEFT JOIN orders ON ODD.order_id = orders.id WHERE orders.shop_id='.$shop_id.' GROUP BY ODD.product_id) as OD'), 'OD.product_id', '=', 'products.id')
            // ->leftJoin(\DB::raw('(SELECT SUM(quantity) as total_transfer, product_id FROM shop_to_shops WHERE shop_from='.$shop_id.' GROUP BY product_id) AS TT'), 'TT.product_id', '=', 'products.id')
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
