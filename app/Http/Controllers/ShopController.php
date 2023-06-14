<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateShopRequest;
use Intervention\Image\Facades\Image;
use App\Http\Requests\UpdateShopRequest;
use App\Http\Controllers\AppBaseController;

class ShopController extends AppBaseController
{
    /**
     * Display a listing of the Shop.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Shop $shops */
        $shops = Shop::select('shops.*', 'SP.total_products')
        ->leftJoin(\DB::raw('(SELECT COUNT(shop_products.id) as total_products, shop_id FROM shop_products INNER JOIN products ON products.id = shop_products.product_id WHERE products.deleted_at IS NULL GROUP BY shop_products.shop_id) as SP'), 'SP.shop_id', '=', 'shops.id')
        ->get();

        return view('admin.shops.index')
            ->with('shops', $shops);
    }

    /**
     * Show the form for creating a new Shop.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.shops.create');
    }

    /**
     * Store a newly created Shop in storage.
     *
     * @param CreateShopRequest $request
     *
     * @return Response
     */
    public function store(CreateShopRequest $request)
    {
        $input = $request->all();

        /** @var Shop $shop */
         if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $img->resize(120, 120, function ($constraint) {
                $constraint->aspectRatio();                 
            });

            $img->stream(); // <-- Key point
            $input['image'] = $fileName;
            Storage::disk('public_uploads')->put('shops'.'/'.$fileName, $img);
        }
        if ($request->hasFile('shop_name_image')) {
            $image      = $request->file('shop_name_image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $img->resize(120, 120, function ($constraint) {
                $constraint->aspectRatio();                 
            });

            $img->stream(); // <-- Key point
            $input['shop_logo_img'] = $fileName;
            Storage::disk('public_uploads')->put('shops'.'/'.$fileName, $img);
        }
        $shop = Shop::create($input);

        Flash::success('Shop saved successfully.');

        return redirect(route('admin.shops.index'));
    }

    /**
     * Display the specified Shop.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Shop $shop */
        $shop = Shop::find($id);

        if (empty($shop)) {
            Flash::error('Shop not found');

            return redirect(route('admin.shops.index'));
        }

        return view('admin.shops.show')->with('shop', $shop);
    }

    /**
     * Show the form for editing the specified Shop.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Shop $shop */
        $shop = Shop::find($id);

        if (empty($shop)) {
            Flash::error('Shop not found');

            return redirect(route('admin.shops.index'));
        }

        return view('admin.shops.edit')->with('shop', $shop);
    }

    /**
     * Update the specified Shop in storage.
     *
     * @param int $id
     * @param UpdateShopRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateShopRequest $request)
    {
        try {
            /** @var Shop $shop */
            $shop = Shop::find($id);

            if (empty($shop)) {
                Flash::error('Shop not found');

                return redirect(route('admin.shops.index'));
            }
            $data = $request->all();
            if ($request->hasFile('image')) {
                $image      = $request->file('image');
                $fileName   = time() . '.' . $image->getClientOriginalExtension();

                $img = Image::make($image->getRealPath());
                $img->resize(120, 120, function ($constraint) {
                    $constraint->aspectRatio();                 
                });

                $img->stream(); // <-- Key point
                $data['image'] = $fileName;
                // remove the previous one
                if (file_exists(public_path().'/uploads/shops/'.$shop->image)  && $shop->image) {
                    unlink(public_path().'/uploads/shops/'.$shop->image);
                }
                Storage::disk('public_uploads')->put('shops'.'/'.$fileName, $img);
            }
            if ($request->hasFile('shop_name_image')) {
                $image      = $request->file('shop_name_image');
                $fileName   = time() . '.' . $image->getClientOriginalExtension();

                $img = Image::make($image->getRealPath());
                $img->resize(120, 120, function ($constraint) {
                    $constraint->aspectRatio();                 
                });

                $img->stream(); // <-- Key point
                $data['shop_logo_img'] = $fileName;
                // remove the previous one
                if (file_exists(public_path().'/uploads/shops/'.$shop->shop_logo_img)  && $shop->shop_logo_img) {
                    unlink(public_path().'/uploads/shops/'.$shop->shop_logo_img);
                }
                Storage::disk('public_uploads')->put('shops'.'/'.$fileName, $img);
            }
            $shop->fill($data);
            $shop->save();
            Flash::success('Shop updated successfully.');
            return redirect(route('admin.shops.index'));
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
            return redirect(route('admin.shops.index'));
        }
       
    }

    /**
     * Remove the specified Shop from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Shop $shop */
        $shop = Shop::find($id);

        if (empty($shop)) {
            Flash::error('Shop not found');

            return redirect(route('admin.shops.index'));
        }

        $shop->delete();

        Flash::success('Shop deleted successfully.');

        return redirect(route('admin.shops.index'));
    }
}
