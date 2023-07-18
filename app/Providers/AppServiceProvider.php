<?php

namespace App\Providers;

use App\Traits\ActivationClass;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use App\CentralLogics\Helpers;

ini_set('memory_limit', '512M');

class AppServiceProvider extends ServiceProvider
{
    use ActivationClass;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     */
    public function boot(Request $request)
    {
        if (($request->is('admin/auth/login') || $request->is('provider/auth/login')) && $request->isMethod('post')) {
            $response = $this->actch();
            $data = json_decode($response->getContent(), true);
            if (!$data['active']) {
                return Redirect::away(base64_decode('aHR0cHM6Ly9hY3RpdmF0aW9uLjZhbXRlY2guY29t'))->send();
            }
        }

        try {
            Config::set('default_pagination', 25);
            Paginator::useBootstrap();
            foreach(Helpers::get_view_keys() as $key=>$value)
            {
                view()->share($key, $value);
            }
        } catch (\Exception $ex) {
            info($ex->getMessage());
        }
    }
}
