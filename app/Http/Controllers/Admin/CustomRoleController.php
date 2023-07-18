<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminRole;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Rap2hpoutre\FastExcel\FastExcel;

class CustomRoleController extends Controller
{
    public function create()
    {
        $rl=AdminRole::whereNotIn('id',[1])->latest()->paginate(config('default_pagination'));
        return view('admin-views.custom-role.create',compact('rl'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:admin_roles|max:191',
            'modules'=>'required|array|min:1'
        ],[
            'name.required'=>translate('messages.Role name is required!'),
            'modules.required'=>translate('messages.Please select atleast one module')
        ]);

        if($request->name[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_name_is_required'));
            return back();
            }

        $role = new AdminRole();
        $role->name = $request->name[array_search('default', $request->lang)];
        $role->modules = json_encode($request['modules']);
        $role->status = 1;
        $role->save();
        $data = [];
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if($default_lang == $key && !($request->name[$index])){
                if ($key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\AdminRole',
                        'translationable_id' => $role->id,
                        'locale' => $key,
                        'key' => 'name',
                        'value' => $role->name,
                    ));
                }
            }else{
                if ($request->name[$index] && $key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\AdminRole',
                        'translationable_id' => $role->id,
                        'locale' => $key,
                        'key' => 'name',
                        'value' => $request->name[$index],
                    ));
                }
            }
        }

        Translation::insert($data);

        Toastr::success(translate('messages.role_added_successfully'));
        return back();
    }

    public function edit($id)
    {
        if($id == 1)
        {
            return view('errors.404');
        }
        $role=AdminRole::withoutGlobalScope('translate')->with('translations')->where(['id'=>$id])->first(['id','name','modules']);
        return view('admin-views.custom-role.edit',compact('role'));
    }

    public function update(Request $request,$id)
    {
        if($id == 1)
        {
            return view('errors.404');
        }
        $request->validate([
            'name' => 'required|max:191|unique:admin_roles,name,'.$id,
            'modules'=>'required|array|min:1'
        ],[
            'name.required'=>translate('messages.Role name is required!'),
            'modules.required'=>translate('messages.Please select atleast one module')
        ]);
        if($request->name[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_name_is_required'));
            return back();
            }

        $role = AdminRole::find($id);
        $role->name = $request->name[array_search('default', $request->lang)];
        $role->modules = json_encode($request['modules']);
        $role->status = 1;
        $role->save();

        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if($default_lang == $key && !($request->name[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\AdminRole',
                            'translationable_id' => $role->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $role->name]
                    );
                }
            }else{

                if ($request->name[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\AdminRole',
                            'translationable_id' => $role->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $request->name[$index]]
                    );
                }
            }
        }
        Toastr::success(translate('messages.role_updated_successfully'));
        return redirect()->route('admin.custom-role.create');
    }
    public function distroy($id)
    {
        if($id == 1)
        {
            return view('errors.404');
        }
        $role=AdminRole::find($id);
        $role?->translations()?->delete();
        $role->delete();
        Toastr::success(translate('messages.role_deleted_successfully'));
        return back();
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $rl=AdminRole::where('id','!=','1')
        ->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->latest()->limit(50)->get();
        return response()->json([
            'view'=>view('admin-views.custom-role.partials._table',compact('rl'))->render(),
            'count'=>$rl->count()
        ]);
    }

    public function employee_role_export(Request $request){
        $withdraw_request = AdminRole::whereNotIn('id',[1])->get();
        if($request->type == 'csv'){
            return (new FastExcel($withdraw_request))->download('CustomRole.csv');
        }
        return (new FastExcel($withdraw_request))->download('CustomRole.xlsx');
    }
}
