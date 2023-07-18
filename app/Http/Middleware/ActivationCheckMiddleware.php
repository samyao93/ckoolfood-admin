<?php

namespace App\Http\Middleware;

use App\Traits\ActivationClass;
use App\CentralLogics\Helpers;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ActivationCheckMiddleware
{
    use ActivationClass;
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->actch()) {
            return Redirect::away(base64_decode('aHR0cHM6Ly82YW10ZWNoLmNvbS9zb2Z0d2FyZS1hY3RpdmF0aW9u'))->send();

        }
        return $next($request);
    }
}
