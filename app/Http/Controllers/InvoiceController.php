<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
        $sqldata = Order::with('customer', 'orderDetail', 'user', 'transaction')->where('id', $order_id)->first();
        if ($sqldata) {
            $data = $sqldata->toArray();
            $data['customer']['current_due'] = $sqldata->customer->current_due;
            //$snappy = \WPDF::loadView('pdf.invoice-bill', $data);
            $snappy = \WPDF::loadView('pdf.sampletest');
            // $headerHtml = view()->make('pdf.wkpdf-header')->render();
            // $footerHtml = view()->make('pdf.wkpdf-footer')->render();
            // $snappy->setOption('header-html', $headerHtml);
            // $snappy->setOption('footer-html', $footerHtml);
            return $snappy->stream(date('Y-m-d-h:i:-a').'-invoice-bill.pdf');
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
