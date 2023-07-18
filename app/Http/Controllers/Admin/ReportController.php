<?php

namespace App\Http\Controllers\Admin;

use App\Models\Food;
use App\Models\User;
use App\Models\Zone;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\OrderTransaction;
use App\CentralLogics\OrderLogic;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use App\CentralLogics\RestaurantLogic;
use App\Models\SubscriptionTransaction;
use Illuminate\Support\Facades\View;

class ReportController extends Controller
{
    public function order_index()
    {
        if(session()->has('from_date') == false)
        {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }
        return view('admin-views.report.order-index');
    }

    public function day_wise_report(Request $request)
    {
        $key = explode(' ', $request['search']);
        $from = null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurant_id = $request->query('restaurant_id', 'all');
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;

        $order_transactions = OrderTransaction::with('order','order.details','order.customer','order.restaurant')
                ->when(isset($zone), function ($query) use ($zone) {
                    return $query->where('zone_id', $zone->id);
                })
                ->when(isset($restaurant), function ($query) use ($restaurant){
                        return $query->whereHas('order', function($q) use ($restaurant){
                            $q->where('restaurant_id', $restaurant->id);
                    });
                })
                ->when(isset($from) && isset($to) && $filter == 'custom', function ($query) use ($from, $to) {
                    return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                })
                ->when(isset($filter) && $filter == 'this_year', function ($query) {
                    return $query->whereYear('created_at', now()->format('Y'));
                })
                ->when(isset($filter) && $filter == 'this_month', function ($query) {
                    return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                })
                ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                    return $query->whereYear('created_at', date('Y') - 1);
                })
                ->when(isset($filter) && $filter == 'this_week', function ($query) {
                    return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                })
                ->when(isset($request['search']), function ($query) use($key){
                    $query->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('order_id', 'like', "%{$value}%");
                        }
                    });
                })
            ->orderBy('created_at', 'desc')
            ->paginate(config('default_pagination'))->withQueryString();

            $admin_earned = OrderTransaction::with('order','order.details','order.customer','order.restaurant')
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->where('zone_id', $zone->id);
                        })
                    ->when(isset($restaurant), function ($query) use ($restaurant){
                            return $query->whereHas('order', function($q) use ($restaurant){
                                $q->where('restaurant_id', $restaurant->id);
                        });
                    })
                    ->when(isset($from) && isset($to) && $filter == 'custom', function ($query) use ($from, $to) {
                        return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                    })
                    ->when(isset($filter) && $filter == 'this_year', function ($query) {
                        return $query->whereYear('created_at', now()->format('Y'));
                    })
                    ->when(isset($filter) && $filter == 'this_month', function ($query) {
                        return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                    })
                    ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                        return $query->whereYear('created_at', date('Y') - 1);
                    })
                    ->when(isset($filter) && $filter == 'this_week', function ($query) {
                        return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                    })
                    ->orderBy('created_at', 'desc')
                    ->notRefunded()
                    ->sum(DB::raw('admin_commission + delivery_fee_comission'));


            $restaurant_earned = OrderTransaction::with('order','order.details','order.customer','order.restaurant')
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->where('zone_id', $zone->id);
                    })
                    ->when(isset($restaurant), function ($query) use ($restaurant){
                            return $query->whereHas('order', function($q) use ($restaurant){
                                $q->where('restaurant_id', $restaurant->id);
                        });
                    })
                    ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                        return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                    })
                    ->when(isset($filter) && $filter == 'this_year', function ($query) {
                        return $query->whereYear('created_at', now()->format('Y'));
                    })
                    ->when(isset($filter) && $filter == 'this_month', function ($query) {
                        return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                    })
                    ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                        return $query->whereYear('created_at', date('Y') - 1);
                    })
                    ->when(isset($filter) && $filter == 'this_week', function ($query) {
                        return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                    })->orderBy('created_at', 'desc')
                    ->notRefunded()
                    ->sum(DB::raw('restaurant_amount - tax'));

            $deliveryman_earned = OrderTransaction::with('order','order.details','order.customer','order.restaurant')
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->where('zone_id', $zone->id);
                    })
                    ->when(isset($restaurant), function ($query) use ($restaurant){
                            return $query->whereHas('order', function($q) use ($restaurant){
                                $q->where('restaurant_id', $restaurant->id);
                        });
                    })
                    ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                        return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                    })
                    ->when(isset($filter) && $filter == 'this_year', function ($query) {
                        return $query->whereYear('created_at', now()->format('Y'));
                    })
                    ->when(isset($filter) && $filter == 'this_month', function ($query) {
                        return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                    })
                    ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                        return $query->whereYear('created_at', date('Y') - 1);
                    })
                    ->when(isset($filter) && $filter == 'this_week', function ($query) {
                        return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                    })->orderBy('created_at', 'desc')
                    ->sum(DB::raw('original_delivery_charge + dm_tips'));

        return view('admin-views.report.day-wise-report', compact('order_transactions', 'zone', 'from', 'to',
        'restaurant','filter','admin_earned','restaurant_earned','deliveryman_earned'));
    }

    public function day_wise_report_export(Request $request){
        $key = explode(' ', $request['search']);
        $from = null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurant_id = $request->query('restaurant_id', 'all');
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;

        $order_transactions = OrderTransaction::with('order','order.details','order.customer','order.restaurant')
                ->when(isset($zone), function ($query) use ($zone) {
                    return $query->where('zone_id', $zone->id);
                })
                ->when(isset($restaurant), function ($query) use ($restaurant){
                        return $query->whereHas('order', function($q) use ($restaurant){
                            $q->where('restaurant_id', $restaurant->id);
                    });
                })
                ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                    return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                })
                ->when(isset($filter) && $filter == 'this_year', function ($query) {
                    return $query->whereYear('created_at', now()->format('Y'));
                })
                ->when(isset($filter) && $filter == 'this_month', function ($query) {
                    return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                })
                ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                    return $query->whereYear('created_at', date('Y') - 1);
                })
                ->when(isset($filter) && $filter == 'this_week', function ($query) {
                    return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                })
                ->when(isset($request['search']), function ($query) use($key){
                    $query->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('order_id', 'like', "%{$value}%");
                        }
                    });
                })
                ->orderBy('created_at', 'desc')
                ->get();

        if($request->type == 'csv'){
            return (new FastExcel(Helpers::export_day_wise_report($order_transactions)))->download('DayWiseDailyReport.csv');
        }
        return (new FastExcel(Helpers::export_day_wise_report($order_transactions)))->download('DayWiseDailyReport.xlsx');
    }

    public function food_wise_report(Request $request)
    {
        $categories = Category::where(['position' => 0])->get();
        $from =  null;
        $to = null;
        $type = $request->query('type', 'all');
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);
            $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
            $restaurant_id = $request->query('restaurant_id', 'all');
            $category_id = $request->query('category_id', 'all');
            $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
            $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
            $category = is_numeric($category_id) ? Category::findOrFail($category_id) : null;
            $foods = Food::withoutGlobalScopes()
                ->withCount([
                    'orders' => function ($query) use ($from, $to, $filter) {
                            $query->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                            })
                            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                                return $query->whereYear('created_at', now()->format('Y'));
                            })
                            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                            })
                            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                                return $query->whereYear('created_at', date('Y') - 1);
                            })
                            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                            })
                            ->whereHas('order', function($query){
                                return $query->whereIn('order_status',['delivered','refund_requested','refund_request_canceled']);
                            });
                    },
                ])
                ->withSum([
                    'orders' => function ($query) use ($from, $to, $filter) {
                        $query->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                            return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                        })
                            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                                return $query->whereYear('created_at', now()->format('Y'));
                            })
                            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                            })
                            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                                return $query->whereYear('created_at', date('Y') - 1);
                            })
                            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                            })
                            ->whereHas('order', function($query){
                                return $query->whereIn('order_status',['delivered','refund_requested','refund_request_canceled']);
                            });
                    },
                ], 'discount_on_food')
                ->withSum([
                    'orders' => function ($query) use ($from, $to, $filter) {
                        $query->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                            return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                        })
                        ->when(isset($filter) && $filter == 'this_year', function ($query) {
                            return $query->whereYear('created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'this_month', function ($query) {
                            return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                            return $query->whereYear('created_at', date('Y') - 1);
                        })
                        ->when(isset($filter) && $filter == 'this_week', function ($query) {
                            return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                        })
                        ->whereHas('order', function($query){
                            return $query->whereIn('order_status',['delivered','refund_requested','refund_request_canceled']);
                        });
                    },
                ], 'price')
                ->when(isset($request['search']), function ($query) use($key){
                        $query->where(function ($q) use ($key) {
                            foreach ($key as $value) {
                                $q->orWhere('name', 'like', "%{$value}%");
                            }
                    });
                })
                ->when(isset($zone), function ($query) use ($zone) {
                    return $query->whereHas('orders.order', function($query) use($zone){
                        $query->where('zone_id',$zone->id);
                    });
                })
                ->when(isset($restaurant), function ($query) use ($restaurant) {
                    return $query->where('restaurant_id', $restaurant->id);
                })
                ->when(isset($category), function ($query) use ($category) {
                    return $query->where('category_id', $category->id);
                })
                ->when(isset($type) && $type =='veg', function ($query)  {
                    return $query->where('veg', 1);
                })
                ->when(isset($type) && $type =='non_veg', function ($query)  {
                    return $query->where('veg', 0);
                })
                ->with('restaurant')
                ->orderBy('orders_count', 'desc')
                ->paginate(config('default_pagination'))
                ->withQueryString();


                // $restaurant_earnings = OrderDetail::with('order')->whereHas('order',function($query) use($zone ,$restaurant) {
                //         $query->when(isset($zone), function ($query) use ($zone) {
                //             $query->where('zone_id',$zone->id);
                //         })
                //         ->when(isset($restaurant), function ($query) use ($restaurant) {
                //                 $query->where('restaurant_id',$restaurant->id);
                //         })
                //         ->whereIn('order_status',['delivered','refund_requested','refund_request_canceled']);
                //     })->select(
                //         DB::raw('IFNULL(sum(price),0) as earning'),
                //         DB::raw('IFNULL(avg(price ),0) as avg_commission'),
                //         DB::raw('YEAR(created_at) year, MONTH(created_at) month'),
                //     )->when(isset($from) && isset($to) , function ($query) use($from,$to){
                //         $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                //     })
                //     ->when(isset($filter) && $filter == 'this_year', function ($query) {
                //         return $query->whereYear('created_at', now()->format('Y'));
                //     })
                //     ->when(isset($filter) && $filter == 'this_month', function ($query) {
                //         return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                //     })
                //     ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                //         return $query->whereYear('created_at', date('Y') - 1);
                //     })
                //     ->when(isset($filter) && $filter == 'this_week', function ($query) {
                //         return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                //     })
                //     ->when(isset($category), function ($query) use ($category) {
                //         return $query->whereHas('food',function($q) use($category){
                //             $q->where('category_id', $category->id);
                //         });
                //     })
                //     ->when(isset($type) && $type =='veg', function ($query){
                //         return $query->whereHas('food',function($q) {
                //             $q->where('veg', 1);
                //         });
                //     })
                //     ->when(isset($type) && $type =='non_veg', function ($query){
                //         return $query->whereHas('food',function($q) {
                //             $q->where('veg', 0);
                //         });
                //     })
                //     ->groupby('year', 'month')->get()->toArray();
                //     for ($inc = 1; $inc <= 12; $inc++) {
                //         $total_food_sells[$inc] = 0;
                //         $avg_food_sells[$inc] = 0;
                //         foreach ($restaurant_earnings as $match) {
                //             if ($match['month'] == $inc) {
                //                 $total_food_sells[$inc] = $match['earning'];
                //                 $avg_food_sells[$inc] = $match['avg_commission'];
                //             }
                //         }
                //     }


                // ,'total_food_sells','avg_food_sells'
            return view('admin-views.report.food-wise-report', compact('zone',
            'restaurant', 'category_id', 'categories','foods','from', 'to','filter'));
        }

    public function food_wise_report_export(Request $request){
        $from =  null;
        $to = null;
        $type = $request->query('type', 'all');
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);
            $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
            $restaurant_id = $request->query('restaurant_id', 'all');
            $category_id = $request->query('category_id', 'all');
            $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
            $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
            $category = is_numeric($category_id) ? Category::findOrFail($category_id) : null;
            $foods = Food::withoutGlobalScopes()
                ->withCount([
                    'orders' => function ($query) use ($from, $to, $filter) {
                            $query->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                            })
                            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                                return $query->whereYear('created_at', now()->format('Y'));
                            })
                            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                            })
                            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                                return $query->whereYear('created_at', date('Y') - 1);
                            })
                            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                            })
                            ->whereHas('order', function($query){
                                return $query->whereIn('order_status',['delivered','refund_requested','refund_request_canceled']);
                            });
                    },
                ])
                ->withSum([
                    'orders' => function ($query) use ($from, $to, $filter) {
                        $query->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                            return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                        })
                            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                                return $query->whereYear('created_at', now()->format('Y'));
                            })
                            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                            })
                            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                                return $query->whereYear('created_at', date('Y') - 1);
                            })
                            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                            })
                            ->whereHas('order', function($query){
                                return $query->whereIn('order_status',['delivered','refund_requested','refund_request_canceled']);
                            });
                    },
                ], 'discount_on_food')
                ->withSum([
                    'orders' => function ($query) use ($from, $to, $filter) {
                        $query->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                            return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                        })
                        ->when(isset($filter) && $filter == 'this_year', function ($query) {
                            return $query->whereYear('created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'this_month', function ($query) {
                            return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                            return $query->whereYear('created_at', date('Y') - 1);
                        })
                        ->when(isset($filter) && $filter == 'this_week', function ($query) {
                            return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                        })
                        ->whereHas('order', function($query){
                            return $query->whereIn('order_status',['delivered','refund_requested','refund_request_canceled']);
                        });
                    },
                ], 'price')
                ->when(isset($request['search']), function ($query) use($key){
                        $query->where(function ($q) use ($key) {
                            foreach ($key as $value) {
                                $q->orWhere('name', 'like', "%{$value}%");
                            }
                    });
                })
                ->when(isset($zone), function ($query) use ($zone) {
                    return $query->whereHas('orders.order', function($query) use($zone){
                        $query->where('zone_id',$zone->id);
                    });
                })
                ->when(isset($restaurant), function ($query) use ($restaurant) {
                    return $query->where('restaurant_id', $restaurant->id);
                })
                ->when(isset($category), function ($query) use ($category) {
                    return $query->where('category_id', $category->id);
                })
                ->when(isset($type) && $type =='veg', function ($query)  {
                    return $query->where('veg', 1);
                })
                ->when(isset($type) && $type =='non_veg', function ($query)  {
                    return $query->where('veg', 0);
                })
                ->with('restaurant')
                ->orderBy('orders_count', 'desc')
                ->get();

        if($request->type == 'csv'){
            return (new FastExcel(Helpers::food_wise_report_export($foods)))->download('FoodWiseReport.csv');
        }
        return (new FastExcel(Helpers::food_wise_report_export($foods)))->download('FoodWiseReport.xlsx');
    }


    public function order_transaction()
    {
        $order_transactions = OrderTransaction::latest()->paginate(config('default_pagination'));
        return view('admin-views.report.order-transactions', compact('order_transactions'));
    }


    public function set_date(Request $request)
    {
        session()->put('from_date', date('Y-m-d', strtotime($request['from'])));
        session()->put('to_date', date('Y-m-d', strtotime($request['to'])));
        return back();
    }

    public function expense_report(Request $request)
    {
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);

        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurant_id = $request->query('restaurant_id', 'all');
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
        $customer_id = $request->query('customer_id', 'all');
        $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;

        $expense = Expense::with('order','order.customer:id,f_name,l_name')->where('created_by','admin')
            ->when(isset($zone) || isset($restaurant) || isset($customer), function ($query) use ($zone,$restaurant,$customer) {
                return $query->whereHas('order', function($query) use ($zone,$restaurant,$customer) {
                    $query->when($zone, function ($query) use ($zone) {
                        return $query->where('zone_id', $zone->id);
                    });
                    $query->when($restaurant, function ($query) use ($restaurant) {
                        return $query->where('restaurant_id', $restaurant->id);
                    });
                    $query->when($customer, function ($query) use ($customer) {
                        return $query->where('user_id', $customer->id);
                    });
                });
            })
            ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('created_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->when( isset($key), function($query) use($key){
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('type', 'like', "%{$value}%")->orWhere('order_id', 'like', "%{$value}%");
                    }
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(config('default_pagination'))->withQueryString();

        return view('admin-views.report.expense-report', compact('expense','zone', 'restaurant','filter','customer','from','to'));
    }


    public function expense_export(Request $request)
    {
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);

        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurant_id = $request->query('restaurant_id', 'all');
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
        $customer_id = $request->query('customer_id', 'all');
        $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;

        $expense = Expense::with('order','order.customer:id,f_name,l_name')->where('created_by','admin')
            ->when(isset($zone) || isset($restaurant) || isset($customer), function ($query) use ($zone,$restaurant,$customer) {
                return $query->whereHas('order', function($query) use ($zone,$restaurant,$customer) {
                    $query->when($zone, function ($query) use ($zone) {
                        return $query->where('zone_id', $zone->id);
                    });
                    $query->when($restaurant, function ($query) use ($restaurant) {
                        return $query->where('restaurant_id', $restaurant->id);
                    });
                    $query->when($customer, function ($query) use ($customer) {
                        return $query->where('user_id', $customer->id);
                    });
                });
            })
            ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('created_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->when( isset($key), function($query) use($key){
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('type', 'like', "%{$value}%")->orWhere('order_id', 'like', "%{$value}%");
                    }
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();
        if ($request->type == 'csv') {
            return (new FastExcel(Helpers::export_expense_wise_report($expense)))->download('ExpenseReport.csv');
        }
        return (new FastExcel(Helpers::export_expense_wise_report($expense)))->download('ExpenseReport.xlsx');
    }

    public function subscription_report(Request $request)
    {
        $from =  null;
        $to = null;

        $restaurant_id = $request->query('restaurant_id', 'all');
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
        $filter = $request->query('filter', 'all_time');

        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);
        $payment_type = $request->query('payment_type', 'all');

        $subscriptions = SubscriptionTransaction::with('restaurant')
                ->when(isset($restaurant), function ($query) use ($restaurant){
                    return $query->where('restaurant_id', $restaurant->id);
                })
                ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                    return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                })
                ->when(isset($payment_type) && $payment_type == 'wallet_payment', function ($query) {
                    return $query->where('payment_method', 'wallet');
                })
                ->when(isset($payment_type) && $payment_type == 'manual_payment', function ($query) {
                    return $query->whereIn('payment_method',[ 'manual_payment_by_restaurant','manual_payment_admin']);
                })
                ->when(isset($payment_type) && $payment_type == 'digital_payment', function ($query) {
                    return $query->where('payment_method', 'digital_payment');
                })
                ->when(isset($payment_type) && $payment_type == 'free_trial', function ($query) {
                    return $query->where('payment_method', 'free_trial');
                })
                ->when(isset($filter) && $filter == 'this_year', function ($query) {
                    return $query->whereYear('created_at', now()->format('Y'));
                })
                ->when(isset($filter) && $filter == 'this_month', function ($query) {
                    return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                })
                ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                    return $query->whereYear('created_at', date('Y') - 1);
                })
                ->when(isset($filter) && $filter == 'this_week', function ($query) {
                    return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                })->when(isset($request['search']), function ($query) use($key){
                    $query->where(function ($qu) use ($key){
                        $qu->where(function ($q) use ($key) {
                            foreach ($key as $value) {
                                $q->orWhere('id', 'like', "%{$value}%")
                                    ->orWhere('paid_amount', 'like', "%{$value}%")
                                    ->orWhere('reference', 'like', "%{$value}%");
                            }
                        })->orwhereHas('restaurant',function($query)use($key){
                            foreach ($key as $v) {
                                $query->where('name', 'like', "%{$v}%")
                                        ->orWhere('email', 'like', "%{$v}%");
                            }
                        });
                    });
                })

                ->orderBy('created_at', 'desc')
                ->paginate(config('default_pagination'))->withQueryString();

        return view('admin-views.report.subscription-report', compact('subscriptions','restaurant','filter','payment_type','to','from'));
    }

    public function subscription_export(Request $request)
    {
        $from =  null;
        $to = null;
        $restaurant_id = $request->query('restaurant_id', 'all');
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $payment_type = $request->query('payment_type', 'all');

        $subscriptions = SubscriptionTransaction::with('restaurant')
                ->when(isset($restaurant), function ($query) use ($restaurant){
                    return $query->where('restaurant_id', $restaurant->id);
                })
                ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                    return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                })
                ->when(isset($payment_type) && $payment_type == 'wallet_payment', function ($query) {
                    return $query->where('payment_method', 'wallet');
                })
                ->when(isset($payment_type) && $payment_type == 'manual_payment', function ($query) {
                    return $query->whereIn('payment_method',[ 'manual_payment_by_restaurant','manual_payment_admin']);
                })
                ->when(isset($payment_type) && $payment_type == 'digital_payment', function ($query) {
                    return $query->where('payment_method', 'digital_payment');
                })
                ->when(isset($payment_type) && $payment_type == 'free_trial', function ($query) {
                    return $query->where('payment_method', 'free_trial');
                })
                ->when(isset($filter) && $filter == 'this_year', function ($query) {
                    return $query->whereYear('created_at', now()->format('Y'));
                })
                ->when(isset($filter) && $filter == 'this_month', function ($query) {
                    return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                })
                ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                    return $query->whereYear('created_at', date('Y') - 1);
                })
                ->when(isset($filter) && $filter == 'this_week', function ($query) {
                    return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                })
                ->orderBy('created_at', 'desc')
                ->get();

        if($request->type == 'csv'){
            return (new FastExcel(Helpers::export_subscription($subscriptions)))->download('SubscriptionsReport.csv');
        }
        return (new FastExcel(Helpers::export_subscription($subscriptions)))->download('SubscriptionsReport.xlsx');
    }

    public function campaign_order_report(Request $request){
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurant_id = $request->query('restaurant_id', 'all');
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
        $customer_id = $request->query('customer_id', 'all');
        $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;
        $campaign_id = $request->query('campaign_id', 'all');
        $key = explode(' ', $request['search']);

        $orders = Order::with(['customer', 'restaurant','details','transaction'])
            ->whereHas('details',function ($query){
                $query->whereNotNull('item_campaign_id');
            })
            ->when(is_numeric($campaign_id), function ($query) use ($campaign_id) {
                return $query->whereHas('details',function ($query) use ($campaign_id){
                    $query->where('item_campaign_id',$campaign_id);
                });
            })
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->where('zone_id', $zone->id);
            })
            ->when(isset($restaurant), function ($query) use ($restaurant) {
                return $query->where('restaurant_id', $restaurant->id);
            })
            ->when(isset($customer), function ($query) use ($customer) {
                return $query->where('user_id', $customer->id);
            })
            ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('schedule_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->when(isset($request['search']), function ($query) use($key){
                $query->where(function ($qu)use ($key){
                    $qu->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('id', 'like', "%{$value}%");
                        }
                    })->orwhereHas('restaurant',function($query)use($key){
                        foreach ($key as $v) {
                            $query->where('name', 'like', "%{$v}%")
                                    ->orWhere('email', 'like', "%{$v}%");
                        }
                    });
                });
            })
            ->withSum('transaction', 'admin_commission')
            ->withSum('transaction', 'admin_expense')
            ->withSum('transaction', 'delivery_fee_comission')
            ->orderBy('schedule_at', 'desc')
            ->paginate(config('default_pagination'))
            ->withQueryString();

        $orders_list = Order::
        whereHas('details',function ($query){
            $query->whereNotNull('item_campaign_id');
        })
        ->when(is_numeric($campaign_id), function ($query) use ($campaign_id) {
        return $query->whereHas('details',function ($query) use ($campaign_id){
            $query->where('item_campaign_id',$campaign_id);
                });
        })
        ->when(isset($zone), function ($query) use ($zone) {
            return $query->where('zone_id', $zone->id);
        })
        ->when(isset($restaurant), function ($query) use ($restaurant) {
            return $query->where('restaurant_id', $restaurant->id);
        })
        ->when(isset($customer), function ($query) use ($customer) {
            return $query->where('user_id', $customer->id);
        })
        ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
            return $query->whereBetween('schedule_at', [$from . " 00:00:00", $to . " 23:59:59"]);
        })
        ->when(isset($filter) && $filter == 'this_year', function ($query) {
            return $query->whereYear('schedule_at', now()->format('Y'));
        })
        ->when(isset($filter) && $filter == 'this_month', function ($query) {
            return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
        })
        ->when(isset($filter) && $filter == 'previous_year', function ($query) {
            return $query->whereYear('schedule_at', date('Y') - 1);
        })
        ->when(isset($filter) && $filter == 'this_week', function ($query) {
            return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
        })
        ->orderBy('schedule_at', 'desc')->get();

        $total_order_amount = $orders_list->sum('order_amount');
        $total_coupon_discount = $orders_list->sum('coupon_discount_amount');
        $total_product_discount = $orders_list->sum('restaurant_discount_amount');

        $total_canceled_count = $orders_list->where('order_status', 'canceled')->count();
        $total_delivered_count = $orders_list->where('order_status', 'delivered')->where('order_type', '<>' , 'pos')->count();
        $total_progress_count = $orders_list->whereIn('order_status', ['accepted','confirmed','processing','handover'])->count();
        $total_failed_count = $orders_list->where('order_status', 'failed')->count();
        $total_refunded_count = $orders_list->where('order_status', 'refunded')->count();
        $total_on_the_way_count = $orders_list->whereIn('order_status', ['picked_up'])->count();
        $total_orders = $orders_list->count();
        return view('admin-views.report.campaign_order-report', compact('orders','orders_list','zone', 'campaign_id','from','to','total_orders',
        'restaurant','filter','customer','total_on_the_way_count','total_refunded_count','total_failed_count','total_progress_count','total_canceled_count','total_delivered_count'));
    }

    public function campaign_report_export(Request $request)
    {
        $key = isset($request['search']) ? explode(' ', $request['search']) : [];
        $filter = $request->query('filter', 'all_time');
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurant_id = $request->query('restaurant_id', 'all');
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
        $customer_id = $request->query('customer_id', 'all');
        $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;
        $campaign_id = $request->query('campaign_id', 'all');

        $orders = Order::with(['customer', 'restaurant','details'])
        ->whereHas('details',function ($query){
            $query->whereNotNull('item_campaign_id');
        })
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->where('zone_id', $zone->id);
            })
            ->when(is_numeric($campaign_id), function ($query) use ($campaign_id) {
                return $query->whereHas('details',function ($query) use ($campaign_id){
                    $query->where('item_campaign_id',$campaign_id);
                });
            })
            ->when(isset($restaurant), function ($query) use ($restaurant) {
                return $query->where('restaurant_id', $restaurant->id);
            })
            ->when(isset($customer), function ($query) use ($customer) {
                return $query->where('user_id', $customer->id);
            })
            ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('schedule_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->when(isset($request['search']), function ($query) use($key){
                $query->where(function ($qu)use ($key){
                    $qu->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('id', 'like', "%{$value}%");
                        }
                    })->orwhereHas('restaurant',function($query)use($key){
                        foreach ($key as $v) {
                            $query->where('name', 'like', "%{$v}%")
                                    ->orWhere('email', 'like', "%{$v}%");
                        }
                    });
                });
            })
            ->orderBy('schedule_at', 'desc')
            ->get();

        if ($request->type == 'csv') {
            return (new FastExcel(OrderLogic::format_order_report_export_data($orders)))->download('CampaignOrders.csv');
        }
        return (new FastExcel(OrderLogic::format_order_report_export_data($orders)))->download('CampaignOrders.xlsx');
    }

    public function order_report(Request $request){
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurant_id = $request->query('restaurant_id', 'all');
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
        $customer_id = $request->query('customer_id', 'all');
        $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;

        $orders = Order::with(['customer', 'restaurant','details','transaction'])
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->where('zone_id', $zone->id);
            })
            ->when(isset($restaurant), function ($query) use ($restaurant) {
                return $query->where('restaurant_id', $restaurant->id);
            })
            ->when(isset($customer), function ($query) use ($customer) {
                return $query->where('user_id', $customer->id);
            })
            ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('schedule_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->when(isset($key), function($query) use($key){
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%");
                    }
                });
            })

            ->withSum('transaction', 'admin_commission')
            ->withSum('transaction', 'admin_expense')
            ->withSum('transaction', 'delivery_fee_comission')
            ->orderBy('schedule_at', 'desc')->paginate(config('default_pagination'))->withQueryString();

        // order card values calculation
        $orders_list = Order::
        when(isset($zone), function ($query) use ($zone) {
            return $query->where('zone_id', $zone->id);
        })
        ->when(isset($restaurant), function ($query) use ($restaurant) {
            return $query->where('restaurant_id', $restaurant->id);
        })
        ->when(isset($customer), function ($query) use ($customer) {
            return $query->where('user_id', $customer->id);
        })
        ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
            return $query->whereBetween('schedule_at', [$from . " 00:00:00", $to . " 23:59:59"]);
        })
        ->when(isset($filter) && $filter == 'this_year', function ($query) {
            return $query->whereYear('schedule_at', now()->format('Y'));
        })
        ->when(isset($filter) && $filter == 'this_month', function ($query) {
            return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
        })
        ->when(isset($filter) && $filter == 'previous_year', function ($query) {
            return $query->whereYear('schedule_at', date('Y') - 1);
        })
        ->when(isset($filter) && $filter == 'this_week', function ($query) {
            return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
        })
        ->orderBy('schedule_at', 'desc')->get();

        $total_order_amount = $orders_list->sum('order_amount');
        $total_coupon_discount = $orders_list->sum('coupon_discount_amount');
        $total_product_discount = $orders_list->sum('restaurant_discount_amount');

        $total_canceled_count = $orders_list->where('order_status', 'canceled')->count();
        $total_delivered_count = $orders_list->where('order_status', 'delivered')->where('order_type', '<>' , 'pos')->count();
        $total_progress_count = $orders_list->whereIn('order_status', ['accepted','confirmed','processing','handover'])->count();
        $total_failed_count = $orders_list->where('order_status', 'failed')->count();
        $total_refunded_count = $orders_list->where('order_status', 'refunded')->count();
        $total_on_the_way_count = $orders_list->whereIn('order_status', ['picked_up'])->count();
        $total_accepted_count = $orders_list->where('order_status', 'accepted')->count();
        $total_pending_count = $orders_list->where('order_status', 'pending')->count();
        $total_scheduled_count = $orders_list->where('scheduled', 1)->count();

        return view('admin-views.report.order-report', compact('orders','orders_list','zone', 'restaurant','from','to','total_accepted_count','total_pending_count','total_scheduled_count',
        'filter','customer','total_on_the_way_count','total_refunded_count','total_failed_count','total_progress_count','total_canceled_count','total_delivered_count'));
    }

    public function order_report_export(Request $request)
    {
        $key = isset($request['search']) ? explode(' ', $request['search']) : [];

        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurant_id = $request->query('restaurant_id', 'all');
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
        $customer_id = $request->query('customer_id', 'all');
        $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;
        $filter = $request->query('filter', 'all_time');

        $orders = Order::with(['customer', 'restaurant'])
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->where('zone_id', $zone->id);
            })
            ->when(isset($restaurant), function ($query) use ($restaurant) {
                return $query->where('restaurant_id', $restaurant->id);
            })
            ->when(isset($customer), function ($query) use ($customer) {
                return $query->where('user_id', $customer->id);
            })
            ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('schedule_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->when(isset($key), function($query) use($key){
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%");
                    }
                });
            })
            ->withSum('transaction', 'admin_commission')
            ->withSum('transaction', 'admin_expense')
            ->withSum('transaction', 'delivery_fee_comission')
            ->orderBy('schedule_at', 'desc')->get();

        if ($request->type == 'csv') {
            return (new FastExcel(OrderLogic::format_order_report_export_data($orders)))->download('Orders.csv');
        }
        return (new FastExcel(OrderLogic::format_order_report_export_data($orders)))->download('Orders.xlsx');
    }

    public function restaurant_report(Request $request)
    {
        $months = array(
            '"Jan"',
            '"Feb"',
            '"Mar"',
            '"Apr"',
            '"May"',
            '"Jun"',
            '"Jul"',
            '"Aug"',
            '"Sep"',
            '"Oct"',
            '"Nov"',
            '"Dec"'
        );
        $days = array(
            '"Sun"',
            '"Mon"',
            '"Tue"',
            '"Wed"',
            '"Thu"',
            '"Fri"',
            '"Sat"'
        );
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $restaurant_model = $request->query('restaurant_model', 'all');
        $type = $request->query('type', 'all');
        $key = explode(' ', $request['search']);
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurants = Restaurant::with('reviews','vendor')
                ->whereHas('vendor',function($query){
                    return $query->where('status',1);
                })
                ->withSum('reviews' , 'rating')
                ->withCount(['reviews','foods'=> function ($query)use ($from, $to, $filter) {
                    $query->withoutGlobalScopes()
                    ->when(isset($from) && isset($to)  && $filter == 'custom', function ($q) use ($from, $to){
                        return $q->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                    })
                    ->when(isset($filter) && $filter == 'this_year', function ($query) {
                        return $query->whereYear('created_at', now()->format('Y'));
                    })
                    ->when(isset($filter) && $filter == 'this_month', function ($query) {
                        return $query->whereMonth('created_at', now()->format('m'));
                    })
                    ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                        return $query->whereYear('created_at','<', date('Y') - 1);
                    })
                    ->when(isset($filter) && $filter == 'this_week', function ($query) {
                        return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                    });
                    },
                    'transaction as without_refund_total_orders_count' => function ($query)use ($from, $to, $filter) {
                                $query->NotRefunded()
                                ->when(isset($from) && isset($to)  && $filter == 'custom', function ($q) use ($from, $to){
                                    $q->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                                })
                                ->when(isset($filter) && $filter == 'this_year', function ($query) {
                                    return $query->whereYear('created_at', now()->format('Y'));
                                })
                                ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                    return $query->whereMonth('created_at', now()->format('m'));
                                })
                                ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                                    return $query->whereYear('created_at', date('Y') - 1);
                                })
                                ->when(isset($filter) && $filter == 'this_week', function ($query) {
                                    return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                                });
                    },])
                ->withSum([
                    'transaction' => function ($query) use ($from, $to, $filter) {
                                $query->NotRefunded()
                                    ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                                        return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                                    })
                                    ->when(isset($filter) && $filter == 'this_year', function ($query) {
                                        return $query->whereYear('created_at', now()->format('Y'));
                                    })
                                    ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                        return $query->whereMonth('created_at', now()->format('m'));
                                    })
                                    ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                                        return $query->whereYear('created_at',date('Y') - 1);
                                    })
                                    ->when(isset($filter) && $filter == 'this_week', function ($query) {
                                        return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                                    });
                            },
                        ], 'order_amount')
                ->withSum([ 'transaction' => function ($query) use ($from, $to, $filter) {
                                $query->NotRefunded()
                                ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                                    return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                                })
                                    ->when(isset($filter) && $filter == 'this_year', function ($query) {
                                        return $query->whereYear('created_at', now()->format('Y'));
                                    })
                                    ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                        return $query->whereMonth('created_at', now()->format('m'));
                                    })
                                    ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                                        return $query->whereYear('created_at', date('Y') - 1);
                                    })
                                    ->when(isset($filter) && $filter == 'this_week', function ($query) {
                                        return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                                    });
                            },
                        ], 'tax')
                ->withSum([
                    'transaction as transaction_sum_restaurant_expense'  => function ($query) use ($from, $to, $filter) {
                        $query->NotRefunded()
                        ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                            return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                        })
                            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                                return $query->whereYear('created_at', now()->format('Y'));
                            })
                            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                return $query->whereMonth('created_at', now()->format('m'));
                            })
                            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                                return $query->whereYear('created_at', date('Y') - 1);
                            })
                            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                            });
                    },
                    ], 'discount_amount_by_restaurant')
                ->withSum([
                        'transaction' => function ($query) use ($from, $to, $filter) {
                            $query->NotRefunded()
                            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($q) use ($from, $to){
                                $q->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                            })
                            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                                return $query->whereYear('created_at', now()->format('Y'));
                            })
                            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                return $query->whereMonth('created_at', now()->format('m'));
                            })
                            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                                return $query->whereYear('created_at', date('Y') - 1);
                            })
                            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                            });
                        },
                    ], 'admin_commission')

                ->when(isset($zone), function ($query) use ($zone) {
                    return $query->where('zone_id', $zone->id);
                })
                ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                    return $query->whereHas('transaction', function($q)use ($from, $to){
                        return $q->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                    });
                })
                ->when(isset($filter) && $filter == 'this_year', function ($query) {
                    return $query->whereHas('transaction', function($q){
                        return $q->whereYear('created_at', now()->format('Y'));
                    });
                })
                ->when(isset($filter) && $filter == 'this_month', function ($query) {
                    return $query->whereHas('transaction', function($q){
                        return $q->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                    });
                })
                ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                    return $query->whereHas('transaction', function($q){
                        return $q->whereYear('created_at' , date('Y') - 1);
                    });
                })
                ->when(isset($filter) && $filter == 'this_week', function ($query) {
                    return $query->whereHas('transaction', function($q){
                        return $q->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                    });
                })
                ->when(isset($restaurant_model), function ($query) use($restaurant_model) {
                    return $query->RestaurantModel($restaurant_model);
                })
                ->when(isset($type), function ($query) use($type) {
                    return $query->Type($type);
                })
                ->when(isset($request['search']), function ($query) use($key){
                    $query->where(function ($qu) use ($key){
                            foreach ($key as $value) {
                                $qu->orWhere('name', 'like', "%{$value}%")
                                    ->orWhere('email', 'like', "%{$value}%");
                            }
                    });
                })
                ->orderBy('created_at', 'asc')
                ->paginate(config('default_pagination'))
                ->withQueryString();

        $monthly_order = [];
        $data = [];
        $data_avg = [];

        switch ($filter) {
            case "all_time":
                $monthly_order = OrderTransaction::NotRefunded()
                ->when(isset($zone), function ($query) use ($zone) {
                    return $query->whereHas('restaurant',function($q)use ($zone){
                        $q->where('zone_id', $zone->id);
                    });
                })
                ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                    return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                        $q->RestaurantModel($restaurant_model);
                    });
                })
                ->when(isset($type), function ($query) use ($type) {
                    return $query->whereHas('restaurant',function($q)use ($type){
                        $q->Type($type);
                    });
                })
                ->select(
                    DB::raw("(sum(order_amount)) as order_amount"),
                    DB::raw("(avg(order_amount)) as order_amount_avg"),
                    DB::raw("(DATE_FORMAT(created_at, '%Y')) as year")
                )
                    ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y')"))
                    ->get()->toArray();
                $label = array_map(function ($order) {
                    return $order['year'];
                }, $monthly_order);
                $data = array_map(function ($order) {
                    return $order['order_amount'];
                }, $monthly_order);
                $data_avg = array_map(function ($order) {
                    return $order['order_amount_avg'];
                }, $monthly_order);

            break;
            case "this_year":
                for ($i = 1; $i <= 12; $i++) {
                    $monthly_order[$i] = OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', now()->format('Y'))
                    ->sum('order_amount');
                    $monthly_order_avg[$i] = OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', now()->format('Y'))
                    ->avg('order_amount');
                }
                $label = $months;
                $data = $monthly_order;
                $data_avg = $monthly_order_avg;

            break;
            case "custom":
                for ($i = 1; $i <= 12; $i++) {
                    $monthly_order[$i] = OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereMonth('created_at', $i)
                    ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                        return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                    })
                    ->sum('order_amount');
                    $monthly_order_avg[$i] = OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                        return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                    })
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', now()->format('Y'))
                    ->avg('order_amount');
                }
                $label = $months;
                $data = $monthly_order;
                $data_avg = $monthly_order_avg;
            break;
            case "previous_year":
                for ($i = 1; $i <= 12; $i++) {
                    $monthly_order[$i] =OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', date('Y') - 1)
                    ->sum('order_amount');
                    $monthly_order_avg[$i] =OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', date('Y') - 1)
                    ->avg('order_amount');
                }
                $label = $months;
                $data = $monthly_order;
                $data_avg = $monthly_order_avg;
            break;
            case "this_week":
                $weekStartDate = now()->startOfWeek();
                for ($i = 1; $i <= 7; $i++) {
                    $monthly_order[$i] = OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereDay('created_at', $weekStartDate->format('d'))->whereMonth('created_at', now()->format('m'))
                    ->sum('order_amount');
                    $weekStartDate = $weekStartDate->addDays(1);
                    $monthly_order_avg[$i] = OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereDay('created_at', $weekStartDate->format('d'))->whereMonth('created_at', now()->format('m'))
                    ->avg('order_amount');
                    $weekStartDate = $weekStartDate->addDays(1);
                }
                $label = $days;
                $data = $monthly_order;
                $data_avg = $monthly_order_avg;
            break;
            case "this_month":
                $start = now()->startOfMonth();
                $end = now()->startOfMonth()->addDays(7);
                $total_day = now()->daysInMonth;
                $remaining_days = now()->daysInMonth - 28;
                $weeks = array(
                    '"Day 1-7"',
                    '"Day 8-14"',
                    '"Day 15-21"',
                    '"Day 22-' . $total_day . '"',
                );
                for ($i = 1; $i <= 4; $i++) {
                    $monthly_order[$i] =  OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                        ->whereBetween('created_at', ["{$start->format('Y-m-d')} 00:00:00", "{$end->format('Y-m-d')} 23:59:59"])
                        ->sum('order_amount');

                    $monthly_order_avg[$i] =  OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                        ->whereBetween('created_at', ["{$start->format('Y-m-d')} 00:00:00", "{$end->format('Y-m-d')} 23:59:59"])
                        ->avg('order_amount');
                    $start = $start->addDays(7);
                    $end = $i == 3 ? $end->addDays(7 + $remaining_days) : $end->addDays(7);
                }
                $label = $weeks;
                $data = $monthly_order;
                $data_avg = $monthly_order_avg;
            break;
            default:
                for ($i = 1; $i <= 12; $i++) {
                    $monthly_order[$i] = OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereMonth('created_at', $i)->whereYear('created_at', now()->format('Y'))
                    ->sum('order_amount');
                    $monthly_order_avg[$i] = OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereMonth('created_at', $i)->whereYear('created_at', now()->format('Y'))
                    ->avg('order_amount');
                }
                $label = $months;
                $data = $monthly_order;
                $data_avg = $monthly_order_avg;
            }
        return view('admin-views.report.restaurant_report', compact('restaurants','filter','zone','to','from',  'monthly_order', 'label', 'data','data_avg'
                    ));
    }
    public function restaurant_export(Request $request)
    {
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $restaurant_model = $request->query('restaurant_model', 'all');
        $type = $request->query('type', 'all');
        $key = explode(' ', $request['search']);
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurants = Restaurant::with('reviews','vendor')
        ->whereHas('vendor',function($query){
            return $query->where('status',1);
        })
        ->withSum('reviews' , 'rating')
        ->withCount(['reviews','foods'=> function ($query)use ($from, $to, $filter) {
                $query->withoutGlobalScopes()
                ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($q) use ($from, $to){
                    return $q->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                })
                ->when(isset($filter) && $filter == 'this_year', function ($query) {
                    return $query->whereYear('created_at', now()->format('Y'));
                })
                ->when(isset($filter) && $filter == 'this_month', function ($query) {
                    return $query->whereMonth('created_at', now()->format('m'));
                })
                ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                    return $query->whereYear('created_at','<', date('Y') - 1);
                })
                ->when(isset($filter) && $filter == 'this_week', function ($query) {
                    return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                });
            },
                'transaction as without_refund_total_orders_count' => function ($query)use ($from, $to, $filter) {
                            $query->NotRefunded()
                            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($q) use ($from, $to){
                                $q->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                            })
                            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                                return $query->whereYear('created_at', now()->format('Y'));
                            })
                            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                return $query->whereMonth('created_at', now()->format('m'));
                            })
                            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                                return $query->whereYear('created_at', date('Y') - 1);
                            })
                            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                            });
                },])
            ->withSum([
                'transaction' => function ($query) use ($from, $to, $filter) {
                                $query->NotRefunded()
                                    ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                                        return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                                    })
                                    ->when(isset($filter) && $filter == 'this_year', function ($query) {
                                        return $query->whereYear('created_at', now()->format('Y'));
                                    })
                                    ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                        return $query->whereMonth('created_at', now()->format('m'));
                                    })
                                    ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                                        return $query->whereYear('created_at',date('Y') - 1);
                                    })
                                    ->when(isset($filter) && $filter == 'this_week', function ($query) {
                                        return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                                    });
                            },
                        ], 'order_amount')
            ->withSum([ 'transaction' => function ($query) use ($from, $to, $filter) {
                            $query->NotRefunded()
                            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                            })
                                ->when(isset($filter) && $filter == 'this_year', function ($query) {
                                    return $query->whereYear('created_at', now()->format('Y'));
                                })
                                ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                    return $query->whereMonth('created_at', now()->format('m'));
                                })
                                ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                                    return $query->whereYear('created_at', date('Y') - 1);
                                })
                                ->when(isset($filter) && $filter == 'this_week', function ($query) {
                                    return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                                });
                            },
                    ], 'tax')
            ->withSum([
                    'transaction as transaction_sum_restaurant_expense'  => function ($query) use ($from, $to, $filter) {
                        $query->NotRefunded()
                        ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                            return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                        })
                            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                                return $query->whereYear('created_at', now()->format('Y'));
                            })
                            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                return $query->whereMonth('created_at', now()->format('m'));
                            })
                            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                                return $query->whereYear('created_at', date('Y') - 1);
                            })
                            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                            });
                    },
                    ], 'discount_amount_by_restaurant')
            ->withSum([
                    'transaction' => function ($query) use ($from, $to, $filter) {
                        $query->NotRefunded()
                        ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($q) use ($from, $to){
                            $q->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                        })
                        ->when(isset($filter) && $filter == 'this_year', function ($query) {
                            return $query->whereYear('created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'this_month', function ($query) {
                            return $query->whereMonth('created_at', now()->format('m'));
                        })
                        ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                            return $query->whereYear('created_at', date('Y') - 1);
                        })
                        ->when(isset($filter) && $filter == 'this_week', function ($query) {
                            return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                        });
                    },
                    ], 'admin_commission')

                ->when(isset($zone), function ($query) use ($zone) {
                    return $query->where('zone_id', $zone->id);
                })
                ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                    return $query->whereHas('transaction', function($q)use ($from, $to){
                        return $q->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                    });
                })
                ->when(isset($filter) && $filter == 'this_year', function ($query) {
                    return $query->whereHas('transaction', function($q){
                        return $q->whereYear('created_at', now()->format('Y'));
                    });
                })
                ->when(isset($filter) && $filter == 'this_month', function ($query) {
                    return $query->whereHas('transaction', function($q){
                        return $q->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                    });
                })
                ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                    return $query->whereHas('transaction', function($q){
                        return $q->whereYear('created_at' , date('Y') - 1);
                    });
                })
                ->when(isset($filter) && $filter == 'this_week', function ($query) {
                    return $query->whereHas('transaction', function($q){
                        return $q->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                    });
                })
                ->when(isset($restaurant_model), function ($query) use($restaurant_model) {
                    return $query->RestaurantModel($restaurant_model);
                })
                ->when(isset($type), function ($query) use($type) {
                    return $query->Type($type);
                })
                ->when(isset($request['search']), function ($query) use($key){
                    $query->where(function ($qu) use ($key){
                            foreach ($key as $value) {
                                $qu->orWhere('name', 'like', "%{$value}%")
                                    ->orWhere('email', 'like', "%{$value}%");
                            }
                    });
                })
                ->orderBy('created_at', 'asc')
                ->get();

            if ($request->type == 'csv') {
                return (new FastExcel(RestaurantLogic::format_restaurant_report_export_data($restaurants)))->download('restaurant.csv');
            }
            return (new FastExcel(RestaurantLogic::format_restaurant_report_export_data($restaurants)))->download('restaurant.xlsx');
    }
    public function generate_statement($id)
    {
        $key =['phone','email_address','footer_text','business_name','logo'];
        $settings =  array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');

        $company_phone =$settings['phone'] ?? null;
        $company_email =$settings['email_address'] ?? null;
        $company_name =$settings['business_name'] ?? null;
        $company_web_logo =$settings['logo'] ?? null;
        $footer_text = $settings['footer_text'] ?? null;

        $order_transaction = OrderTransaction::with('order','order.details','order.customer','order.restaurant')->where('id', $id)->first();
        $data["email"] = $order_transaction->order->customer !=null?$order_transaction->order->customer["email"]: translate('email_not_found');
        $data["client_name"] = $order_transaction->order->customer !=null? $order_transaction->order->customer["f_name"] . ' ' . $order_transaction->order->customer["l_name"]: translate('customer_not_found');
        $data["order_transaction"] = $order_transaction;
        $mpdf_view = View::make('admin-views.report.order-transaction-statement',
            compact('order_transaction', 'company_phone', 'company_name', 'company_email', 'company_web_logo', 'footer_text')
        );
        Helpers::gen_mpdf(view: $mpdf_view,file_prefix: 'order_trans_statement',file_postfix: $order_transaction->id);
    }
    public function subscription_generate_statement($id)
    {
        $key =['phone','email_address','footer_text','business_name','logo'];
        $settings =  array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');

        $company_phone =$settings['phone'] ?? null;
        $company_email =$settings['email_address'] ?? null;
        $company_name =$settings['business_name'] ?? null;
        $company_web_logo =$settings['logo'] ?? null;
        $footer_text = $settings['footer_text'] ?? null;

        $transaction = SubscriptionTransaction::with('restaurant')->where('id', $id)->first();
        $data["email"] = $transaction->restaurant !=null?$transaction->restaurant->email: translate('email_not_found');
        $data["client_name"] = $transaction->restaurant !=null? $transaction->restaurant->name: translate('customer_not_found');
        $data["transaction"] = $transaction;
        $mpdf_view = View::make('admin-views.report.subs-transaction-statement',
            compact('transaction', 'company_phone', 'company_name', 'company_email', 'company_web_logo', 'footer_text')
        );
        // return view('admin-views.report.subs-transaction-statement',
        //     compact('transaction', 'company_phone', 'company_name', 'company_email', 'company_web_logo', 'footer_text'));
        Helpers::gen_mpdf(view: $mpdf_view,file_prefix: 'transaction',file_postfix: $transaction->id);
    }
}
