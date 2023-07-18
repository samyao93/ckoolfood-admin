<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\AdminWallet;
use App\Models\DeliveryMan;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\AccountTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;

class AccountTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $account_transaction = AccountTransaction::latest()->paginate(config('default_pagination'));
        return view('admin-views.account.index', compact('account_transaction'));
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
            'type' => 'required|in:restaurant,deliveryman',
            'method' => 'required',
            'restaurant_id' => 'required_if:type,restaurant',
            'deliveryman_id' => 'required_if:type,deliveryman',
            'amount' => 'required|numeric',
        ]);

        if ($request['restaurant_id'] && $request['deliveryman_id']) {
            $validator->getMessageBag()->add('from type', 'Can not select both deliveryman and restaurant');
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }


        if($request['type']=='restaurant' && $request['restaurant_id'])
        {
            $restaurant = Restaurant::findOrFail($request['restaurant_id']);
            $data = $restaurant?->vendor;
            $current_balance = $data?->wallet?->collected_cash ?? 0;
        }
        else if($request['type']=='deliveryman' && $request['deliveryman_id'])
        {
            $data = DeliveryMan::findOrFail($request['deliveryman_id']);

            $current_balance = $data?->wallet?->collected_cash ?? 0;
        }

        if ($current_balance < $request['amount']) {
            $validator->getMessageBag()->add('amount', translate('messages.insufficient_balance'));
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $account_transaction = new AccountTransaction();
        $account_transaction->from_type = $request['type'];
        $account_transaction->from_id = $data->id;
        $account_transaction->method = $request['method'];
        $account_transaction->ref = $request['ref'];
        $account_transaction->amount = $request['amount'];
        $account_transaction->current_balance = $current_balance;

        try
        {
            DB::beginTransaction();
            $account_transaction->save();
            $data?->wallet?->decrement('collected_cash', $request['amount']);
            AdminWallet::where('admin_id', Admin::where('role_id', 1)->first()->id)->increment('manual_received', $request['amount']);
            if($request['type']=='deliveryman' && $request['deliveryman_id']){
                $mail_status = Helpers::get_mail_status('cash_collect_mail_status_dm');
                if (config('mail.status') && $mail_status == '1') {
                    Mail::to($data['email'])->send(new \App\Mail\CollectCashMail($account_transaction,$data['f_name']));
                }
            }
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json($e->getMessage());
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
        AccountTransaction::where('id', $id)->delete();
        Toastr::success(translate('messages.account_transaction_removed'));
        return back();
    }

    public function export_account_transaction(Request $request){
        $account_transaction = AccountTransaction::latest()->get();
        if($request->type == 'csv'){
            return (new FastExcel(Helpers::export_account_transaction($account_transaction)))->download('Account_transactions.csv');
        }
        return (new FastExcel(Helpers::export_account_transaction($account_transaction)))->download('Account_transactions.xlsx');
    }

    public function search_account_transaction(Request $request){
        $key = explode(' ', $request['search']);
        $account_transaction = AccountTransaction::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('ref', 'like', "%{$value}%");
            }
        })->get();

        return response()->json([
            'view'=>view('admin-views.account.partials._table', compact('account_transaction'))->render(),
            'total'=>$account_transaction->count()
        ]);
    }
}
