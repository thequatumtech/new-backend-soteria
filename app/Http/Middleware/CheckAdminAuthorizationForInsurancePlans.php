<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminAuthorizationForInsurancePlans
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the authenticated admin user
        $admin = Auth::user();

        // If super admin then ignore
        if($admin->is_super_admin == 1 || $request->isMethod('post') || $request->isMethod('patch') || $request->isMethod('delete')){
            return $next($request);  // Skip authorization check
        }

        // Get the authorized routes from JSON
        $authorizedRoutes = json_decode($admin->authorized_routes, true);

        // Check if the route is authorized
        if (is_array($authorizedRoutes) && in_array('insurance_plans', $authorizedRoutes)) {
            return $next($request); // Allow access
        }

        // If not authorized, deny access
        abort(403, 'Unauthorized');
    }
}
