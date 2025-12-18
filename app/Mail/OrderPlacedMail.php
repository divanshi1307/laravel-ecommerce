<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $shippingCharge;
    public $couponCode;
    public $discountAmount;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->shippingCharge = session('shipping_charge') ?? 0;
        $this->couponCode = session('coupon_code');
        $this->discountAmount = session('discount') ?? 0;
    }

    public function build()
    {
        return $this->subject(config('app.name') . ' - Order Confirmation')
            ->view('emails.order-placed')
            ->with([
                'order' => $this->order,
                'shippingCharge' => $this->shippingCharge,
                'couponCode' => $this->couponCode,
                'discountAmount' => $this->discountAmount,
            ]);
    }
}
