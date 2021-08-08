<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Flash;
use Response;

class SupplierController extends AppBaseController
{
    /**
     * Display a listing of the Supplier.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Supplier $suppliers */
        $suppliers = Supplier::all();

        return view('admin.suppliers.index')
            ->with('suppliers', $suppliers);
    }

    /**
     * Show the form for creating a new Supplier.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created Supplier in storage.
     *
     * @param CreateSupplierRequest $request
     *
     * @return Response
     */
    public function store(CreateSupplierRequest $request)
    {
        $input = $request->all();

        /** @var Supplier $supplier */
        $supplier = Supplier::create($input);

        Flash::success('Supplier saved successfully.');

        return redirect(route('admin.suppliers.index'));
    }

    /**
     * Display the specified Supplier.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Supplier $supplier */
        $supplier = Supplier::find($id);

        if (empty($supplier)) {
            Flash::error('Supplier not found');

            return redirect(route('admin.suppliers.index'));
        }

        return view('admin.suppliers.show')->with('supplier', $supplier);
    }

    /**
     * Show the form for editing the specified Supplier.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Supplier $supplier */
        $supplier = Supplier::find($id);

        if (empty($supplier)) {
            Flash::error('Supplier not found');

            return redirect(route('admin.suppliers.index'));
        }

        return view('admin.suppliers.edit')->with('supplier', $supplier);
    }

    /**
     * Update the specified Supplier in storage.
     *
     * @param int $id
     * @param UpdateSupplierRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSupplierRequest $request)
    {
        /** @var Supplier $supplier */
        $supplier = Supplier::find($id);

        if (empty($supplier)) {
            Flash::error('Supplier not found');

            return redirect(route('admin.suppliers.index'));
        }

        $supplier->fill($request->all());
        $supplier->save();

        Flash::success('Supplier updated successfully.');

        return redirect(route('admin.suppliers.index'));
    }

    /**
     * Remove the specified Supplier from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Supplier $supplier */
        $supplier = Supplier::find($id);

        if (empty($supplier)) {
            Flash::error('Supplier not found');

            return redirect(route('admin.suppliers.index'));
        }

        $supplier->delete();

        Flash::success('Supplier deleted successfully.');

        return redirect(route('admin.suppliers.index'));
    }
}
