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
        $products = Product::select('products.*', 'countries.name as country_name')
        ->leftJoin('countries', 'countries.id', '=', 'products.origin')
        ->get();

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
