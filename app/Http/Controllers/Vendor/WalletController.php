<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\WithdrawRequest;
use App\Models\RestaurantWallet;
use App\Models\WithdrawalMethod;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;

class WalletController extends Controller
{
    public function index()
    {
        $withdrawal_methods = WithdrawalMethod::ofStatus(1)->get();
        $withdraw_req = WithdrawRequest::with(['vendor'])->where('vendor_id', Helpers::get_vendor_id())->latest()->paginate(config('default_pagination'));
        return view('vendor-views.wallet.index', compact('withdraw_req','withdrawal_methods'));
    }
    public function w_request(Request $request)
    {
        $method = WithdrawalMethod::find($request['withdraw_method']);
        $fields = array_column($method->method_fields, 'input_name');
        $values = $request->all();

        $method_data = [];
        foreach ($fields as $field) {
            if(key_exists($field, $values)) {
                $method_data[$field] = $values[$field];
            }
        }
        $w = RestaurantWallet::where('vendor_id', Helpers::get_vendor_id())->first();
        if ($w->balance >= $request['amount'] && $request['amount'] > .01) {
            $data = [
                'vendor_id' => Helpers::get_vendor_id(),
                'amount' => $request['amount'],
                'transaction_note' => null,
                'withdrawal_method_id' => $request['withdraw_method'],
                'withdrawal_method_fields' => json_encode($method_data),
                'approved' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ];

            DB::table('withdraw_requests')->insert($data);
            RestaurantWallet::where('vendor_id', Helpers::get_vendor_id())->increment('pending_withdraw', $request['amount']);

            try
            {
                $admin= Admin::where('role_id', 1)->first();
                $mail_status = Helpers::get_mail_status('withdraw_request_mail_status_admin');
                if(config('mail.status') && $mail_status == '1') {
                    $wallet_transaction = WithdrawRequest::where('vendor_id',Helpers::get_vendor_id())->latest()->first();
                    Mail::to($admin->email)->send(new \App\Mail\WithdrawRequestMail('admin_mail',$wallet_transaction));
                }
            }
            catch(\Exception $e)
            {
                info($e->getMessage());
            }

            Toastr::success(translate('Withdraw request has been sent.'));
            return redirect()->back();
        }

        Toastr::error('invalid request.!');
        return redirect()->back();
    }

    public function close_request($id)
    {
        $wr = WithdrawRequest::find($id);
        if ($wr->approved == 0) {
            RestaurantWallet::where('vendor_id', Helpers::get_vendor_id())->decrement('pending_withdraw', $wr['amount']);
        }
        $wr->delete();
        Toastr::success(translate('request closed!'));
        return back();
    }

    public function method_list(Request $request)
    {
        $method = WithdrawalMethod::ofStatus(1)->where('id', $request->method_id)->first();

        return response()->json(['content'=>$method], 200);
    }
}
