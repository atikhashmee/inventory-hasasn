<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Models\Product;
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
        /** @var Product $products */
       $product_sql = Product::select('products.*', 'countries.name as country_name')
        ->leftJoin('countries', 'countries.id', '=', 'products.origin');

        
        if (request()->query('warehouse_id') == '' && request()->query('shop_id') =='') {
            $product_sql->addSelect(\DB::raw("(IFNULL(TS.total_supply_in, 0) + IFNULL(TS1.total_supply_out_one, 0) + IFNULL(TS2.total_supply_out_two, 0)) as quantity"));
            $product_sql->leftJoin(\DB::raw("(SELECT SUM(quantity) as total_supply_in, product_id FROM stocks GROUP BY product_id) as TS"), "TS.product_id", "=", "products.id");
            $product_sql->leftJoin(\DB::raw("(SELECT SUM(quantity) as total_supply_out_one, product_id FROM shop_products GROUP BY product_id) as TS1"), "TS1.product_id", "=", "products.id");
            $product_sql->leftJoin(\DB::raw("(SELECT SUM(quantity) as total_supply_out_two, product_id FROM shop_product_stocks GROUP BY product_id) as TS2"), "TS2.product_id", "=", "products.id");
        }
        if (request()->query('shop_id')!='') {
            $product_sql->join('shop_products', function($q) {
                $q->on('shop_products.product_id', '=', 'products.id');
            });
            $product_sql->where('shop_products.shop_id', request()->query('shop_id'));
            
            $product_sql->addSelect(\DB::raw("( (IFNULL(TS1.total_in_one, 0) + IFNULL(TS2.total_in_two, 0)) - (IFNULL(TST.total_shop_tranfer, 0) + IFNULL(TS3.total_sell, 0))) as quantity")); 
            $product_sql->leftJoin(\DB::raw("(SELECT SUM(quantity) as total_shop_tranfer, product_id FROM shop_to_shops GROUP BY product_id) as TST"), "TST.product_id", "=", "products.id");
            $product_sql->leftJoin(\DB::raw("(SELECT SUM(quantity) as total_in_one, product_id FROM shop_products GROUP BY product_id) as TS1"), "TS1.product_id", "=", "products.id");
            $product_sql->leftJoin(\DB::raw("(SELECT SUM(quantity) as total_in_two, product_id FROM shop_product_stocks GROUP BY product_id) as TS2"), "TS2.product_id", "=", "products.id");
            $product_sql->leftJoin(\DB::raw("(SELECT SUM(final_quantity) as total_sell, product_id FROM order_details GROUP BY product_id) as TS3"), "TS3.product_id", "=", "products.id");
        }

        if (request()->query('warehouse_id') != '') {
            $product_sql->join('stocks', function($q) {
                $q->on('stocks.product_id', '=', 'products.id');
            });
            $product_sql->where('stocks.warehouse_id', request()->query('warehouse_id'));
            $product_sql->addSelect(\DB::raw("(IFNULL(TS.total_supply_in, 0) - (IFNULL(TS1.total_supply_out_one, 0) + IFNULL(TS2.total_supply_out_two, 0))) as quantity"));
            $product_sql->leftJoin(\DB::raw("(SELECT SUM(quantity) as total_supply_in, product_id FROM stocks GROUP BY product_id) as TS"), "TS.product_id", "=", "products.id");
            $product_sql->leftJoin(\DB::raw("(SELECT SUM(quantity) as total_supply_out_one, product_id FROM shop_products GROUP BY product_id) as TS1"), "TS1.product_id", "=", "products.id");
            $product_sql->leftJoin(\DB::raw("(SELECT SUM(quantity) as total_supply_out_two, product_id FROM shop_product_stocks GROUP BY product_id) as TS2"), "TS2.product_id", "=", "products.id");
        }

        //dd($product_sql->get()->toArray());
        $products =   $product_sql->orderBy('id', 'DESC')->paginate(10);

        return view('admin.products.index')
            ->with('products', $products);
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
            $product = Product::create($input);

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
