<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shift;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class ShiftController extends Controller
{
    public function list(Request $request)
    {
        $key = explode(' ', $request['search']) ?? null;

        $shifts= Shift::latest()
        ->when(isset($key) ,function($query) use ($key){
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })

        ->paginate(25);
        $total=$shifts->total();
        return view('admin-views.shift.list',[
            'shifts'=>$shifts,
            'total'=>$total,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'start_time'=>'required|date_format:H:i',
            'end_time'=>'required|date_format:H:i|after:start_time',
            'name'=>'required|max:191',
        ],[
            'end_time.after'=>translate('messages.End time must be after the start time')
        ]);



        if($request->name[array_search('default', $request->lang)] == '' ){
                    return response()->json(['errors' => [
            ['code' => 'default', 'message' => translate('messages.default_shift_name_is_required')]
        ]]);
                }


        $temp = Shift::where(function ($q) use ($request) {
            return $q->where(function ($query) use ($request) {
                return $query->where('start_time', '<=', $request->start_time)->where('end_time', '>=', $request->start_time);
            })->orWhere(function ($query) use ($request) {
                return $query->where('start_time', '<=', $request->end_time)->where('end_time', '>=', $request->end_time);
            });
        })
        ->first();

    if (isset($temp)) {
        return response()->json(['errors' => [
            ['code' => 'overlaped', 'message' => translate('messages.Shift_overlaped')]
        ]]);
    }
        $shift = new Shift();
        $shift->name = $request->name[array_search('default', $request->lang)];
        $shift->start_time = $request->start_time;
        $shift->end_time = $request->end_time;
        $shift->save();
        $default_lang = str_replace('_', '-', app()->getLocale());

        $data = [];
        foreach($request->lang as $index=>$key)
        {
            if($default_lang == $key && !($request->name[$index])){
                if($key != 'default')
                {
                    array_push($data, Array(
                        'translationable_type'  => 'App\Models\Shift',
                        'translationable_id'    => $shift->id,
                        'locale'                => $key,
                        'key'                   => 'name',
                        'value'                 => $shift->name,
                    ));
                }
            }else{
                if($request->name[$index] && $key != 'default')
                {
                    array_push($data, Array(
                        'translationable_type'  => 'App\Models\Shift',
                        'translationable_id'    => $shift->id,
                        'locale'                => $key,
                        'key'                   => 'name',
                        'value'                 => $request->name[$index],
                    ));
                }
            }
        }

        if(count($data))
        {
            Translation::insert($data);
        }
        Toastr::success(translate('messages.shift_added_successfully'));
        return response()->json(['success'=>true]);

    }
    public function status(Request $request)
    {
        $shift = Shift::findOrFail($request->id);
        $shift->status = $request->status;
        $shift->save();
        Toastr::success(translate('messages.shift_status_updated'));
        return back();
    }
    public function update(Request $request )
    {
        $id=$request->id;
        $request->validate([
            'start_time'=>'required',
            'end_time'=>'required|after:start_time',
            'name'=>'required|max:191',
        ],[
            'end_time.after'=>translate('messages.End time must be after the start time')
        ]);

        if($request->name[array_search('default', $request->lang1)] == '' ){
            return response()->json(['errors' => [
            ['code' => 'default', 'message' => translate('messages.default_shift_name_is_required')]
            ]]);
        }
        $temp = Shift::where('id' ,'!=', $id)->where(function ($q) use ($request) {
            return $q->where(function ($query) use ($request) {
                return $query->where('start_time', '<=', $request->start_time)->where('end_time', '>=', $request->start_time);
            })->orWhere(function ($query) use ($request) {
                return $query->where('start_time', '<=', $request->end_time)->where('end_time', '>=', $request->end_time);
            });
        })
        ->first();

    if (isset($temp)) {
        return response()->json(['errors' => [
            ['code' => 'overlaped', 'message' => translate('messages.Shift_overlaped')]
        ]]);
    }
        $shift= Shift::find($id);

        $shift->name = $request->name[array_search('default', $request->lang1)];
        $shift->start_time = $request->start_time;
        $shift->end_time = $request->end_time;
        $shift->save();


        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang1 as $index => $key) {
            if ($default_lang == $key && !($request->name[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Shift',
                            'translationable_id' => $shift->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $shift->type]
                    );
                }
            } else {
                if ($request->name[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Shift',
                            'translationable_id' => $shift->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $request->name[$index]]
                    );
                }
            }

        }


        Toastr::success(translate('messages.shift_updated_successfully'));
        return response()->json(['success'=>true]);
    }
    public function destroy(Shift $shift)
    {
        $shift->delete();
        $shift?->translations()?->delete();
        Toastr::success(translate('messages.shift_deleted_successfully'));
        return back();
    }

}
