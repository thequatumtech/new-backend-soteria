<?php

namespace App\Http\Middleware;

use App\Models\Client;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Get language from request
        |--------------------------------------------------------------------------
        |
        | Priority:
        |
        | Accept-Language
        |      ↓
        | lang
        |      ↓
        | language
        |
        */
        $locale = $this->getRequestLanguage($request);

        /*
        |--------------------------------------------------------------------------
        | 2. If request does not contain language, use session language
        |--------------------------------------------------------------------------
        |
        | This is mainly useful for normal web requests.
        | Flutter API requests should normally always send Accept-Language.
        |
        */
        if (!$locale) {
            $locale = Session::get('locale');
        }

        /*
        |--------------------------------------------------------------------------
        | 3. If still no language, use authenticated client language
        |--------------------------------------------------------------------------
        |
        | This works when authentication has already been completed before
        | this middleware.
        |
        | In your current route structure, `apitoken` runs inside this
        | middleware, so the authenticated client is also checked again
        | AFTER `$next($request)`.
        |
        */
        if (!$locale) {
            $client = $this->getAuthenticatedClient($request);

            if ($client) {
                $locale = $client->language;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Default language
        |--------------------------------------------------------------------------
        */
        if (!in_array($locale, ['en', 'ar'], true)) {
            $locale = 'en';
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Set Laravel application locale
        |--------------------------------------------------------------------------
        */
        App::setLocale($locale);

        /*
        |--------------------------------------------------------------------------
        | 6. Execute the request
        |--------------------------------------------------------------------------
        */
        $response = $next($request);

        /*
        |--------------------------------------------------------------------------
        | 7. Get authenticated client after apitoken middleware
        |--------------------------------------------------------------------------
        |
        | This is important for your current route structure:
        |
        | SetLocale
        |    ↓
        | apitoken
        |    ↓
        | Controller
        |
        | Therefore the client may only be available after `$next()`.
        |
        */
        $client = $this->getAuthenticatedClient($request);

        /*
        |--------------------------------------------------------------------------
        | 8. Update client's selected language
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | Database = en
        | Flutter sends = ar
        |
        | Result:
        | Database = ar
        |
        */
        if ($client) {
            if ($client->language !== $locale) {
                $client->language = $locale;
                $client->save();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 9. Store locale in session for non-API/web requests
        |--------------------------------------------------------------------------
        |
        | Flutter sends:
        | Accept: application/json
        |
        | Therefore this normally won't store API language in session.
        |
        */
        if (!$request->expectsJson()) {
            Session::put('locale', $locale);
        }

        return $response;
    }

    /**
     * Get language from request.
     *
     * Priority:
     * 1. Accept-Language
     * 2. lang
     * 3. language
     *
     * Supports:
     * en
     * ar
     * en-US
     * en-GB
     * ar-SA
     * ar-JO
     * ar,en;q=0.9
     */
    private function getRequestLanguage(Request $request): ?string
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Standard HTTP header
        |--------------------------------------------------------------------------
        */
        $language = $request->header('Accept-Language');

        /*
        |--------------------------------------------------------------------------
        | 2. Custom header
        |--------------------------------------------------------------------------
        */
        if (!$language) {
            $language = $request->header('lang');
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Request parameter
        |--------------------------------------------------------------------------
        |
        | Useful for login/register before authentication.
        |
        */
        if (!$language && $request->filled('language')) {
            $language = $request->input('language');
        }

        /*
        |--------------------------------------------------------------------------
        | No language provided
        |--------------------------------------------------------------------------
        */
        if (!$language) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Normalize language
        |--------------------------------------------------------------------------
        */
        $language = strtolower(trim((string) $language));

        /*
        |--------------------------------------------------------------------------
        | Handle:
        |
        | ar,en;q=0.9
        |
        | We use the first language.
        |--------------------------------------------------------------------------
        */
        if (strpos($language, ',') !== false) {
            $language = explode(',', $language)[0];
        }

        /*
        |--------------------------------------------------------------------------
        | Remove quality value:
        |
        | ar;q=0.9
        |--------------------------------------------------------------------------
        */
        if (strpos($language, ';') !== false) {
            $language = explode(';', $language)[0];
        }

        /*
        |--------------------------------------------------------------------------
        | Remove region:
        |
        | en-US -> en
        | ar-SA -> ar
        |--------------------------------------------------------------------------
        */
        if (strpos($language, '-') !== false) {
            $language = explode('-', $language)[0];
        }

        /*
        |--------------------------------------------------------------------------
        | Remove region if underscore is used:
        |
        | en_US -> en
        | ar_SA -> ar
        |--------------------------------------------------------------------------
        */
        if (strpos($language, '_') !== false) {
            $language = explode('_', $language)[0];
        }

        /*
        |--------------------------------------------------------------------------
        | Only allow supported languages
        |--------------------------------------------------------------------------
        */
        if (in_array($language, ['en', 'ar'], true)) {
            return $language;
        }

        return null;
    }

    /**
     * Get authenticated client.
     */
    private function getAuthenticatedClient(Request $request): ?Client
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Request authenticated user
        |--------------------------------------------------------------------------
        */
        try {
            $client = $request->user();

            if ($client instanceof Client) {
                return $client;
            }
        } catch (\Throwable $e) {
            // Continue to next authentication method.
        }

        /*
        |--------------------------------------------------------------------------
        | 2. client guard
        |--------------------------------------------------------------------------
        */
        try {
            $client = Auth::guard('client')->user();

            if ($client instanceof Client) {
                return $client;
            }
        } catch (\Throwable $e) {
            // Continue to next authentication method.
        }

        /*
        |--------------------------------------------------------------------------
        | 3. api guard
        |--------------------------------------------------------------------------
        */
        try {
            $client = Auth::guard('api')->user();

            if ($client instanceof Client) {
                return $client;
            }
        } catch (\Throwable $e) {
            // Continue.
        }

        return null;
    }
}
