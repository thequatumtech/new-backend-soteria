<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PurchasePolicy;
use App\Models\Client;
use App\Models\AgentModel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\RenewalClientMail;
use App\Mail\RenewalAgentMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class NotifyRenewals extends Command
{
    protected $signature = 'notify:renewals';
    protected $description = 'Send automatic renewal notices at 30 and 15 days before policy expiry';

    public function handle()
    {
        Log::info('NotifyRenewals job started.(handle function) starting.');
        $policies = PurchasePolicy::whereNotNull('expiry_date')->get();

        $today = Carbon::now()->startOfDay();
        $sentCount = 0;

        foreach ($policies as $policy) {
            try {
                try {
                    $expiry = Carbon::parse($policy->expiry_date)->startOfDay();
                } catch (Throwable $e) {
                    continue;
                }

                if ($expiry->lt($today)) {
                    continue;
                }

                $daysLeft = $today->diffInDays($expiry);

                // 30 days before expiry

                if ($daysLeft === 30 && $policy->notify_30_days === '0') {
                    DB::beginTransaction();

                    $this->sendEmails($policy, $daysLeft);

                    $policy->notify_30_days = '1';
                    $policy->save();

                    DB::commit();
                    $sentCount++;
                }

                //  15 days before expiry

                if ($daysLeft === 15 && $policy->notify_15_days === '0') {
                    DB::beginTransaction();

                    $this->sendEmails($policy, $daysLeft);

                    $policy->notify_15_days = '1';
                    $policy->save();

                    DB::commit();
                    $sentCount++;
                }
            } catch (Throwable $ex) {
                DB::rollBack();
                Log::error('NotifyRenewals error for policy id ' . ($policy->id ?? 'n/a') . ': ' . $ex->getMessage());
            }
        }
        Log::info('NotifyRenewals job finished.(handle function) finishing.');
        $this->info("NotifyRenewals job finished. Emails sent: {$sentCount}");
        return 0;
    }

    private function sendEmails($policy, $daysLeft)
    {
        Log::info("Sending renewal emails for policy id {$policy->id} with {$daysLeft} days left.");
        $client = $policy->client ?? Client::find($policy->client_id);
        Log::info('Client details for client: ' . ($client ? 'Found' : 'Not Found'));
        // Send to client
        if ($client && !empty($client->email_id)) {
            Mail::to($client->email_id)->send(new RenewalClientMail($policy, $client, $daysLeft));
        }
        Log::info('Client email sent if email exists. for agent');
        // Send to agent
        if ($client && !empty($client->agent_id)) {
            $agent = AgentModel::where('agent_code', $client->agent_id)->first();
            if ($agent && !empty($agent->agent_email)) {
                Mail::to($agent->agent_email)->send(new RenewalAgentMail($policy, $client, $agent, $daysLeft));
            }
        }
    }
}
