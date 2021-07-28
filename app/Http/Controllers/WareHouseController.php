<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWareHouseRequest;
use App\Http\Requests\UpdateWareHouseRequest;
use App\Repositories\WareHouseRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class WareHouseController extends AppBaseController
{
    /** @var  WareHouseRepository */
    private $wareHouseRepository;

    public function __construct(WareHouseRepository $wareHouseRepo)
    {
        $this->wareHouseRepository = $wareHouseRepo;
    }

    /**
     * Display a listing of the WareHouse.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $wareHouses = $this->wareHouseRepository->all();

        return view('ware_houses.index')
            ->with('wareHouses', $wareHouses);
    }

    /**
     * Show the form for creating a new WareHouse.
     *
     * @return Response
     */
    public function create()
    {
        return view('ware_houses.create');
    }

    /**
     * Store a newly created WareHouse in storage.
     *
     * @param CreateWareHouseRequest $request
     *
     * @return Response
     */
    public function store(CreateWareHouseRequest $request)
    {
        $input = $request->all();

        $wareHouse = $this->wareHouseRepository->create($input);

        Flash::success('Ware House saved successfully.');

        return redirect(route('wareHouses.index'));
    }

    /**
     * Display the specified WareHouse.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $wareHouse = $this->wareHouseRepository->find($id);

        if (empty($wareHouse)) {
            Flash::error('Ware House not found');

            return redirect(route('wareHouses.index'));
        }

        return view('ware_houses.show')->with('wareHouse', $wareHouse);
    }

    /**
     * Show the form for editing the specified WareHouse.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $wareHouse = $this->wareHouseRepository->find($id);

        if (empty($wareHouse)) {
            Flash::error('Ware House not found');

            return redirect(route('wareHouses.index'));
        }

        return view('ware_houses.edit')->with('wareHouse', $wareHouse);
    }

    /**
     * Update the specified WareHouse in storage.
     *
     * @param int $id
     * @param UpdateWareHouseRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWareHouseRequest $request)
    {
        $wareHouse = $this->wareHouseRepository->find($id);

        if (empty($wareHouse)) {
            Flash::error('Ware House not found');

            return redirect(route('wareHouses.index'));
        }

        $wareHouse = $this->wareHouseRepository->update($request->all(), $id);

        Flash::success('Ware House updated successfully.');

        return redirect(route('wareHouses.index'));
    }

    /**
     * Remove the specified WareHouse from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $wareHouse = $this->wareHouseRepository->find($id);

        if (empty($wareHouse)) {
            Flash::error('Ware House not found');

            return redirect(route('wareHouses.index'));
        }

        $this->wareHouseRepository->delete($id);

        Flash::success('Ware House deleted successfully.');

        return redirect(route('wareHouses.index'));
    }
}
