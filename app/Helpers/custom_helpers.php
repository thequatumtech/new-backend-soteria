<?php

use App\Models\Cities;
use App\Models\District;
use App\Models\InPatientDeductible;
use App\Models\OutPatientDeductible;
use App\Models\InsurancePeriod;
use App\Models\InsuranceCompany;
use App\Models\Country;
use App\Models\InsuredItemCategory;
use App\Models\InsuredItemSubCategory;
use App\Models\EngineType;

use App\Models\VehicleType;
use App\Models\VehicleBrand;
use App\Models\VehicleCategory;
use App\Models\VehicleColor;
use Illuminate\Support\Facades\Route;

if (!function_exists('is_admin_authorized')) {
    function is_admin_authorized($route)
    {
        return auth()->user()->is_super_admin == 1 || in_array($route, json_decode(auth()->user()->authorized_routes,1));
    }
}


if (!function_exists('getCityName')) {
    function getCityName($cityId)
    {
        if (empty($cityId)) {
            return '-';
        }

        return Cities::where('id', $cityId)->value('name') ?? '-';
    }
}

if (!function_exists('getDistrictName')) {
    function getDistrictName($districtId)
    {
        if (empty($districtId)) {
            return '-';
        }

        return District::where('id', $districtId)->value('name') ?? '-';
    }
}






if (!function_exists('getInpatientDeductibleName')) {
    function getInpatientDeductibleName($id)
    {
        if (empty($id)) {
            return '-';
        }

        return InPatientDeductible::where('id', $id)->value('name') ?? '-';
    }
}

if (!function_exists('getOutpatientDeductibleName')) {
    function getOutpatientDeductibleName($id)
    {
        if (empty($id)) {
            return '-';
        }

        return OutPatientDeductible::where('id', $id)->value('name') ?? '-';
    }
}



if (!function_exists('getInsurancePeriodName')) {
    function getInsurancePeriodName($id)
    {
        if (empty($id)) {
            return '-';
        }

        return InsurancePeriod::where('id', $id)->value('name') ?? '-';
    }
}


if (!function_exists('getInsuranceCompanyName')) {
    function getInsuranceCompanyName($id)
    {
        if (empty($id)) {
            return '-';
        }

        return InsuranceCompany::where('id', $id)->value('company_name') ?? '-';
    }
}



if (!function_exists('getCountryName')) {
    function getCountryName($id)
    {
        if (empty($id)) {
            return '-';
        }

        return Country::where('id', $id)->value('name') ?? '-';
    }
}


if (!function_exists('getInsuredItemCategoryName')) {
    function getInsuredItemCategoryName($id)
    {
        if (empty($id)) {
            return '-';
        }

        return InsuredItemCategory::where('id', $id)->value('name') ?? '-';
    }
}

if (!function_exists('getInsuredItemSubCategoryName')) {
    function getInsuredItemSubCategoryName($id)
    {
        if (empty($id)) {
            return '-';
        }

        return InsuredItemSubCategory::where('id', $id)->value('name') ?? '-';
    }
}


if (!function_exists('getYesNoStatus')) {
    function getYesNoStatus($value)
    {
        return match ((string) $value) {
            '1' => 'Yes',
            '2' => 'No',
            default => '-',
        };
    }
}


/**
 * Get Vehicle Type name by ID
 */
if (!function_exists('get_vehicle_type_name')) {
    function get_vehicle_type_name($id)
    {
        if (empty($id)) {
            return '-';
        }

        return VehicleType::where('id', $id)->value('name') ?? '-';
    }
}

/**
 * Get Vehicle Brand name by ID
 */
if (!function_exists('get_vehicle_brand_name')) {
    function get_vehicle_brand_name($id)
    {
        if (empty($id)) {
            return '-';
        }

        return VehicleBrand::where('id', $id)->value('name') ?? '-';
    }
}

/**
 * Get Vehicle Category name by ID
 */
if (!function_exists('get_vehicle_category_name')) {
    function get_vehicle_category_name($id)
    {
        if (empty($id)) {
            return '-';
        }

        return VehicleCategory::where('id', $id)->value('name') ?? '-';
    }
}

/**
 * Get Vehicle Color name by ID
 */
if (!function_exists('get_vehicle_color_name')) {
    function get_vehicle_color_name($id)
    {
        if (empty($id)) {
            return '-';
        }

        return VehicleColor::where('id', $id)->value('name') ?? '-';
    }
}

if (!function_exists('get_engine_type_name')) {
    function get_engine_type_name($id)
    {
        if (empty($id)) {
            return '-';
        }

        return EngineType::where('id', $id)->value('name') ?? '-';
    }

    if (!function_exists('getAdminViewData')) {
        function getAdminViewData(): array
        {
            $admin = auth()->user();
            return ['admin' => $admin, 'is_super_admin' => $admin?->is_super_admin ?? false, 'authorized_routes' => $admin?->authorized_routes ? json_decode($admin->authorized_routes, true) : [], 'currentRouteName' => Route::currentRouteName(),];
        }
    }

    if (!function_exists('canAccessRoute')) {
        function canAccessRoute(string $route): bool
        {
            $admin = auth()->user();

            if (!$admin) {
                return false;
            }

            if ($admin->is_super_admin == 1) {
                return true;
            }

            $authorizedRoutes = $admin->authorized_routes
                ? json_decode($admin->authorized_routes, true)
                : [];

            return in_array($route, $authorizedRoutes);
        }
    }
}
