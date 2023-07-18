<?php

namespace App\Providers;

use App\Models\Log;
use App\Models\Food;
use App\Models\User;
use App\Models\Zone;
use App\Models\AddOn;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Shift;
use App\Models\Banner;
use App\Models\Coupon;
use App\Models\Refund;
use App\Models\Review;
use App\Models\Vendor;
use App\Models\Cuisine;
use App\Models\Expense;
use App\Models\FoodTag;
use App\Models\Message;
use App\Models\TimeLog;
use App\Models\Vehicle;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Discount;
use App\Models\DMReview;
use App\Models\UserInfo;
use App\Models\Wishlist;
use App\Models\AdminRole;
use App\Models\Attribute;
use App\Models\Incentive;
use App\Models\MailConfig;
use App\Models\Newsletter;
use App\Models\Restaurant;
use App\Models\VisitorLog;
use App\Models\AdminWallet;
use App\Models\DeliveryMan;
use App\Models\OrderDetail;
use App\Models\SocialMedia;
use App\Models\Conversation;
use App\Models\EmployeeRole;
use App\Models\IncentiveLog;
use App\Models\ItemCampaign;
use App\Models\Notification;
use App\Models\RefundReason;
use App\Models\Subscription;
use App\Models\ContactMessage;
use App\Models\RestaurantZone;
use App\Models\VendorEmployee;
use App\Observers\LogObserver;
use App\Models\BusinessSetting;
use App\Models\CustomerAddress;
use App\Models\DeliveryHistory;
use App\Models\SubscriptionLog;
use App\Models\WithdrawRequest;
use App\Observers\FoodObserver;
use App\Observers\UserObserver;
use App\Observers\ZoneObserver;
use App\Models\OrderTransaction;
use App\Models\ProvideDMEarning;
use App\Models\RestaurantWallet;
use App\Models\TrackDeliveryman;
use App\Models\UserNotification;
use App\Models\WithdrawalMethod;
use App\Observers\AddOnObserver;
use App\Observers\AdminObserver;
use App\Observers\OrderObserver;
use App\Observers\ShiftObserver;
use App\Models\DeliveryManWallet;
use App\Models\OrderCancelReason;
use App\Models\SubscriptionPause;
use App\Models\WalletTransaction;
use App\Observers\BannerObserver;
use App\Observers\CouponObserver;
use App\Observers\RefundObserver;
use App\Observers\ReviewObserver;
use App\Observers\VendorObserver;
use App\Models\AccountTransaction;
use App\Models\Cuisine_restaurant;
use App\Models\EmailVerifications;
use App\Models\RestaurantSchedule;
use App\Observers\CuisineObserver;
use App\Observers\ExpenseObserver;
use App\Observers\FoodTagObserver;
use App\Observers\MessageObserver;
use App\Observers\TimeLogObserver;
use App\Observers\VehicleObserver;
use NunoMaduro\Collision\Provider;
use App\Models\SubscriptionPackage;
use App\Observers\CampaignObserver;
use App\Observers\CategoryObserver;
use App\Observers\DiscountObserver;
use App\Observers\DMReviewObserver;
use App\Observers\UserInfoObserver;
use App\Observers\WishlistObserver;
use App\Models\OrderDeliveryHistory;
use App\Models\SubscriptionSchedule;
use App\Observers\AdminRoleObserver;
use App\Observers\AttributeObserver;
use App\Observers\IncentiveObserver;
use App\Observers\MailConfigObserver;
use App\Observers\NewsletterObserver;
use App\Observers\RestaurantObserver;
use App\Observers\VisitorLogObserver;
use Illuminate\Support\Facades\Event;
use App\Models\RestaurantSubscription;
use App\Observers\AdminWalletObserver;
use App\Observers\DeliveryManObserver;
use App\Observers\OrderDetailObserver;
use App\Observers\SocialMediaObserver;
use Illuminate\Auth\Events\Registered;
use App\Models\LoyaltyPointTransaction;
use App\Models\SubscriptionTransaction;
use App\Observers\ConversationObserver;
use App\Observers\EmployeeRoleObserver;
use App\Observers\IncentiveLogObserver;
use App\Observers\ItemCampaignObserver;
use App\Observers\NotificationObserver;
use App\Observers\RefundReasonObserver;
use App\Observers\SubscriptionObserver;
use App\Observers\ContactMessageObserver;
use App\Observers\RestaurantZoneObserver;
use App\Observers\VendorEmployeeObserver;
use App\Observers\BusinessSettingObserver;
use App\Observers\CustomerAddressObserver;
use App\Observers\DeliveryHistoryObserver;
use App\Observers\SubscriptionLogObserver;
use App\Observers\WithdrawRequestObserver;
use App\Observers\OrderTransactionObserver;
use App\Observers\ProvideDMEarningObserver;
use App\Observers\RestaurantWalletObserver;
use App\Observers\TrackDeliverymanObserver;
use App\Observers\UserNotificationObserver;
use App\Observers\WithdrawalMethodObserver;
use App\Observers\DeliveryManWalletObserver;
use App\Observers\OrderCancelReasonObserver;
use App\Observers\SubscriptionPauseObserver;
use App\Observers\WalletTransactionObserver;
use App\Observers\AccountTransactionObserver;
use App\Observers\Cuisine_restaurantObserver;
use App\Observers\EmailVerificationsObserver;
use App\Observers\RestaurantScheduleObserver;
use App\Observers\SubscriptionPackageObserver;
use App\Observers\OrderDeliveryHistoryObserver;
use App\Observers\SubscriptionScheduleObserver;
use App\Observers\RestaurantSubscriptionObserver;
use App\Observers\LoyaltyPointTransactionObserver;
use App\Observers\SubscriptionTransactionObserver;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
            // try {
                //code...

        //     AccountTransaction::observe(AccountTransactionObserver::class);
        //     AddOn::observe(AddOnObserver::class);
        //     Admin::observe(AdminObserver::class);
        //     AdminRole::observe(AdminRoleObserver::class);
        //     AdminWallet::observe(AdminWalletObserver::class);
        //     Attribute::observe(AttributeObserver::class);
        //     Banner::observe(BannerObserver::class);
        //     BusinessSetting::observe(BusinessSettingObserver::class);
        //     Campaign::observe(CampaignObserver::class);
        //     Category::observe(CategoryObserver::class);
        //     ContactMessage::observe(ContactMessageObserver::class);
        //     Conversation::observe(ConversationObserver::class);
        //     Coupon::observe(CouponObserver::class);
        //     Cuisine::observe(CuisineObserver::class);
        //     // Cuisine_restaurant::observe(Cuisine_restaurantObserver::class);
        //     CustomerAddress::observe(CustomerAddressObserver::class);
            // DeliveryHistory::observe(DeliveryHistoryObserver::class);
        //     DeliveryMan::observe(DeliveryManObserver::class);
            // DeliveryManWallet::observe(DeliveryManWalletObserver::class);
        //     Discount::observe(DiscountObserver::class);
        //     DMReview::observe(DMReviewObserver::class);
        //     EmailVerifications::observe(EmailVerificationsObserver::class);
        //     EmployeeRole::observe(EmployeeRoleObserver::class);
        //     Expense::observe(ExpenseObserver::class);
        //     Food::observe(FoodObserver::class);
        //     FoodTag::observe(FoodTagObserver::class);
        //     Incentive::observe(IncentiveObserver::class);
        //     IncentiveLog::observe(IncentiveLogObserver::class);
        //     ItemCampaign::observe(ItemCampaignObserver::class);
        //     Log::observe(LogObserver::class);
        //     LoyaltyPointTransaction::observe(LoyaltyPointTransactionObserver::class);
        //     MailConfig::observe(MailConfigObserver::class);
        //     Message::observe(MessageObserver::class);
        //     Newsletter::observe(NewsletterObserver::class);
        //     Notification::observe(NotificationObserver::class);
        //     OrderCancelReason::observe(OrderCancelReasonObserver::class);
        //     OrderDeliveryHistory::observe(OrderDeliveryHistoryObserver::class);
        //     OrderDetail::observe(OrderDetailObserver::class);
        //     OrderTransaction::observe(OrderTransactionObserver::class);
        //     Order::observe(OrderObserver::class);
        //     ProvideDMEarning::observe(ProvideDMEarningObserver::class);
        //     Refund::observe(RefundObserver::class);
        //     RefundReason::observe(RefundReasonObserver::class);
        //     Restaurant::observe(RestaurantObserver::class);
        //     RestaurantSchedule::observe(RestaurantScheduleObserver::class);
        //     RestaurantSubscription::observe(RestaurantSubscriptionObserver::class);
        //     RestaurantWallet::observe(RestaurantWalletObserver::class);
        //     RestaurantZone::observe(RestaurantZoneObserver::class);
        //     Review::observe(ReviewObserver::class);
        //     Shift::observe(ShiftObserver::class);
        //     SocialMedia::observe(SocialMediaObserver::class);
        //     SubscriptionLog::observe(SubscriptionLogObserver::class);
        //     Subscription::observe(SubscriptionObserver::class);
        //     SubscriptionPackage::observe(SubscriptionPackageObserver::class);
        //     SubscriptionPause::observe(SubscriptionPauseObserver::class);
        //     SubscriptionSchedule::observe(SubscriptionScheduleObserver::class);
        //     SubscriptionTransaction::observe(SubscriptionTransactionObserver::class);
        //     TimeLog::observe(TimeLogObserver::class);
        //     TrackDeliveryman::observe(TrackDeliverymanObserver::class);
        //     UserInfo::observe(UserInfoObserver::class);
        //     UserNotification::observe(UserNotificationObserver::class);
        //     User::observe(UserObserver::class);
        //     Vehicle::observe(VehicleObserver::class);
        //     VendorEmployee::observe(VendorEmployeeObserver::class);
        //     Vendor::observe(VendorObserver::class);
        //     // VisitorLog::observe(VisitorLogObserver::class);
        //     WalletTransaction::observe(WalletTransactionObserver::class);
        //     Wishlist::observe(WishlistObserver::class);
        //     WithdrawalMethod::observe(WithdrawalMethodObserver::class);
        //     WithdrawRequest::observe(WithdrawRequestObserver::class);
        //     Zone::observe(ZoneObserver::class);
        // } catch (\Throwable $th) {
        //     info($th->getMessage());
        // }
    }
}
