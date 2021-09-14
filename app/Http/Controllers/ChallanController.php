<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateChallanRequest;
use App\Http\Requests\UpdateChallanRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Challan;
use Illuminate\Http\Request;
use Flash;
use Response;

class ChallanController extends AppBaseController
{
    /**
     * Display a listing of the Challan.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Challan $challans */
        $challans = Challan::all();

        return view('admin.challans.index')
            ->with('challans', $challans);
    }

    /**
     * Show the form for creating a new Challan.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.challans.create');
    }

    /**
     * Store a newly created Challan in storage.
     *
     * @param CreateChallanRequest $request
     *
     * @return Response
     */
    public function store(CreateChallanRequest $request)
    {
        $input = $request->all();

        /** @var Challan $challan */
        $challan = Challan::create($input);

        Flash::success('Challan saved successfully.');

        return redirect(route('admin.challans.index'));
    }

    /**
     * Display the specified Challan.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Challan $challan */
        $challan = Challan::find($id);

        if (empty($challan)) {
            Flash::error('Challan not found');

            return redirect(route('admin.challans.index'));
        }

        return view('admin.challans.show')->with('challan', $challan);
    }

    /**
     * Show the form for editing the specified Challan.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Challan $challan */
        $challan = Challan::find($id);

        if (empty($challan)) {
            Flash::error('Challan not found');

            return redirect(route('admin.challans.index'));
        }

        return view('admin.challans.edit')->with('challan', $challan);
    }

    /**
     * Update the specified Challan in storage.
     *
     * @param int $id
     * @param UpdateChallanRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChallanRequest $request)
    {
        /** @var Challan $challan */
        $challan = Challan::find($id);

        if (empty($challan)) {
            Flash::error('Challan not found');

            return redirect(route('admin.challans.index'));
        }

        $challan->fill($request->all());
        $challan->save();

        Flash::success('Challan updated successfully.');

        return redirect(route('admin.challans.index'));
    }

    /**
     * Remove the specified Challan from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Challan $challan */
        $challan = Challan::find($id);

        if (empty($challan)) {
            Flash::error('Challan not found');

            return redirect(route('admin.challans.index'));
        }

        $challan->delete();

        Flash::success('Challan deleted successfully.');

        return redirect(route('admin.challans.index'));
    }
}
