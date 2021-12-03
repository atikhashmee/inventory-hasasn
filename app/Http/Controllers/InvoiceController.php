<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Order;
use App\Models\Challan;
use App\Models\Quotation;
use Endroid\QrCode\QrCode;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Endroid\QrCode\Color\Color;
use NumberToWords\NumberToWords;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class InvoiceController extends Controller
{
    public function index()
    {
        //
    }

    public function qrCodeGenerator() {
        $qr = QrCode::create("BEGIN:VCARD
        VERSION:3.0
        N:Doe;John;
        TEL;TYPE=work,VOICE:123456789
        EMAIL:john@doe.com
        ORG:Code Boxx
        TITLE:Crash Test Dummy
        URL:https://code-boxx.com
        VERSION:3.0
        END:VCARD");
        // (B1) CORRECTION LEVEL
        $qr->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh());
        // (B2) SIZE & MARGIN
        $qr->setSize(175);
        $qr->setMargin(0);
        // (B3) COLORS
        // $qr->setForegroundColor(new Color(255, 10, 0));
        // $qr->setBackgroundColor(new Color(255, 255, 255));
        return (new PngWriter())->write($qr);
    }

    public function printInvoice($order_id) {
        $numberToWords = new NumberToWords();
          // build a new number transformer using the RFC 3066 language identifier
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $sqldata = Order::with('customer', 'orderDetail', 'orderDetail.unit', 'user', 'transaction', 'shop')->where('id', $order_id)->first();
        $shop = $sqldata->shop;
        if (file_exists(public_path().'/uploads/shops/'.$shop->image)  && $shop->image) {
            $shop->image_link = asset('/uploads/shops/'.$shop->image);
        } else {
            $shop->image_link = asset('assets/img/not-found.png');
        }
        if ($sqldata) {
            $data = $sqldata->toArray();
         
            $data['amount_in_total_words'] = $numberTransformer;
            $qrCode = null; // $this->qrCodeGenerator();
            $data['customer']['current_due'] = $sqldata->customer->current_due;
            $snappy = \WPDF::loadView('pdf.invoice-bill', $data);
            $headerHtml = view()->make('pdf.wkpdf-header', compact('shop', 'qrCode'))->render();
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
            $qrCode = null; // $this->qrCodeGenerator();
            $headerHtml = view()->make('pdf.wkpdf-header', compact('shop', 'qrCode'))->render();
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
            $qrCode = null; // $this->qrCodeGenerator();
            $snappy = \WPDF::loadView('pdf.challan-conditioned', $data);
            $headerHtml = view()->make('pdf.wkpdf-header', compact('shop', 'qrCode'))->render();
            $footerHtml = view()->make('pdf.wkpdf-footer')->render();
            $snappy->setOption('header-html', $headerHtml);
            $snappy->setOption('footer-html', $footerHtml);
            return $snappy->inline(date('Y-m-d-h:i:-a').'-challan-conditioned.pdf');
        } else {
            return redirect()->back()->withError('Nothing found');
        }
    }

    public function printQuotation($quotation_id) {
        $numberToWords = new NumberToWords();
        // build a new number transformer using the RFC 3066 language identifier
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $sqldata = Quotation::with('items')->where('id', $quotation_id)->first();
        $shop = Shop::where('id', $sqldata->shop_id)->where('status', 'active')->first();
        if (file_exists(public_path().'/uploads/shops/'.$shop->image)  && $shop->image) {
            $shop->image_link = asset('/uploads/shops/'.$shop->image);
        } else {
            $shop->image_link = asset('assets/img/not-found.png');
        }
        if ($sqldata) {
            $totalSum = $sqldata->items->sum('total_price');
            $data = $sqldata->toArray();
            $qrCode = null; // $this->qrCodeGenerator();
            $data['amount_in_total'] = $totalSum;
            $data['amount_in_total_words'] = $numberTransformer->toWords($totalSum);
            $snappy = \WPDF::loadView('pdf.quotation', $data);
            $headerHtml = view()->make('pdf.wkpdf-header', compact('shop', 'qrCode'))->render();
            $footerHtml = view()->make('pdf.wkpdf-footer')->render();
            $snappy->setOption('header-html', $headerHtml);
            $snappy->setOption('footer-html', $footerHtml);
            return $snappy->inline(date('Y-m-d-h:i:-a').'-quotation.pdf');
        } else {
            return redirect()->back()->withError('Nothing found');
        }
    }

    public function printWarentySerails($order_id) {

        $sqldata = Order::with('customer', 'orderDetail', 'orderDetail.warenty', 'user', 'shop')->where('id', $order_id)->first();
        $shop = $sqldata->shop;
        if (file_exists(public_path().'/uploads/shops/'.$shop->image)  && $shop->image) {
            $shop->image_link = asset('/uploads/shops/'.$shop->image);
        } else {
            $shop->image_link = asset('assets/img/not-found.png');
        }
        if ($sqldata) {
            $data = $sqldata->toArray();
            $order_details = OrderDetail::with('warenty')->select('order_details.*')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->where('order_details.order_id', $order_id)
            ->whereNotNull('products.warenty_duration')
            ->get();
            $serials = [];
            $eachItem = [];
            if (count($order_details) > 0) {
                foreach ($order_details as $detail) {
                    $eachItem['product_name'] = $detail->product_name;
                    $eachItem['serial_items'] = [];
                    if (count($detail->warenty) > 0) {
                        foreach ($detail->warenty as $warenty) {
                            if ($warenty->serial_number) {
                                $eachItem['serial_items'][$warenty->quanitty_serial_number] = $warenty->serial_number;
                            }
                        }
                    }
                }
                $serials[] = $eachItem;
            }
            $qrCode = null; // $this->qrCodeGenerator();
            $data['customer']['current_due'] = $sqldata->customer->current_due;
            $data['serials'] = $serials;
            $snappy = \WPDF::loadView('pdf.warenty-serial-numbers', $data);
            $headerHtml = view()->make('pdf.wkpdf-header', compact('shop', 'qrCode'))->render();
            $footerHtml = view()->make('pdf.wkpdf-footer')->render();
            $snappy->setOption('header-html', $headerHtml);
            $snappy->setOption('footer-html', $footerHtml);
            return $snappy->inline(date('Y-m-d-h:i:-a').'-invoice-bill.pdf');
        } else {
            return redirect()->back()->withError('Nothing found');
        }
    }
}
