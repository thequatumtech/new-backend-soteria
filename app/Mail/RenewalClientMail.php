<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PurchasePolicy;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
class RenewalClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public $policy;
    public $client;
    public $daysLeft;

    public function __construct(PurchasePolicy $policy, Client $client = null, int $daysLeft = null)
    {
        Log::info('Constructing RenewalClientMail email.');
        $this->policy = $policy;
        $this->client = $client;
        $this->daysLeft = $daysLeft;
    }

    // public function build()
    // {
    //     $subject = "Renewal reminder: Policy {$this->policy->policy_no} expires in {$this->daysLeft} days";
    //     return $this->subject($subject)
    //                 ->view('emails.renewal_client');
    // }
    public function build()
    {
        Log::info('Building RenewalClientMail email.');
        $clientName = $this->client ? ($this->client->first_name . ' ' . $this->client->surname) : 'Client';

        $subject = "Renewal reminder: Policy {$this->policy->policy_no} expires in {$this->daysLeft} days";

        return $this->subject($subject)
            ->view('emails.renewal_client');
    }
}
