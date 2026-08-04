<?php

namespace App\Console\Commands;

use App\Mail\SendCoupon;
use App\Models\Client;
use App\Models\ClientDiscountCoupon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDiscountCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:coupons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Discount Coupons';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('send:coupons command started.');

        echo "executing=>".time();
        try {
            $pending_clients = ClientDiscountCoupon::where('send_to', '1')->where('is_sent', '0')->where('status', '1')->limit(env('MAIL_LIMIT'))->get();
            foreach ($pending_clients as $client) {
                $found = Client::where('id', $client->client_id)->first();
                if ($found) {
                    Mail::to($found->email_id)->queue(new SendCoupon($client));
                }
//            $update = ClientDiscountCoupon::find($client->id);
                $client->is_sent = '1';
                $client->save();
            }
            Log::info('send:coupons command completed successfully.');
        } catch (\Exception $e) {
            Log::error('Error in send:coupons command: ' . $e->getMessage());
        }
        return true;
    }
}
