<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Flash;
use Response;

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
        $quotations = Quotation::all();

        return view('admin.quotations.index')
            ->with('quotations', $quotations);
    }

    /**
     * Show the form for creating a new Quotation.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.quotations.create');
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
        $input = $request->all();

        /** @var Quotation $quotation */
        $quotation = Quotation::create($input);

        Flash::success('Quotation saved successfully.');

        return redirect(route('admin.quotations.index'));
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
        $quotation = Quotation::find($id);

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
        $quotation = Quotation::find($id);

        if (empty($quotation)) {
            Flash::error('Quotation not found');

            return redirect(route('admin.quotations.index'));
        }

        return view('admin.quotations.edit')->with('quotation', $quotation);
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
        /** @var Quotation $quotation */
        $quotation = Quotation::find($id);

        if (empty($quotation)) {
            Flash::error('Quotation not found');

            return redirect(route('admin.quotations.index'));
        }

        $quotation->fill($request->all());
        $quotation->save();

        Flash::success('Quotation updated successfully.');

        return redirect(route('admin.quotations.index'));
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
