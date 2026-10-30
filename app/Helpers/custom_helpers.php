<?php

use App\Models\Cities;
use App\Models\District;
use App\Models\InPatientDeductible;
use App\Models\OutPatientDeductible;
use App\Models\InsurancePeriod;
use App\Models\InsuranceCompany;
use App\Models\Country;
use App\Models\Nationality;
use App\Models\ProtectionSystem;
use App\Models\DangerousActivities;
use App\Models\InsuredItemCategory;
use App\Models\InsuredItemSubCategory;
use App\Models\EngineType;

use App\Models\VehicleType;
use App\Models\VehicleBrand;
use App\Models\VehicleCategory;
use App\Models\VehicleColor;
use Illuminate\Support\Facades\Route;
use App\Models\ChronicDisease;


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



if (!function_exists('getNationalityName')) {
    function getNationalityName($id)
    {
        if (empty($id)) {
            return '-';
        }

        // Some forms store the nationality name itself (e.g. "Jordanian")
        if (!is_numeric($id)) {
            return $id;
        }

        return Nationality::where('id', $id)->value('name') ?? '-';
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

if (!function_exists('getMaritalStatusName')) {
    function getMaritalStatusName($id)
    {
        $statuses = [
            1 => 'Single',
            2 => 'Married',
            3 => 'Divorced',
            4 => 'Widowed',
        ];

        return $statuses[$id] ?? '-';
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
            '0', '2' => 'No', // 2 = legacy "no" (still used by marine)
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

if (!function_exists('getNamesFromIds')) {
    // Ids stored as "2,3" or a JSON array -> "Name A, Name B" (soft-deleted names included)
    function getNamesFromIds($modelClass, $value)
    {
        if (empty($value)) {
            return '-';
        }

        $decoded = is_string($value) ? json_decode($value, true) : $value;
        $ids = is_array($decoded) ? $decoded : explode(',', (string) $value);
        $ids = array_filter(array_map('trim', $ids), 'is_numeric');

        if (empty($ids)) {
            return '-';
        }

        $names = $modelClass::withTrashed()->whereIn('id', $ids)->pluck('name')->toArray();

        return count($names) ? implode(', ', $names) : '-';
    }
}

if (!function_exists('getProtectionSystemName')) {
    function getProtectionSystemName($value)
    {
        return getNamesFromIds(ProtectionSystem::class, $value);
    }
}

if (!function_exists('getDangerousActivityName')) {
    function getDangerousActivityName($value)
    {
        return getNamesFromIds(DangerousActivities::class, $value);
    }
}

if (!function_exists('getDocumentPaths')) {
    // Document fields hold "path", "path1,path2" or a JSON array of paths
    function getDocumentPaths($value)
    {
        if (empty($value)) {
            return [];
        }

        $decoded = is_string($value) ? json_decode($value, true) : $value;

        if (is_array($decoded)) {
            $paths = $decoded;
        } elseif (is_string($decoded) && $decoded !== '') {
            $paths = explode(',', $decoded);
        } else {
            $paths = explode(',', $value);
        }

        return array_values(array_filter(array_map(fn($path) => ltrim(trim((string) $path), '/'), $paths)));
    }
}

if (!function_exists('getDocumentUrl')) {
    // App uploads (/upload-document) live in public/, direct uploads in storage/app/public
    function getDocumentUrl($path)
    {
        $path = ltrim(trim((string) $path), '/');

        if ($path === '') {
            return null;
        }

        if (file_exists(public_path($path))) {
            return url($path);
        }

        if (file_exists(storage_path('app/public/' . $path))) {
            return asset('storage/' . $path);
        }

        return null;
    }
}

if (!function_exists('getDocumentPreview')) {
    // One document for a PDF: images are shown directly as a small thumbnail (dompdf reads the
    // local file), anything else (PDF / Word) as a download link without the full path
    function getDocumentPreview($path)
    {
        $path = ltrim(trim((string) $path), '/');

        $localPath = null;
        if ($path !== '' && file_exists(public_path($path))) {
            $localPath = public_path($path);
        } elseif ($path !== '' && file_exists(storage_path('app/public/' . $path))) {
            $localPath = storage_path('app/public/' . $path);
        }

        if (!$localPath) {
            return 'Document not found';
        }

        $extension = strtolower(pathinfo($localPath, PATHINFO_EXTENSION));

        // Check the extension and that the file really is an image (older uploads kept the app's file name)
        $imageInfo = in_array($extension, ['jpg', 'jpeg', 'png', 'gif'], true) ? @getimagesize($localPath) : false;

        if ($imageInfo && in_array($imageInfo[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF], true)) {
            return '<img src="' . e($localPath) . '" alt="Document" style="max-width:120px; max-height:90px; border:1px solid #ccc; margin:2px;">';
        }

        $url = getDocumentUrl($path);
        $label = 'Download ' . strtoupper($extension ?: 'file');

        return $url ? '<a href="' . e($url) . '" download>' . e($label) . '</a>' : e($label);
    }
}

if (!function_exists('getDocumentLinks')) {
    // Previews for a document field, for use in PDFs with {!! !!}
    function getDocumentLinks($value, $empty = '-')
    {
        $previews = array_map('getDocumentPreview', getDocumentPaths($value));

        return count($previews) ? implode(' ', $previews) : e($empty);
    }
}

if (!function_exists('getChronicDiseaseName')) {
    function getChronicDiseaseName($value)
    {
        if (empty($value)) {
            return '-';
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            } else {
                // App sends multiple selections as "5,6"
                $value = array_map('trim', explode(',', $value));
            }
        }

        $ids = is_array($value) ? $value : [$value];

        $ids = array_filter(
            $ids,
            fn($id) =>
                is_scalar($id) && is_numeric($id)
        );

        if (empty($ids)) {
            return '-';
        }

        $names = ChronicDisease::whereIn('id', $ids)->pluck('name')->toArray();

        return count($names) ? implode(', ', $names) : '-';
    }
}
