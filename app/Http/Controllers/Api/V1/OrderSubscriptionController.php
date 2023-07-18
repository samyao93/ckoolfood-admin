<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\SubscriptionLog;
use App\Models\SubscriptionPause;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\CentralLogics\Helpers;

class OrderSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $offset = $request->query('off$offset', 1);
        $paginator = Order::with(['restaurant','subscription'])->withCount('details')->whereHas('subscription')->where('user_id', $request?->user()?->id)->latest()->paginate($limit, ['*'], 'page', $offset);
        $data = [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'orders' => array_map(function ($data) {
                $data['delivery_address'] = $data['delivery_address'] ? json_decode($data['delivery_address']) : $data['delivery_address'];
                $data['restaurant'] = $data['restaurant'] ? Helpers::restaurant_data_formatting($data['restaurant']) : $data['restaurant'];
                return $data;
            }, $paginator->items())
        ];
        return response()->json($data);
    }

    public function show(Request $request, $id, $tab = null)
    {
        $limit = $request->query('limit', 10);
        $offset = $request->query('off$offset', 1);
        switch($tab){
            case 'delivery-log':
                $paginator = SubscriptionLog::where('subscription_id', $id)->paginate($limit, ['*'], 'page', $offset);
                $data = [
                    'total_size' => $paginator->total(),
                    'limit' => $limit,
                    'offset' => $offset,
                    'data' => $paginator->items()
                ];
                break;
            case 'pause-log':
                $paginator = SubscriptionPause::where('subscription_id', $id)->paginate($limit, ['*'], 'page', $offset);
                $data = [
                    'total_size' => $paginator->total(),
                    'limit' => $limit,
                    'offset' => $offset,
                    'data' => $paginator->items()
                ];
                break;
            default:
            $subscription = Subscription::with(['restaurant','order','schedules'])->findOrFail($id);
            $data = $subscription->toArray();
            $data['restaurant'] = $subscription->restaurant ? Helpers::restaurant_data_formatting($subscription->restaurant) : null;
            $data['order'] = $subscription->order ? Helpers::order_data_formatting($subscription->order) : null;
        }

        return response()->json($data);
    }
    public function edit(Subscription $subscription)
    {
        return response()->json($subscription);
    }
    public function update(Request $request, Subscription $subscription)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:paused,canceled',
            'start_date' => 'required_if:status,paused|nullable|date|after_or_equal:today',
            'end_date' => 'required_if:status,paused|nullable|date|after_or_equal:start_date',
            'cancellation_reason'=>'required_if:status,canceled'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        DB::beginTransaction();
        try{
            if($request->status == 'paused'){
                $pc = Subscription::checkDate($request->start_date, $request->end_date)->whereDoesntHave('pause', function($query)use($request){
                    $query->checkDate($request->start_date, $request->end_date);
                })->whereId( $subscription->id)->count();
                if(!$pc)
                {
                    return response()->json(['errors' => [['code'=>'overlaped', 'message'=>translate('messages.subscription_pause_log_overlap_warning')]]], 403);
                }
                $subscription?->pause()?->updateOrInsert(['from'=>$request->start_date, 'subscription_id'=>$subscription->id],['to'=>$request->end_date]);
            }
            elseif ($request->status == 'canceled' && $subscription?->order) {
                $subscription?->order()?->update([
                    'order_status' => $request->status,
                    'canceled' => now(),
                    'cancellation_note' => $request->note ?? null,
                    'cancellation_reason' => $request->cancellation_reason ?? null,
                    'canceled_by' => 'customer',
                    ]);

                    if($subscription?->log){
                        $subscription?->log()?->update([
                            'order_status' => $request->status,
                            'canceled' => now(),
                            ]);
                    }
                $subscription->status = $request->status;
            }
            elseif ($request->status == 'active' && $subscription?->order) {
                $subscription?->order()?->update([
                    'order_status' => 'pending',
                    'canceled' => null,
                    'pending' => now(),
                    ]);
                $subscription->status = $request->status;
            }
            else {
                $subscription->status = $request->status;
            }
            $subscription?->save();
            DB::commit();
            return response()->json(translate('messages.subscription_updated_successfully'), 200);

        }catch(Exception $ex){
            DB::rollBack();
            info($ex->getMessage());
            return response()->json(['errors' => [['code'=>$ex->getCode(), 'message'=>$ex->getMessage()]]], 403);
        }

        return response()->json(['errors' => [['code'=>'unknown-error', 'message'=>translate('messages.failed_updated_subscription')]]], 500);
    }
    public function update_schedule(Request $request, Subscription $subscription)
    {
        $validator = Validator::make($request->all(), [
            'day' => 'nullable|integer',
            'time' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        try{
            $subscription?->schedules()?->updateOrInsert(['day'=>$request->day, 'subscription_id'=>$subscription->id],['time'=>$request->time]);
            return response()->json(translate('messages.subscription_schedule_updated_successfully'), 200);
        }catch(Exception $ex){
            info($ex->getMessage());
            return response()->json(['errors' => [['code'=>$ex->getCode(), 'message'=>$ex->getMessage()]]], 403);
        }
        return response()->json(['errors' => [['code'=>'unknown-error', 'message'=>translate('messages.failed_updated_subscription_schedule')]]], 500);
    }
}
