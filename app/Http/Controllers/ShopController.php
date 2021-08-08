<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Shop;
use Illuminate\Http\Request;
use Flash;
use Response;

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
        $shops = Shop::all();

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
        /** @var Shop $shop */
        $shop = Shop::find($id);

        if (empty($shop)) {
            Flash::error('Shop not found');

            return redirect(route('admin.shops.index'));
        }

        $shop->fill($request->all());
        $shop->save();

        Flash::success('Shop updated successfully.');

        return redirect(route('admin.shops.index'));
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
