<?php
if (!function_exists('is_admin_authorized')) {
    function is_admin_authorized($route)
    {
        return auth()->user()->is_super_admin == 1 || in_array($route, json_decode(auth()->user()->authorized_routes,1));
    }
}
