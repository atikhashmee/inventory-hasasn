<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Models\Product;
use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Models\QuotationItem;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;

class QuotationController extends AppBaseController
{
    /**
     * Display a listing of the Quotation.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Quotation $quotations */
        $quotations = Quotation::orderBy('id', 'DESC')->paginate(50);

        $serial = pagiSerial($quotations, 50);
        return view('admin.quotations.index')
            ->with('serial', $serial)
            ->with('quotations', $quotations);
    }

    /**
     * Show the form for creating a new Quotation.
     *
     * @return Response
     */
    public function create()
    {
        $data['products'] = Product::select('products.*')
        ->with('brand', 'origin')
        ->get();
        return view('admin.quotations.create', $data);
    }

    /**
     * Store a newly created Quotation in storage.
     *
     * @param CreateQuotationRequest $request
     *
     * @return Response
     */
    public function store(CreateQuotationRequest $request)
    {
        try {
            \DB::beginTransaction();
            $input = $request->all();
            /** @var Quotation $quotation */
            $quotation = Quotation::create($input);
            if (count($input['product_id']) > 0) {
                for ($i=0; $i < count($input['product_id']); $i++) { 
                    QuotationItem::create([
                        'quotation_id' => $quotation->id,
                        'quantity_unit_id' => $input['unit'][$i] == 0 ? null : $input['unit'][$i],
                        'item_name' => $input['product_names'][$i],
                        'brand' => $input['brand_name'][$i],
                        'model' => $input['model'][$i]??'',
                        'origin' => $input['origin'][$i],
                        'quantity' => $input['quantity'][$i],
                        'unit_price' => $input['unit_price'][$i],
                        'total_price' => $input['total_price'][$i],
                    ]);
                }
            }
            Flash::success('Quotation saved successfully.');
            \DB::commit();
            return redirect(route('admin.quotations.index'));
        } catch (\Exception $e) {
            \DB::rollBack();
            Flash::error($e->getMessage());
            return redirect(route('admin.quotations.create'));
        }
       
    }

    /**
     * Display the specified Quotation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Quotation $quotation */
        $quotation = Quotation::with('items')->find($id);

        if (empty($quotation)) {
            Flash::error('Quotation not found');
            return redirect(route('admin.quotations.index'));
        }
        return view('admin.quotations.show')->with('quotation', $quotation);
    }

    /**
     * Show the form for editing the specified Quotation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Quotation $quotation */
        $data['quotation'] = Quotation::with('items')->where('id', $id)->first();
        $data['products'] = Product::select('products.*')
        ->with('brand', 'origin')
        ->get();
        if (empty($data['quotation'])) {
            Flash::error('Quotation not found');
            return redirect(route('admin.quotations.index'));
        }

        return view('admin.quotations.edit', $data);
    }

    /**
     * Update the specified Quotation in storage.
     *
     * @param int $id
     * @param UpdateQuotationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQuotationRequest $request)
    {
        try {
            \DB::beginTransaction();
            $quotation = Quotation::find($id);
            if (empty($quotation)) {
                Flash::error('Quotation not found');
                return redirect(route('admin.quotations.index'));
            }
            $quotation->fill($request->all());
            $quotation->save();
            QuotationItem::where('quotation_id', $quotation->id)->delete();
            $input = $request->all();
            if (count($input['product_id']) > 0) {
                for ($i=0; $i < count($input['product_id']); $i++) { 
                    QuotationItem::create([
                        'quotation_id' => $quotation->id,
                        'item_name' => $input['product_names'][$i],
                        'brand' => $input['brand_name'][$i],
                        'model' => $input['model'][$i]??'',
                        'origin' => $input['origin'][$i],
                        'quantity' => $input['quantity'][$i],
                        'unit_price' => $input['unit_price'][$i],
                        'total_price' => $input['total_price'][$i],
                    ]);
                }
            }

            Flash::success('Quotation updated successfully.');
            \DB::commit();
            return redirect(route('admin.quotations.index'));
        } catch (\Exception $e) {
            \DB::rollBack();
            Flash::error($e->getMessage());
            return redirect(route('admin.quotations.index'));
        }
    }

    /**
     * Remove the specified Quotation from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Quotation $quotation */
        $quotation = Quotation::find($id);

        if (empty($quotation)) {
            Flash::error('Quotation not found');

            return redirect(route('admin.quotations.index'));
        }

        $quotation->delete();

        Flash::success('Quotation deleted successfully.');

        return redirect(route('admin.quotations.index'));
    }
}
