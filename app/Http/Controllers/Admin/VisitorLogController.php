<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VisitorLog;
use Illuminate\Http\Request;

class VisitorLogController extends Controller
{
    public function index(Request $request)
    {
        abort(404);
        $customer=User::where('id',$request->customer_id)->select(['id','f_name','l_name'])->first();
        $logs = VisitorLog::with('users:id,f_name,l_name','visitor_log:id,name')
        ->when(isset($request->customer_id), function ($q) use($request){
                $q->where('user_id',$request->customer_id);
        })
            ->latest()
            ->paginate(config('default_pagination'));
        return view('admin-views.visitor-log.index', compact('logs','customer'));
    }
}
