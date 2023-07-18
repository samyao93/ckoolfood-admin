<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;

class Subscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$module)
    {
        // if(Helpers::subscription_check()== true){
            if (auth('vendor_employee')->check() || auth('vendor')->check()) {
                $restaurant= Helpers::get_restaurant_data();
                if($restaurant->restaurant_model== 'commission'){
                    return $next($request);
                } elseif($restaurant->restaurant_model == 'subscription') {
                        if($restaurant->restaurant_sub == null){
                            Toastr::error(translate('messages.you_are_not_subscribed_to_any_package'));
                            // return redirect()->route('restaurant.back',['restaurant_id' => encrypt($restaurant->id)]);
                            return back();
                        } else {
                        $rest_sub=$restaurant->restaurant_sub;
                        $review=$rest_sub->review ?? 0;
                        $pos=$rest_sub->pos ?? 0;
                        $self_delivery=$rest_sub->self_delivery ?? 0;
                        $chat=$rest_sub->chat ?? 0;
                        if ($module == 'reviews') {
                            if( $review == 1){
                                return $next($request);
                            }  else{
                                Toastr::error(translate('messages.your_package_does_not_include_this_section'));
                                return back();
                            }
                        }
                        if ($module == 'pos') {
                            if( $pos == 1){
                                return $next($request);
                            }  else{
                                Toastr::error(translate('messages.your_package_does_not_include_this_section'));
                                return back();
                            }
                        }
                        if ($module == 'deliveryman') {
                            if( $self_delivery == 1){
                                return $next($request);
                            }  else{
                                Toastr::error(translate('messages.your_package_does_not_include_this_section'));
                                return back();
                            }
                        }
                        if ($module == 'chat') {
                            if( $chat == 1){
                                return $next($request);
                            }  else{
                                Toastr::error(translate('messages.your_package_does_not_include_this_section'));
                                return back();
                            }
                        }
                    }
                }
                elseif($restaurant->restaurant_model == 'unsubscribed') {
                    if ($module == 'chat' || $module == 'deliveryman' || $module == 'pos' || $module == 'reviews' ) {
                        Toastr::error(translate('messages.you_are_not_subscribed_to_any_package'));
                        return back();
                    }
                }
            }
        // } else{
        //     return $next($request);
        // }
    }


}
