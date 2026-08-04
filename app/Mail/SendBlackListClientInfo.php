<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendBlackListClientInfo extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $client_id;
    public $full_name;
    public $mobile_no;
    public $email_id;
    public $national_id_number;
    public $insurance_companies_name;
    public $insurance_types_name;
    public $total_gross_premium_paid;
    public $total_net_premium_paid;
    public $insured_period;
    public $insurance_type_cannot_purchase;
    public $black_list_reason;
    public $client_attachments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->client_id = $data['client_id'];
        $this->full_name = $data['full_name'];
        $this->mobile_no = $data['mobile_no'];
        $this->email_id = $data['email_id'];
        $this->national_id_number = $data['national_id_number'];
        $this->insurance_companies_name = $data['insurance_companies_name'];
        $this->insurance_types_name = $data['insurance_types_name'];
        $this->total_gross_premium_paid = $data['total_gross_premium_paid'];
        $this->total_net_premium_paid = $data['total_net_premium_paid'];
        $this->insured_period = $data['insured_period'];
        $this->insurance_type_cannot_purchase = $data['insurance_type_cannot_purchase'];
        $this->black_list_reason = $data['black_list_reason'];
        $this->client_attachments = $data['attachments'];
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: __('messages.black_lists.client_black_list_file',['client' => $this->full_name]),
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.send_black_list_client_details',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        $attachments = [];
        foreach ($this->client_attachments as $single) {
            if (!empty($single)) {
                $attachments[] = Attachment::fromPath(public_path('uploads/black_list/') . $this->client_id . '/' . $single);
            }
        }
        return $attachments;
    }
}
