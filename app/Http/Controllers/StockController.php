<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Requests\CreateStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Http\Controllers\AppBaseController;

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
        $user = auth()->user();
        /** @var Stock $stocks */
        $stocks = Stock::where(function($q) use($request) {
            if (request()->query('start')!='' && request()->query('end')!='') {
                $q->whereBetween('created_at', [request()->query('start'),  request()->query('end')]);
            }

            if (!empty($request->supplier_id)) {
                $q->where('supplier_id', $request->supplier_id);
            }
            if (!empty($request->product_id)) {
                $q->where('product_id', $request->product_id);
            }

        })
        ->where('user_id', $user->id)
        ->orderBy('id', 'DESC')->paginate(50);
        
        $serial = pagiSerial($stocks, 50);
        $suppliers = Supplier::get();
        
        if ($user->role == 'admin') {
            $products = Product::get();
            return view('admin.stocks.index')
            ->with('stocks', $stocks)
            ->with('suppliers', $suppliers)
            ->with('serial', $serial)
            ->with('products', $products);
        } else {
            $products = Product::where('user_id', $user->id)->get();
            return view('user.stocks.index')
            ->with('stocks', $stocks)
            ->with('suppliers', $suppliers)
            ->with('serial', $serial)
            ->with('products', $products);
        }
       
    }

    /**
     * Show the form for creating a new Stock.
     *
     * @return Response
     */
    public function create()
    {
        $user = auth()->user();
        if ($user->role == 'admin') {
            return view('admin.stocks.create');
        } else {
            return view('user.stocks.create');
        }
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
        $user = auth()->user();

        /** @var Stock $stock */
        
        $input['user_id'] = $user->id;
        $stock = Stock::create($input);

        if ($stock) {
            Product::where('id', $stock->product_id)->update(['product_cost' => $stock->price]);
        }

        Flash::success('Stock saved successfully.');
        if ($user->role == 'admin') {
            return redirect(route('admin.stocks.index'));
        } else {
            return redirect(route('user.stocks.index'));
        }
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
        $user = auth()->user();
        $stock = Stock::find($id);

        if (empty($stock)) {
            Flash::error('Stock not found');

            return redirect(route('admin.stocks.index'));
        }
        if ($user->role == 'admin') {
            return view('admin.stocks.show')->with('stock', $stock);
        } else {
            return view('user.stocks.show')->with('stock', $stock);
        }
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
        $user = auth()->user();

        if (empty($stock)) {
            Flash::error('Stock not found');

            return redirect(route('admin.stocks.index'));
        }
        if ($user->role == 'admin') {
            return view('admin.stocks.edit')->with('stock', $stock);
        } else {
            return view('user.stocks.edit')->with('stock', $stock);
        }

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
        $user = auth()->user();

        if (empty($stock)) {
            Flash::error('Stock not found');

            return redirect(route('admin.stocks.index'));
        }

        $stock->fill($request->all());
        $stock->save();

        Flash::success('Stock updated successfully.');

        if ($user->role == 'admin') {
            return redirect(route('admin.stocks.index'));
        } else {
            return redirect(route('user.stocks.index'));
        }
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
        $user = auth()->user();

        if (empty($stock)) {
            Flash::error('Stock not found');

            return redirect(route('admin.stocks.index'));
        }

        $stock->delete();

        Flash::success('Stock deleted successfully.');
        if ($user->role == 'admin') {
            return redirect(route('admin.stocks.index'));
        } else {
            return redirect(route('user.stocks.index'));
        }
    }
}
