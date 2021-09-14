<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Order;
use App\Models\Challan;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function printInvoice($order_id) {
        $sqldata = Order::with('customer', 'orderDetail', 'orderDetail.unit', 'user', 'transaction', 'shop')
        ->where('id', $order_id)
        ->first();
        $shop = $sqldata->shop;
        if (file_exists(public_path().'/uploads/shops/'.$shop->image)  && $shop->image) {
            $shop->image_link = asset('/uploads/shops/'.$shop->image);
        } else {
            $shop->image_link = asset('assets/img/not-found.png');
        }
        if ($sqldata) {
            $data = $sqldata->toArray();
            $data['customer']['current_due'] = $sqldata->customer->current_due;
            $snappy = \WPDF::loadView('pdf.invoice-bill', $data);
            $headerHtml = view()->make('pdf.wkpdf-header', compact('shop'))->render();
            $footerHtml = view()->make('pdf.wkpdf-footer')->render();
            $snappy->setOption('header-html', $headerHtml);
            $snappy->setOption('footer-html', $footerHtml);
            return $snappy->inline(date('Y-m-d-h:i:-a').'-invoice-bill.pdf');
        } else {
            return redirect()->back()->withError('Nothing found');
        }
    }

    public function printChallan($order_id) {
        $sqldata = Order::with('customer', 'orderDetail', 'orderDetail.unit', 'user', 'transaction', 'shop')->where('id', $order_id)->first();
        $shop = $sqldata->shop;
        if (file_exists(public_path().'/uploads/shops/'.$shop->image)  && $shop->image) {
            $shop->image_link = asset('/uploads/shops/'.$shop->image);
        } else {
            $shop->image_link = asset('assets/img/not-found.png');
        }
        if ($sqldata) {
            $data = $sqldata->toArray();
            $data['customer']['current_due'] = $sqldata->customer->current_due;
            $snappy = \WPDF::loadView('pdf.challan', $data);
            $headerHtml = view()->make('pdf.wkpdf-header', compact('shop'))->render();
            $footerHtml = view()->make('pdf.wkpdf-footer')->render();
            $snappy->setOption('header-html', $headerHtml);
            $snappy->setOption('footer-html', $footerHtml);
            return $snappy->inline(date('Y-m-d-h:i:-a').'-challan.pdf');
        } else {
            return redirect()->back()->withError('Nothing found');
        }
    }

    public function printChallanCondition($challan_id) {
        $sqldata = Challan::with('customer', 'unit')->where('id', $challan_id)
        ->first();
        $shop = Shop::where('id', $sqldata->shop_id)->where('status', 'active')->first();
        if (file_exists(public_path().'/uploads/shops/'.$shop->image)  && $shop->image) {
            $shop->image_link = asset('/uploads/shops/'.$shop->image);
        } else {
            $shop->image_link = asset('assets/img/not-found.png');
        }
        if ($sqldata) {
            $data = $sqldata->toArray();
            $snappy = \WPDF::loadView('pdf.challan-conditioned', $data);
            $headerHtml = view()->make('pdf.wkpdf-header', compact('shop'))->render();
            $footerHtml = view()->make('pdf.wkpdf-footer')->render();
            $snappy->setOption('header-html', $headerHtml);
            $snappy->setOption('footer-html', $footerHtml);
            return $snappy->inline(date('Y-m-d-h:i:-a').'-challan-conditioned.pdf');
        } else {
            return redirect()->back()->withError('Nothing found');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
