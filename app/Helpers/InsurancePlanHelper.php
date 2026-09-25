<?php

namespace App\Helpers;

use App\Models\Client;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class InsurancePlanHelper
{
    /**
     * Filter insurance plans according to client's country currency.
     *
     * Plan model must have:
     * - insurance_company() relationship
     *
     * InsuranceCompany must have:
     * - currency_id
     */
    public static function filterByClientCurrency(
        Builder $query,
        $userId
    ): Builder {

        Log::info('Insurance Plan Currency Filter - START', [
            'user_id' => $userId,
            'model' => $query->getModel()::class,
        ]);

        // Get client
        $client = Client::find($userId);

        Log::info('Insurance Plan Currency Filter - Client', [
            'user_id' => $userId,
            'client_id' => $client?->id,
            'country_id' => $client?->country_id,
        ]);

        if (!$client) {

            Log::warning('Insurance Plan Currency Filter - Client Not Found', [
                'user_id' => $userId,
            ]);

            // No client = no plans
            return $query->whereRaw('1 = 0');
        }

        // Get currency of client's country
        $currency = Currency::where(
            'country_id',
            $client->country_id
        )->first();

        Log::info('Insurance Plan Currency Filter - Client Currency', [
            'client_id' => $client->id,
            'country_id' => $client->country_id,
            'currency_id' => $currency?->id,
            'currency_name' => $currency?->name,
            'currency_abbreviation' => $currency?->abbreviation,
        ]);

        if (!$currency) {

            Log::warning('Insurance Plan Currency Filter - Currency Not Found', [
                'client_id' => $client->id,
                'country_id' => $client->country_id,
            ]);

            // No currency = no plans
            return $query->whereRaw('1 = 0');
        }

        // Filter plans by insurance company's currency
        Log::info('Insurance Plan Currency Filter - Applying Filter', [
            'client_id' => $client->id,
            'client_country_id' => $client->country_id,
            'client_currency_id' => $currency->id,
            'client_currency_name' => $currency->name,
            'client_currency_abbreviation' => $currency->abbreviation,
        ]);

        $query->whereHas('insurance_company', function ($q) use ($currency) {

            $q->whereNull('deleted_at')
                ->where('currency_id', $currency->id);

        });

        Log::info('Insurance Plan Currency Filter - Filter Applied', [
            'currency_id' => $currency->id,
        ]);

        return $query;
    }
}
