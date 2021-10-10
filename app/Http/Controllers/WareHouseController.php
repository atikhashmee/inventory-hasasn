<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWareHouseRequest;
use App\Http\Requests\UpdateWareHouseRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\WareHouse;
use Illuminate\Http\Request;
use Flash;
use Response;

class WareHouseController extends AppBaseController
{
    /**
     * Display a listing of the WareHouse.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var WareHouse $wareHouses */
        $wareHouses = WareHouse::select('ware_houses.*', 'SP.total_products')
        ->leftJoin(\DB::raw('(SELECT COUNT(shop_products.id) as total_products, warehouse_id FROM shop_products INNER JOIN products ON products.id = shop_products.product_id WHERE products.deleted_at IS NULL GROUP BY shop_products.warehouse_id) as SP'), 'SP.warehouse_id', '=', 'ware_houses.id')
        ->get();

        return view('admin.ware_houses.index')
            ->with('wareHouses', $wareHouses);
    }

    /**
     * Show the form for creating a new WareHouse.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.ware_houses.create');
    }

    /**
     * Store a newly created WareHouse in storage.
     *
     * @param CreateWareHouseRequest $request
     *
     * @return Response
     */
    public function store(CreateWareHouseRequest $request)
    {
        $input = $request->all();

        /** @var WareHouse $wareHouse */
        $wareHouse = WareHouse::create($input);

        Flash::success('Ware House saved successfully.');

        return redirect(route('admin.wareHouses.index'));
    }

    /**
     * Display the specified WareHouse.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var WareHouse $wareHouse */
        $wareHouse = WareHouse::find($id);

        if (empty($wareHouse)) {
            Flash::error('Ware House not found');

            return redirect(route('admin.wareHouses.index'));
        }

        return view('admin.ware_houses.show')->with('wareHouse', $wareHouse);
    }

    /**
     * Show the form for editing the specified WareHouse.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var WareHouse $wareHouse */
        $wareHouse = WareHouse::find($id);

        if (empty($wareHouse)) {
            Flash::error('Ware House not found');

            return redirect(route('admin.wareHouses.index'));
        }

        return view('admin.ware_houses.edit')->with('wareHouse', $wareHouse);
    }

    /**
     * Update the specified WareHouse in storage.
     *
     * @param int $id
     * @param UpdateWareHouseRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWareHouseRequest $request)
    {
        /** @var WareHouse $wareHouse */
        $wareHouse = WareHouse::find($id);

        if (empty($wareHouse)) {
            Flash::error('Ware House not found');

            return redirect(route('admin.wareHouses.index'));
        }

        $wareHouse->fill($request->all());
        $wareHouse->save();

        Flash::success('Ware House updated successfully.');

        return redirect(route('admin.wareHouses.index'));
    }

    /**
     * Remove the specified WareHouse from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var WareHouse $wareHouse */
        $wareHouse = WareHouse::find($id);

        if (empty($wareHouse)) {
            Flash::error('Ware House not found');

            return redirect(route('admin.wareHouses.index'));
        }

        $wareHouse->delete();

        Flash::success('Ware House deleted successfully.');

        return redirect(route('admin.wareHouses.index'));
    }
}
