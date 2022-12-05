<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Models\Brand;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use App\Models\Menufacture;
use Illuminate\Http\Request;
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
        $products =   $product_sql->orderBy('id', 'DESC')->paginate(100);
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
                if ($input[""]) {
                    # code...
                }
            }

            Flash::success('Product saved successfully.');

            return redirect(route('admin.products.index'));
        } catch (\Exception $e) {
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
