<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PurchasePolicy;
use App\Models\Client;
use App\Models\AgentModel;
use Illuminate\Support\Facades\Log;
class RenewalAgentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $policy;
    public $client;
    public $agent;
    public $daysLeft;

    public function __construct(PurchasePolicy $policy, Client $client = null, AgentModel $agent = null, int $daysLeft = null)
    {
        Log::info('Constructing RenewalAgentMail email.');
        $this->policy = $policy;
        $this->client = $client;
        $this->agent = $agent;
        $this->daysLeft = $daysLeft;
    }

    // public function build()
    // {
    //     $subject = "Client {$this->client->first_name} {$this->client->surname} - policy {$this->policy->policy_no} expires in {$this->daysLeft} days";
    //     return $this->subject($subject)
    //                 ->view('emails.renewal_agent');
    // }
    public function build()
    {
        Log::info('Building RenewalAgentMail email.');
        $clientName = $this->client
            ? "{$this->client->first_name} {$this->client->surname}"
            : 'Client';

        $subject = "Client {$clientName} - policy {$this->policy->policy_no} expires in {$this->daysLeft} days";

        return $this->subject($subject)
            ->view('emails.renewal_agent');
    }
}
