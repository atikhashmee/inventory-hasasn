<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMenufactureRequest;
use App\Http\Requests\UpdateMenufactureRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Menufacture;
use Illuminate\Http\Request;
use Flash;
use Response;

class MenufactureController extends AppBaseController
{
    /**
     * Display a listing of the Menufacture.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Menufacture $menufactures */
        $menufactures = Menufacture::all();

        return view('admin.menufactures.index')
            ->with('menufactures', $menufactures);
    }

    /**
     * Show the form for creating a new Menufacture.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.menufactures.create');
    }

    /**
     * Store a newly created Menufacture in storage.
     *
     * @param CreateMenufactureRequest $request
     *
     * @return Response
     */
    public function store(CreateMenufactureRequest $request)
    {
        $input = $request->all();

        /** @var Menufacture $menufacture */
        $menufacture = Menufacture::create($input);

        Flash::success('Menufacture saved successfully.');

        return redirect(route('admin.menufactures.index'));
    }

    /**
     * Display the specified Menufacture.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Menufacture $menufacture */
        $menufacture = Menufacture::find($id);

        if (empty($menufacture)) {
            Flash::error('Menufacture not found');

            return redirect(route('admin.menufactures.index'));
        }

        return view('admin.menufactures.show')->with('menufacture', $menufacture);
    }

    /**
     * Show the form for editing the specified Menufacture.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Menufacture $menufacture */
        $menufacture = Menufacture::find($id);

        if (empty($menufacture)) {
            Flash::error('Menufacture not found');

            return redirect(route('admin.menufactures.index'));
        }

        return view('admin.menufactures.edit')->with('menufacture', $menufacture);
    }

    /**
     * Update the specified Menufacture in storage.
     *
     * @param int $id
     * @param UpdateMenufactureRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMenufactureRequest $request)
    {
        /** @var Menufacture $menufacture */
        $menufacture = Menufacture::find($id);

        if (empty($menufacture)) {
            Flash::error('Menufacture not found');

            return redirect(route('admin.menufactures.index'));
        }

        $menufacture->fill($request->all());
        $menufacture->save();

        Flash::success('Menufacture updated successfully.');

        return redirect(route('admin.menufactures.index'));
    }

    /**
     * Remove the specified Menufacture from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Menufacture $menufacture */
        $menufacture = Menufacture::find($id);

        if (empty($menufacture)) {
            Flash::error('Menufacture not found');

            return redirect(route('admin.menufactures.index'));
        }

        $menufacture->delete();

        Flash::success('Menufacture deleted successfully.');

        return redirect(route('admin.menufactures.index'));
    }
}
