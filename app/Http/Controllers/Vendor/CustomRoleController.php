<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Translation;
use App\Models\EmployeeRole;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class CustomRoleController extends Controller
{
    public function create()
    {
        $rl=EmployeeRole::where('restaurant_id',Helpers::get_restaurant_id())->orderBy('name')->paginate(config('default_pagination'));
        return view('vendor-views.custom-role.create',compact('rl'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'modules'=>'required|array|min:1',
            'name' => [
                'required',Rule::unique('employee_roles')->where(function($query) {
                  $query->where('restaurant_id', Helpers::get_restaurant_id());
              })
            ],
        ],[
            'name.required'=>translate('messages.Role name is required!'),
            'modules.required'=>translate('messages.Please select atleast one module')
        ]);
        if($request->name[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_name_is_required'));
            return back();
            }
        $role = new EmployeeRole();
        $role->name=$request->name[array_search('default', $request->lang)];
        $role->modules=json_encode($request['modules']);
        $role->status=1;
        $role->restaurant_id=Helpers::get_restaurant_id();
        $role->save();

        $data = [];
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if($default_lang == $key && !($request->name[$index])){
                if ($key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\EmployeeRole',
                        'translationable_id' => $role->id,
                        'locale' => $key,
                        'key' => 'name',
                        'value' => $role->name,
                    ));
                }
            }else{
                if ($request->name[$index] && $key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\EmployeeRole',
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
        $role=EmployeeRole::where('restaurant_id',Helpers::get_restaurant_id())->withoutGlobalScope('translate')->with('translations')->where(['id'=>$id])->first(['id','name','modules']);
        return view('vendor-views.custom-role.edit',compact('role'));
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'modules'=>'required|array|min:1',
            'name' => [
                'required',Rule::unique('employee_roles')->where(function($query)use($id) {
                  $query->where('restaurant_id', Helpers::get_restaurant_id())->where('id','<>', $id);
              })
            ]
        ],[
            'name.required'=>translate('messages.Role name is required!'),
            'name.unique'=>translate('messages.Role name already taken!'),
            'modules.required'=>translate('messages.Please select atleast one module')
        ]);

        if($request->name[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_name_is_required'));
            return back();
            }
            
        $role = EmployeeRole::where('restaurant_id',Helpers::get_restaurant_id())->where(['id'=>$id])->first();
        $role->name = $request->name[array_search('default', $request->lang)];
        $role->modules = json_encode($request['modules']);
        $role->status = 1;
        $role->restaurant_id = Helpers::get_restaurant_id();
        $role->save();

        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if($default_lang == $key && !($request->name[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\EmployeeRole',
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
                            'translationable_type' => 'App\Models\EmployeeRole',
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
        return redirect()->route('vendor.custom-role.create');
    }

    public function distroy($id)
    {
        $role=EmployeeRole::where('restaurant_id',Helpers::get_restaurant_id())->where(['id'=>$id])->first();
        $role?->translations()?->delete();
        $role->delete();
        Toastr::success(translate('messages.role_deleted_successfully'));
        return back();
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $rl=EmployeeRole::where('restaurant_id',Helpers::get_restaurant_id())->
        where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->orderBy('name')->limit(50)->get();
        return response()->json([
            'view'=>view('vendor-views.custom-role.partials._table',compact('rl'))->render()
        ]);
    }
}
