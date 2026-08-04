<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PurchasePolicy;
use Illuminate\Support\Facades\Log;

class ExpiredPolicyReset extends Command
{
    protected $signature = 'policies:reset-renewed';
    protected $description = 'Reset renewed column to 0 for expired policies';

    public function handle()
    {
        Log::info('ExpiredPolicyReset job started.(handle function) starting.');
        $today = now()->format('Y-m-d');

        $expiredPolicies = PurchasePolicy::whereRaw("
            CASE
                WHEN expiry_date REGEXP '^[0-9]{2}-[0-9]{2}-[0-9]{4}$'
                    THEN STR_TO_DATE(expiry_date, '%d-%m-%Y')
                WHEN expiry_date REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
                    THEN STR_TO_DATE(expiry_date, '%Y-%m-%d')
                ELSE NULL
            END < STR_TO_DATE(?, '%Y-%m-%d')
        ", [$today])
            ->where('renewed', 1)
            ->update(['renewed' => 0]);
        $this->info("Expired policies reset: {$expiredPolicies}");
        Log::info('ExpiredPolicyReset job finished.(handle function) finishing.');
    }
}
