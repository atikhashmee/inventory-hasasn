<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Models\Shop;
use App\Models\Challan;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateChallanRequest;
use App\Http\Requests\UpdateChallanRequest;

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
        $user = auth()->user();
        $challanSql = Challan::where(function($q) {
            if (request()->challan_type) {
                $q->where('challan_type', request()->challan_type);
            }
            if (request()->query('customer_id')!='') {
                $q->where('customer_id', request()->query('customer_id'));
            }
            if (request()->query('shop_id')!='') {
                $q->where('shop_id', request()->query('shop_id'));
            }
            if (request()->query('status')!='') {
                $q->where('status', request()->query('status'));
            }

        });
        if ($user->role != 'admin') {
            $challanSql->where('user_id', $user->id);
        }
        $challans = $challanSql->orderBy('id', 'DESC')->paginate(50);

        $data['serial'] = pagiSerial($challans, 50);
        $data['shops'] = Shop::get();
        $data['customers'] = Customer::get();
        $data['challans'] =  $challans;
        return view('admin.challans.index', $data);
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
        try {
            //code...
            $input = $request->all();
            $user = auth()->user();

            /** @var Challan $challan */
            $input['user_id'] = $user->id;
            $challan = Challan::create($input);

            Flash::success('Challan saved successfully.');
            
            return redirect(route('admin.challans.index'));
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
            return redirect()->back()->withErrors($request->all())->withInput();
            //throw $th;
        }
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
