<?php

namespace App\Http\Controllers\Admin;

use App\Models\DeliveryMan;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\ProvideDMEarning;
use App\Models\AccountTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;
use Brian2694\Toastr\Facades\Toastr;

class ProvideDMEarningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provide_dm_earning = ProvideDMEarning::latest()->paginate(config('default_pagination'));
        return view('admin-views.deliveryman-earning-provide.index', compact('provide_dm_earning'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deliveryman_id' => 'required',
            'method'=>'max:191',
            'ref'=>'max:191',
            'amount' => 'required|numeric|between:0.01,999999999999.99',
        ]);


        $dm = DeliveryMan::findOrFail($request['deliveryman_id']);

        $current_balance = $dm?->wallet?->total_earning - $dm?->wallet?->total_withdrawn ?? 0;

        if ($current_balance < $request['amount']) {
            $validator->getMessageBag()->add('amount', 'Insufficient balance!');
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $provide_dm_earning = new ProvideDMEarning();

        $provide_dm_earning->delivery_man_id = $dm->id;
        $provide_dm_earning->method = $request['method'];
        $provide_dm_earning->ref = $request['ref'];
        $provide_dm_earning->amount = $request['amount'];

        try
        {
            DB::beginTransaction();
            $provide_dm_earning->save();
            $dm?->wallet?->increment('total_withdrawn', $request['amount']);
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json(['error'=>$e->getMessage()],200);
        }

        return response()->json(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account_transaction=AccountTransaction::findOrFail($id);
        return view('admin-views.account.view', compact('account_transaction'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DeliveryMan::where('id', $id)->delete();
        Toastr::success(translate('messages.provided_dm_earnings_removed'));
        return back();
    }


    public function dm_earning_list_export(Request $request){
        $withdraw_request = ProvideDMEarning::latest()->get();
        if($request->type == 'csv'){
            return (new FastExcel(Helpers::dm_earning_list_export($withdraw_request)))->download('ProvideDMEarning.csv');
        }
        return (new FastExcel(Helpers::dm_earning_list_export($withdraw_request)))->download('ProvideDMEarning.xlsx');
    }

    public function search_deliveryman_earning(Request $request){
        $key = explode(' ', $request['search']);
        $provide_dm_earning = ProvideDMEarning::with('delivery_man')->whereHas('delivery_man',function($query)use($key){
            foreach ($key as $value) {
                $query->where('f_name', 'like', "%{$value}%")->orWhere('l_name', 'like', "%{$value}%");
            }
        })->get();

        return response()->json([
            'view'=>view('admin-views.deliveryman-earning-provide.partials._table', compact('provide_dm_earning'))->render(),
            'total'=>$provide_dm_earning->count()
        ]);
    }
}
