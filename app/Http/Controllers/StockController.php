<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\ShopProduct;
use Illuminate\Http\Request;
use App\Models\ShopProductStock;
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
        if ($user->role == 'admin') {
            $stocksql = Stock::where(function($q) use($request) {
                if (request()->query('start')!='' && request()->query('end')!='') {
                    $q->whereBetween('created_at', [request()->query('start'),  request()->query('end')]);
                }
    
                if (!empty($request->supplier_id)) {
                    $q->where('supplier_id', $request->supplier_id);
                }
                if (!empty($request->product_id)) {
                    $q->where('product_id', $request->product_id);
                }
    
            });
            
            $stocks = $stocksql->orderBy('id', 'DESC')->paginate(50);
            $products = Product::get();
        } else {
            $stocksql = ShopProductStock::where(function($q) use($request) {
                if (request()->query('start')!='' && request()->query('end')!='') {
                    $q->whereBetween('created_at', [request()->query('start'),  request()->query('end')]);
                }
    
                if (!empty($request->supplier_id)) {
                    $q->where('supplier_id', $request->supplier_id);
                }
                if (!empty($request->product_id)) {
                    $q->where('product_id', $request->product_id);
                }
            });
            $stocksql->where('type', 'user_transfer');
            $stocks = $stocksql->orderBy('id', 'DESC')->paginate(50);
            $products = Product::leftJoin('shop_products', 'shop_products.product_id', '=', 'products.id')
            ->where('products.user_id', $user->id)
            ->orWhere('shop_products.shop_id', $user->shop_id)
            ->get();
        }
        
        
        $serial = pagiSerial($stocks, 50);
        $suppliers = Supplier::get();
        
        return view('admin.stocks.index')
        ->with('stocks', $stocks)
        ->with('suppliers', $suppliers)
        ->with('user', $user)
        ->with('serial', $serial)
        ->with('products', $products);
       
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
            $productItems = Product::pluck('name','id')->toArray();
        } else {
            $productItems = Product::leftJoin('shop_products', 'shop_products.product_id', '=', 'products.id')
            ->where('products.user_id', $user->id)
            ->orWhere('shop_products.shop_id', $user->shop_id)
            ->pluck('products.name','products.id')->toArray();
        }
        return view('admin.stocks.create')->with('productItems', $productItems);
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

        if ($user->role == 'admin') {
            $stock = Stock::create($input);
        } else {
            ShopProduct::updateOrCreate(
                [
                    'product_id' => $input['product_id'],
                    'shop_id' => $user->shop_id
                ],
                [
                'shop_id' => $user->shop_id,
                'product_id' => $input['product_id'],
                'quantity' => 0,
                'price' => 0,
            ]);
            $stock = ShopProductStock::create([
                'user_id' => $user->id,
                'shop_id' => $user->shop_id, 
                'product_id' => $input['product_id'],
                'supplier_id' => $input['supplier_id'],
                'quantity' => $input['quantity'],
                'type' => 'user_transfer',
                'price' => $input['price']
            ]);
        }
        if ($stock) {
            Product::where('id', $stock->product_id)->update(['product_cost' => $stock->price]);
        }
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
