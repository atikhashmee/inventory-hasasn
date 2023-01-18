<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Order;
use App\Models\Challan;
use App\Models\Quotation;
use Endroid\QrCode\QrCode;
use App\Models\OrderDetail;
use App\Models\Transaction;
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
        

        $sqldata = Order::with(['customer', 'orderDetail' => function($q) {
            $q->addSelect('order_details.*', 'countries.name as origin', 'brands.name as brand_name');
            $q->leftJoin('products', 'products.id', '=', 'order_details.product_id');
            $q->leftJoin('countries', 'countries.id', '=', 'products.origin');
            $q->leftJoin('brands', 'brands.id', '=', 'products.brand_id');
        }, 'orderDetail.unit', 'user', 'transaction', 'shop'])->where('id', $order_id)->first();
        $shop = $sqldata->shop;
        if (file_exists(public_path().'/uploads/shops/'.$shop->image)  && $shop->image) {
            $shop->image_link = asset('/uploads/shops/'.$shop->image);
        } else {
            $shop->image_link = asset('assets/img/not-found.png');
        }
        if ($sqldata) {
            $data = $sqldata->toArray();
            $totalDeposit = Transaction::where("type", "in")->where('customer_id', $data['customer_id'])->where(function($q) use($order_id) {
                $q->where('order_id', '!=', $order_id);
                $q->orWhereNull('order_id');
            })->groupBy('customer_id')->sum('amount');
            $totalWithdraw = Transaction::where("type", "out")->where('customer_id', $data['customer_id'])->where(function($q) use($order_id) {
                $q->where('order_id', '!=', $order_id);
                $q->orWhereNull('order_id');
            })->groupBy('customer_id')->sum('amount');

            $currentTotalDeposit = Transaction::where(["type" => "in",  "flag" => "payment"])->where('order_id', $data['id'])->groupBy('order_id')->sum('amount');
            $currentTotalWithdraw = Transaction::where(["type" => "out", "flag" => "refund"])->where('order_id', $data['id'])->groupBy('order_id')->sum('amount');
            $currentTotalAmountCollected = ($currentTotalDeposit - $currentTotalWithdraw);

            $currentTotalSales = Transaction::where(["type" => "out",  "flag" => "order_placed"])->where('order_id', $data['id'])->groupBy('order_id')->sum('amount');
            $currentTotalReturn = Transaction::where(["type" => "in", "flag" => "sell_return"])->where('order_id', $data['id'])->groupBy('order_id')->sum('amount');
            $currentTotalDue = ($currentTotalSales - $currentTotalReturn);
            $qrCode = null;
            $totalCurrentDue = ($totalWithdraw - $totalDeposit);
            $finalCurrentDue = $totalCurrentDue;
            $data['customer']['current_due'] = $finalCurrentDue;
            $data['today_sales'] = $currentTotalDue;
            $data['total_collected'] = $currentTotalAmountCollected;
            $data['net_outstanding'] = ($finalCurrentDue + $currentTotalDue) - $currentTotalAmountCollected;
            $orderNumber = $data['order_number'];
            list($dateStr, $randomeNumber) = explode('-', $orderNumber);
            $data['order_number'] = date("ymd", strtotime($data["created_at"]))."-".$randomeNumber; 
            $snappy = \WPDF::loadView('pdf.invoice-bill', $data);
            $headerHtml = view()->make('pdf.wkpdf-header', compact('shop', 'qrCode'))->render();
            $footerHtml = view()->make('pdf.wkpdf-footer')->render();
            $snappy->setOption('header-html', $headerHtml);
            if (env('DEMO_SHOW') != true) {
                $snappy->setOption('footer-html', $footerHtml);
            }
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
            $footer_precuation = true;
            $headerHtml = view()->make('pdf.wkpdf-header', compact('shop', 'qrCode'))->render();
            $footerHtml = view()->make('pdf.wkpdf-footer', compact('footer_precuation'))->render();
            $snappy->setOption('header-html', $headerHtml);
            if (env('DEMO_SHOW') != true) {
                $snappy->setOption('footer-html', $footerHtml);
            }
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
            $footer_precuation = true;
            $snappy = \WPDF::loadView('pdf.challan-conditioned', $data);
            $headerHtml = view()->make('pdf.wkpdf-header', compact('shop', 'qrCode'))->render();
            $footerHtml = view()->make('pdf.wkpdf-footer', compact('footer_precuation'))->render();
            $snappy->setOption('header-html', $headerHtml);
            if (env('DEMO_SHOW') != true) {
                $snappy->setOption('footer-html', $footerHtml);
            }
            return $snappy->inline(date('Y-m-d-h:i:-a').'-challan-conditioned.pdf');
        } else {
            return redirect()->back()->withError('Nothing found');
        }
    }

    public function printQuotation($quotation_id) {
        $sqldata = Quotation::with('items', 'items.unit')->where('id', $quotation_id)->first();
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
            $footer_precuation = true;
            $data['amount_in_total'] = $totalSum;
            $data['amount_in_total_words'] = numberToWord($totalSum);
            $snappy = \WPDF::loadView('pdf.quotation', $data);
            $headerHtml = view()->make('pdf.wkpdf-header', compact('shop', 'qrCode'))->render();
            $footerHtml = view()->make('pdf.wkpdf-footer', compact('footer_precuation'))->render();
            $snappy->setOption('header-html', $headerHtml);
            
            if (env('DEMO_SHOW') != true) {
                $snappy->setOption('footer-html', $footerHtml);
            }
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
                                $eachItem['serial_items'][$warenty->quanitty_serial_number]['s_number'] = $warenty->serial_number;
                                $effectiveDate = date('Y-m-d', strtotime("+".$detail->warenty_duration." months", strtotime($detail->created_at)));
                                $eachItem['serial_items'][$warenty->quanitty_serial_number]['warenty_preiod'] = $effectiveDate;
                            }
                        }
                    }
                    $serials[] = $eachItem;
                }
            }
            $qrCode = null; // $this->qrCodeGenerator();
            $data['customer']['current_due'] = $sqldata->customer->current_due;
            $data['serials'] = $serials;
            $footer_precuation = true;
            $snappy = \WPDF::loadView('pdf.warenty-serial-numbers', $data);
            $headerHtml = view()->make('pdf.wkpdf-header', compact('shop', 'qrCode'))->render();
            $footerHtml = view()->make('pdf.wkpdf-footer', compact('footer_precuation'))->render();
            $snappy->setOption('header-html', $headerHtml);
            if (env('DEMO_SHOW') != true) {
                $snappy->setOption('footer-html', $footerHtml);
            }
            return $snappy->inline(date('Y-m-d-h:i:-a').'-warenty-card.pdf');
        } else {
            return redirect()->back()->withError('Nothing found');
        }
    }
}
