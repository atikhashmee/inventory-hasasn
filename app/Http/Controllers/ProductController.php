<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Models\Brand;
use App\Models\Stock;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use App\Models\Menufacture;
use App\Models\ShopProduct;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ShopProductStock;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends AppBaseController
{
    public function getProductJson($id) {
        try {
            $product = Product::select('products.*', 'PP.all_price')
            ->leftJoin(\DB::raw('(SELECT GROUP_CONCAT(price) as all_price,product_id FROM stocks GROUP BY product_id) as PP'), 'PP.product_id', '=', 'products.id')
            ->where('id', $id)->first();
            if ($product) {
                return response()->json(['status'=>true, 'data'=>$product]);
            } else {
                return response()->json(['status'=>false, 'data'=>'Product is not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['status'=>false, 'data'=>$e->getMessage()]);
        }
    }

    public function getAllProductsSearchJson(Request $request) {
        try {
            $data['products'] = Product::where('name', 'LIKE', '%'.$request->term.'%')->get();
            return response()->json(['status'=> true, 'data'=>$data]);
        } catch (\Exception $e) {
            return response()->json(['status'=> false, 'data'=>$e->getMessage()]);
        }
    }
    /**
     * Display a listing of the Product.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $data = [];
        /** @var Product $products */
        $shop_id = request()->query('shop_id') !='' ? $request->shop_id : '';
        if ($user->role != 'admin') {
            $shop_id = $user->shop_id;
        }
       $product_sql = Product::select('products.*', 'countries.name as country_name')
        ->leftJoin('countries', 'countries.id', '=', 'products.origin');

        $product_sql->where(function($q) use($request) {
            if ($request->category_id) {
                $q->where('products.category_id', $request->category_id);
                
            }
            if ($request->brand_id) {
                $q->where('products.brand_id', $request->brand_id);
            }

            if ($request->menufacture_id) {
                $q->where('products.menufacture_id', $request->menufacture_id);
            }

            if ($request->origin_id) {
                $q->where('products.origin', $request->origin_id);
            }
            if ($request->search != '') {
                $q->where('products.name', 'LIKE', '%'.$request->search.'%');
            }
        });

        if (request()->query('warehouse_id') == '' && request()->query('shop_id') =='') {
            $product_sql->addSelect(\DB::raw("(IFNULL(TS.total_wareHouse_in, 0) + IFNULL(TS2.total_customer_in, 0)) - IFNULL(TS3.total_sell, 0) as quantity"));
            $product_sql->leftJoin(\DB::raw("(SELECT SUM(quantity) as total_wareHouse_in, product_id FROM stocks GROUP BY product_id) as TS"), "TS.product_id", "=", "products.id")
            ->leftJoin(\DB::raw("(SELECT SUM(quantity) as total_customer_in, product_id FROM shop_product_stocks WHERE type = 'user_transfer' GROUP BY product_id) as TS2"), "TS2.product_id", "=", "products.id")
            ->leftJoin(\DB::raw("(SELECT SUM(final_quantity) as total_sell, product_id FROM order_details GROUP BY product_id) as TS3"), "TS3.product_id", "=", "products.id");
        }
        
        if ($shop_id !='' ) {
            $product_sql->join('shop_products', function($q) use($shop_id) {
                $q->on('shop_products.product_id', '=', 'products.id');
                $q->where('shop_products.shop_id', $shop_id);
            });
            $product_sql->addSelect(\DB::raw('(IFNULL(spQ.shop_stock_in, 0) - IFNULL(spO.shop_stock_out, 0)) AS quantity')); 
            $product_sql->leftJoin(\DB::raw('(SELECT SUM(quantity) as shop_stock_in, product_id, shop_id FROM shop_product_stocks GROUP BY shop_id, product_id) as spQ'), function($q) use($shop_id) {
                $q->on('spQ.product_id', '=', 'products.id');
                $q->where('spQ.shop_id', $shop_id);
            })
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shop_stock_out, product_id, shop_id FROM shop_inventories GROUP BY shop_id, product_id) as spO'), function($q) use($shop_id) {
                $q->on('spO.product_id', '=', 'products.id');
                $q->where('spO.shop_id', $shop_id);
            });
        }

        if (request()->query('warehouse_id') != '') {
            $product_sql->addSelect(\DB::raw("(IFNULL(TS.total_supply_in, 0) - IFNULL(TS2.total_supply_out, 0)) as quantity"));

            $product_sql->join(\DB::raw('(SELECT COUNT(id) as total_products, product_id, warehouse_id FROM stocks GROUP BY product_id, warehouse_id) as STTOCK'), function($q) {
                $q->on('STTOCK.product_id', '=', 'products.id');
                $q->where('STTOCK.warehouse_id', request()->query('warehouse_id'));
            });

            $product_sql->leftJoin(\DB::raw("(SELECT SUM(quantity) as total_supply_in, product_id, warehouse_id FROM stocks GROUP BY product_id, warehouse_id) as TS"), function($q) {
                $q->on("TS.product_id", "=", "products.id");
                $q->where("TS.warehouse_id", request()->query('warehouse_id'));
            });
            $product_sql->leftJoin(\DB::raw("(SELECT SUM(quantity) as total_supply_out, product_id, warehouse_id FROM shop_product_stocks GROUP BY product_id, warehouse_id) as TS2"), function($q) {
                $q->on("TS2.product_id", "=", "products.id");
                $q->where("TS2.warehouse_id", request()->query('warehouse_id'));
            });
        }
        

        if ($user->role != 'admin') {
            $product_sql->where(function($qq) use($user) {
                $qq->where('products.user_id', $user->id);
                $qq->orWhere('shop_products.shop_id', $user->shop_id);
            });
        } 
        $products =   $product_sql->orderBy('name', 'ASC')->paginate(100);
        $serial   = pagiSerial($products, 100);
        $countryItems = Country::pluck('name','id')->toArray();
        $menufactures = Menufacture::pluck('name','id')->toArray();
        $brands = Brand::pluck('name','id')->toArray();
        $categoryItems = Category::with('nested', 'nested.nested')->where('parent_id', 0)->get()->toArray();
        $data['products'] = $products;
        $data['countries'] = $countryItems;
        $data['menufactures'] = $menufactures;
        $data['brands'] = $brands;
        $data['serial'] = $serial;
        $data['categoryItems'] = $categoryItems;
        return view('admin.products.index', $data);
    }

    /**
     * Show the form for creating a new Product.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created Product in storage.
     *
     * @param CreateProductRequest $request
     *
     * @return Response
     */
    public function store(CreateProductRequest $request)
    {
        try {
            \DB::beginTransaction();
            $input = $request->all();
            $user = auth()->user();

            /** @var Product $product */

            if ($request->hasFile('feature_image')) {
                $image      = $request->file('feature_image');
                $fileName   = time() . '.' . $image->getClientOriginalExtension();

                $img = Image::make($image->getRealPath());
                $img->resize(120, 120, function ($constraint) {
                    $constraint->aspectRatio();                 
                });

                $img->stream(); // <-- Key point
                $input['feature_image'] = $fileName;
                Storage::disk('public_uploads')->put('products'.'/'.$fileName, $img);
            }

            $input['user_id'] = $user->id;
            $product = Product::create($input);
            if ($product) {
                if (isset($input["distribution_required"]) && $input["distribution_required"] == 1) {
                    $input = $request->all();
                    $input['user_id'] = $user->id;
                    if ($user->role == 'admin') {
                        $stock = Stock::create([
                            "product_id" => $product->id,
                            "supplier_id" => $input["supplier_id"],
                            "warehouse_id" => $input["warehouse_id"],
                            "user_id" => $input["user_id"],
                            "sku" => Str::random(6),
                            "selling_price" => 0,
                            "old_price" => 0,
                            "price" => $input["purchase_price"],
                            "quantity" => $input["purchase_quantity"],
                        ]); 
                        if (gettype($input["shop_id"]) == "array") {
                            foreach ($input["shop_id"] as $shop_id) {
                                ShopProduct::updateOrCreate(
                                    [
                                        'product_id' => $product->id,
                                        'shop_id' => $shop_id,
                                    ],
                                    [
                                    'shop_id' => $shop_id,
                                    'product_id' => $product->id,
                                    'quantity' => 0,
                                    'price' => 0,
                                ]);
                                $stock = ShopProductStock::create([
                                    'stock_id' => $stock->id, 
                                    'warehouse_id' => $input["warehouse_id"], 
                                    'user_id' => $user->id,
                                    'supplier_id' =>  $input["supplier_id"],
                                    'shop_id' => $shop_id, 
                                    'product_id' =>  $product->id,
                                    'quantity' => $input['stock_quantity'],
                                    'price' => $input['ad_selling_price'],
                                    'type' => 'warehouse_transfer',
                                ]);
                            }
                        }
                    } else {
                        ShopProduct::updateOrCreate(
                            [
                                'product_id' => $product->id,
                                'shop_id' => $user->shop_id,
                            ],
                            [
                            'shop_id' => $user->shop_id,
                            'product_id' => $product->id,
                            'quantity' => 0,
                            'price' => 0,
                        ]);
                        $stock = ShopProductStock::create([
                            'user_id' => $user->id,
                            'shop_id' => $user->shop_id, 
                            'product_id' =>  $product->id,
                            'supplier_id' =>  $input["supplier_id"],
                            'quantity' => $input['stock_quantity'],
                            'type' => 'user_transfer',
                            'price' => $input['selling_price']
                        ]);
                    }
                    
                    if ($stock) {
                        Product::where('id', $stock->product_id)->update(['product_cost' => $stock->price]);
                    }
                }
            }

            \DB::commit();
            Flash::success('Product saved successfully.');

            return redirect(route('admin.products.index'));
        } catch (\Exception $e) {
            \DB::rollback();
            Flash::error($e->getMessage());
            return redirect(route('admin.products.index'));
        }
        
    }

    /**
     * Display the specified Product.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Product $product */
        $product = Product::find($id);

        if (empty($product)) {
            Flash::error('Product not found');
            return redirect(route('admin.products.index'));
        }
        return view('admin.products.show')->with('product', $product);
    
    }

    /**
     * Show the form for editing the specified Product.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Product $product */
        $product = Product::find($id);

        if (empty($product)) {
            Flash::error('Product not found');

            return redirect(route('admin.products.index'));
        }

        return view('admin.products.edit')->with('product', $product);
    }

    /**
     * Update the specified Product in storage.
     *
     * @param int $id
     * @param UpdateProductRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductRequest $request)
    {
        /** @var Product $product */
        $product = Product::find($id);

        if (empty($product)) {
            Flash::error('Product not found');

            return redirect(route('admin.products.index'));
        }
        $data = $request->all();
        if ($request->hasFile('feature_image')) {
            $image      = $request->file('feature_image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $img->resize(120, 120, function ($constraint) {
                $constraint->aspectRatio();                 
            });

            $img->stream(); // <-- Key point
            $data['feature_image'] = $fileName;
            if (file_exists(public_path().'/uploads/products/'.$product->feature_image) && $product->feature_image) {
                unlink(public_path().'/uploads/products/'.$product->feature_image);
            }
            Storage::disk('public_uploads')->put('products'.'/'.$fileName, $img, 'public');
        }
        $product->fill($data);
        $product->save();

        Flash::success('Product updated successfully.');
        return redirect(route('admin.products.index'));
    }

    /**
     * Remove the specified Product from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Product $product */
        $product = Product::find($id);

        if (empty($product)) {
            Flash::error('Product not found');
            return redirect(route('admin.products.index'));
        }

        $product->delete();
        Flash::success('Product deleted successfully.');
        return redirect(route('admin.products.index'));
    }
}
