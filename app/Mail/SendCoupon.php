<?php

namespace App\Mail;

use App\Models\Client;
use App\Models\ClientDiscountCoupon;
use App\Models\InsuranceCompany;
use App\Models\LineOfBusiness;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCoupon extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $client_coupon;
    public $discount;
    public $insurance_type;
    public $insurance_company;
    public $coupon_code;
    public $effective_date;
    public $expiry_date;
    public $description;
    public $attachment;
    public $coupon_id;
    public $name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ClientDiscountCoupon $client_coupon)
    {
        $this->client_coupon = $client_coupon;
        $this->discount = $client_coupon->percentage;
        $this->coupon_code = $client_coupon->coupon_code;
        $this->description = $client_coupon->description;
        $this->coupon_id = $client_coupon->coupon_id;
        $this->attachment = $client_coupon->attachment;
        $this->effective_date = Carbon::parse($client_coupon->effective_date)->format('d-m-Y');
        $this->expiry_date = Carbon::parse($client_coupon->expiry_date)->format('d-m-Y');
        $this->insurance_type = LineOfBusiness::find($client_coupon->line_of_business_id)->name;
        $this->insurance_company = InsuranceCompany::find($client_coupon->insurance_company_id)->company_name;
        $this->name = Client::find($client_coupon->client_id)->full_name;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Special Offer Just for You! Enjoy '.$this->discount.'% Off On Your Next Purchase',
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
            view: 'emails.send_coupon',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        if(!empty($this->attachment)){
            return [
                Attachment::fromPath(public_path('uploads/discount_coupons/') . $this->coupon_id . '/' . $this->attachment)
                    ->as('Attachment.pdf')
            ];
        } else {
            return [];
        }
    }
}
