<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Unit;
use Illuminate\Http\Request;
use Flash;
use Response;

class UnitController extends AppBaseController
{
    /**
     * Display a listing of the Unit.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Unit $units */
        $units = Unit::all();

        return view('admin.units.index')
            ->with('units', $units);
    }

    /**
     * Show the form for creating a new Unit.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.units.create');
    }

    /**
     * Store a newly created Unit in storage.
     *
     * @param CreateUnitRequest $request
     *
     * @return Response
     */
    public function store(CreateUnitRequest $request)
    {
        $input = $request->all();

        /** @var Unit $unit */
        $unit = Unit::create($input);

        Flash::success('Unit saved successfully.');

        return redirect(route('admin.units.index'));
    }

    /**
     * Display the specified Unit.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Unit $unit */
        $unit = Unit::find($id);

        if (empty($unit)) {
            Flash::error('Unit not found');

            return redirect(route('admin.units.index'));
        }

        return view('admin.units.show')->with('unit', $unit);
    }

    /**
     * Show the form for editing the specified Unit.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Unit $unit */
        $unit = Unit::find($id);

        if (empty($unit)) {
            Flash::error('Unit not found');

            return redirect(route('admin.units.index'));
        }

        return view('admin.units.edit')->with('unit', $unit);
    }

    /**
     * Update the specified Unit in storage.
     *
     * @param int $id
     * @param UpdateUnitRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUnitRequest $request)
    {
        /** @var Unit $unit */
        $unit = Unit::find($id);

        if (empty($unit)) {
            Flash::error('Unit not found');

            return redirect(route('admin.units.index'));
        }

        $unit->fill($request->all());
        $unit->save();

        Flash::success('Unit updated successfully.');

        return redirect(route('admin.units.index'));
    }

    /**
     * Remove the specified Unit from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Unit $unit */
        $unit = Unit::find($id);

        if (empty($unit)) {
            Flash::error('Unit not found');

            return redirect(route('admin.units.index'));
        }

        $unit->delete();

        Flash::success('Unit deleted successfully.');

        return redirect(route('admin.units.index'));
    }
}
