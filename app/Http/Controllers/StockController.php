<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Stock;
use Illuminate\Http\Request;
use Flash;
use Response;

class StockController extends AppBaseController
{
    /**
     * Display a listing of the Stock.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Stock $stocks */
        $stocks = Stock::all();

        return view('admin.stocks.index')
            ->with('stocks', $stocks);
    }

    /**
     * Show the form for creating a new Stock.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.stocks.create');
    }

    /**
     * Store a newly created Stock in storage.
     *
     * @param CreateStockRequest $request
     *
     * @return Response
     */
    public function store(CreateStockRequest $request)
    {
        $input = $request->all();

        /** @var Stock $stock */
        $stock = Stock::create($input);

        Flash::success('Stock saved successfully.');

        return redirect(route('admin.stocks.index'));
    }

    /**
     * Display the specified Stock.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Stock $stock */
        $stock = Stock::find($id);

        if (empty($stock)) {
            Flash::error('Stock not found');

            return redirect(route('admin.stocks.index'));
        }

        return view('admin.stocks.show')->with('stock', $stock);
    }

    /**
     * Show the form for editing the specified Stock.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Stock $stock */
        $stock = Stock::find($id);

        if (empty($stock)) {
            Flash::error('Stock not found');

            return redirect(route('admin.stocks.index'));
        }

        return view('admin.stocks.edit')->with('stock', $stock);
    }

    /**
     * Update the specified Stock in storage.
     *
     * @param int $id
     * @param UpdateStockRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateStockRequest $request)
    {
        /** @var Stock $stock */
        $stock = Stock::find($id);

        if (empty($stock)) {
            Flash::error('Stock not found');

            return redirect(route('admin.stocks.index'));
        }

        $stock->fill($request->all());
        $stock->save();

        Flash::success('Stock updated successfully.');

        return redirect(route('admin.stocks.index'));
    }

    /**
     * Remove the specified Stock from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Stock $stock */
        $stock = Stock::find($id);

        if (empty($stock)) {
            Flash::error('Stock not found');

            return redirect(route('admin.stocks.index'));
        }

        $stock->delete();

        Flash::success('Stock deleted successfully.');

        return redirect(route('admin.stocks.index'));
    }
}
