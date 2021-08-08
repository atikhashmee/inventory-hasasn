<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Brand;
use Illuminate\Http\Request;
use Flash;
use Response;

class BrandController extends AppBaseController
{
    /**
     * Display a listing of the Brand.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Brand $brands */
        $brands = Brand::all();

        return view('brands.index')
            ->with('brands', $brands);
    }

    /**
     * Show the form for creating a new Brand.
     *
     * @return Response
     */
    public function create()
    {
        return view('brands.create');
    }

    /**
     * Store a newly created Brand in storage.
     *
     * @param CreateBrandRequest $request
     *
     * @return Response
     */
    public function store(CreateBrandRequest $request)
    {
        $input = $request->all();

        /** @var Brand $brand */
        $brand = Brand::create($input);

        Flash::success('Brand saved successfully.');

        return redirect(route('brands.index'));
    }

    /**
     * Display the specified Brand.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Brand $brand */
        $brand = Brand::find($id);

        if (empty($brand)) {
            Flash::error('Brand not found');

            return redirect(route('brands.index'));
        }

        return view('brands.show')->with('brand', $brand);
    }

    /**
     * Show the form for editing the specified Brand.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Brand $brand */
        $brand = Brand::find($id);

        if (empty($brand)) {
            Flash::error('Brand not found');

            return redirect(route('brands.index'));
        }

        return view('brands.edit')->with('brand', $brand);
    }

    /**
     * Update the specified Brand in storage.
     *
     * @param int $id
     * @param UpdateBrandRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBrandRequest $request)
    {
        /** @var Brand $brand */
        $brand = Brand::find($id);

        if (empty($brand)) {
            Flash::error('Brand not found');

            return redirect(route('brands.index'));
        }

        $brand->fill($request->all());
        $brand->save();

        Flash::success('Brand updated successfully.');

        return redirect(route('brands.index'));
    }

    /**
     * Remove the specified Brand from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Brand $brand */
        $brand = Brand::find($id);

        if (empty($brand)) {
            Flash::error('Brand not found');

            return redirect(route('brands.index'));
        }

        $brand->delete();

        Flash::success('Brand deleted successfully.');

        return redirect(route('brands.index'));
    }
}
