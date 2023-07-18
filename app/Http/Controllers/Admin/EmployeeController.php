<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\AdminRole;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Validation\Rules\Password;

class EmployeeController extends Controller
{

    public function add_new()
    {
        $rls = AdminRole::whereNotIn('id', [1])->get();
        return view('admin-views.employee.add-new', compact('rls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'nullable|max:100',
            'role_id' => 'required',
            'image' => 'required|max:2048',
            'email' => 'required|unique:admins',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20|unique:admins',
            'password' => ['required', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],

        ]);

        if ($request->role_id == 1) {
            Toastr::warning(translate('messages.access_denied'));
            return back();
        }

        Admin::insert([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'phone' => $request->phone,
            'zone_id' => $request->zone_id,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => bcrypt($request->password),
            'image' => Helpers::upload(dir:'admin/', format:'png', image: $request->file('image')),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Toastr::success(translate('messages.employee_added_successfully'));
        return redirect()->route('admin.employee.list');
    }

    function list()
    {
        $em = Admin::zone()->with(['role'])->where('role_id', '!=','1')->latest()->paginate(config('default_pagination'));
        return view('admin-views.employee.list', compact('em'));
    }

    public function edit($id)
    {
        $e = Admin::zone()->where('role_id', '!=','1')->where(['id' => $id])->first();
        if (auth('admin')->id()  == $e['id']){
            Toastr::error(translate('messages.You_can_not_edit_your_own_info'));
            return redirect()->route('admin.employee.list');
        }
        $rls = AdminRole::whereNotIn('id', [1])->get();
        return view('admin-views.employee.edit', compact('rls', 'e'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'f_name' => 'required|max:100',
            'l_name' => 'nullable|max:100',
            'role_id' => 'required',
            'email' => 'required|unique:admins,email,'.$id,
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20|unique:admins,phone,'.$id,
            'password' => ['nullable', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],
            'image' => 'nullable|max:2048',
        ], [
            'f_name.required' => translate('messages.first_name_is_required'),
        ]);


        if ($request->role_id == 1) {
            Toastr::warning(translate('messages.access_denied'));
            return back();
        }

        $e = Admin::where('role_id','!=',1)->findOrFail($id);
        if (auth('admin')->id()  == $e['id']){
            Toastr::error(translate('messages.You_can_not_edit_your_own_info'));
            return redirect()->route('admin.employee.list');
        }

        if ($request['password'] == null) {
            $pass = $e['password'];
        } else {

            $pass = bcrypt($request['password']);
        }

        if ($request->has('image')) {
            $e['image'] = Helpers::update(dir:'admin/', old_image: $e->image, format: 'png', image:$request->file('image'));
        }

       Admin::where(['id' => $id])->update([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'phone' => $request->phone,
            'zone_id' => $request->zone_id,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => $pass,
            'image' => $e['image'],
            'updated_at' => now(),
        ]);

        Toastr::success(translate('messages.employee_updated_successfully'));
        return redirect()->route('admin.employee.list');
    }

    public function distroy($id)
    {
        $role=Admin::zone()->where('role_id', '!=','1')->where(['id'=>$id])->first();
        if (auth('admin')->id()  == $role['id']){
            Toastr::error(translate('messages.You_can_not_edit_your_own_info'));
            return redirect()->route('admin.employee.list');
        }
        $role->delete();
        Toastr::info(translate('messages.employee_deleted_successfully'));
        return back();
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $employees=Admin::zone()->where('role_id', '!=','1')
        ->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('f_name', 'like', "%{$value}%");
                $q->orWhere('l_name', 'like', "%{$value}%");
                $q->orWhere('phone', 'like', "%{$value}%");
                $q->orWhere('email', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('admin-views.employee.partials._table',compact('employees'))->render(),
            'count'=>$employees->count()
        ]);
    }

    public function employee_list_export(Request $request){
        $withdraw_request = Admin::zone()->with(['role'])->where('role_id', '!=','1')->get();
        if($request->type == 'csv'){
            return (new FastExcel($withdraw_request))->download('Employee.csv');
        }
        return (new FastExcel($withdraw_request))->download('Employee.xlsx');
    }
}
