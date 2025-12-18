<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif;">

<h2>Thank you for your order!</h2>

<div class="card shadow-sm rounded-3 border-0">
    <div class="card-body">

        {{-- ORDER HEADER --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <span class="badge bg-light text-dark border">
                    <strong class="text-danger">Order ID:</strong> {{ $order->order_id }}
                </span>
            </div>
            <div class="col-md-6 text-end">
                <span class="badge bg-light text-dark border">
                    <strong>Order Status:</strong> {{ ucfirst($order->status()) }}
                </span>
            </div>
        </div>

        <hr>

        {{-- ITEMS --}}
        <p class="text-uppercase fw-semibold text-secondary small">Items</p>

        @php
            $subtotal = 0;
        @endphp

        @foreach($order->items as $item)
            @php
                $totalItemPrice = $item->price * $item->quantity;
                $subtotal += $totalItemPrice;
            @endphp

            <div class="row align-items-center mb-3">
                <div class="col-md-1">
                    <img src="{{ asset('uploads/products/' . $item->product->display_image) }}"
                        alt="{{ $item->product->title }}"
                        class="img-fluid rounded border"
                        width="70">
                </div>

                <div class="col-md-7">
                    <strong>{{ $item->quantity }} × {{ $item->product->title ?? 'Product' }}</strong>
                    @if($item->variant_name)
                        <div class="text-muted small">
                            {{ $item->variant_name }} : {{ $item->variant_option_name }}
                        </div>
                    @endif
                </div>

                <div class="col-md-4 text-end fw-bold fs-5">
                    ₹ {{ number_format($totalItemPrice) }}
                </div>
            </div>

            <hr>
        @endforeach

        @php
            $couponCode = session('coupon_code');
            $discountAmount = session('discount') ?? 0;
            $finalTotal = $subtotal + ($shippingCharge ?? 0) - $discountAmount;

            if ($finalTotal < 0) $finalTotal = 0;
        @endphp

        <div class="row my-3 text-right text-dark">
            <div class="col-6 fw-bold fs-5">Subtotal</div>
            <div class="col-6 fw-bold fs-5 text-end">₹ {{ number_format($subtotal) }}</div>
        </div>

        @if($couponCode)
            <div class="row my-3 text-right text-dark">
                <div class="col-6 fw-bold fs-5">Coupon ({{ $couponCode }})</div>
                <div class="col-6 fw-bold fs-5 text-end text-success">- ₹ {{ number_format($discountAmount) }}</div>
            </div>
        @endif

        @if(!empty($shippingCharge) && $shippingCharge > 0)
        <div class="row my-3 text-right text-dark">
            <div class="col-6 fw-bold fs-5">Shipping Charge</div>
            <div class="col-6 fw-bold fs-5 text-end">₹ {{ number_format($shippingCharge) }}</div>
        </div>
        @endif

        <div class="row my-3 text-right text-dark">
            <div class="col-6 fw-bold fs-5">Order Total</div>
            <div class="col-6 fw-bold fs-5 text-end">₹ {{ number_format($finalTotal) }}</div>
        </div>

        <hr>

        {{-- ORDER NOTES --}}
        @if($order->order_notes)
            <p class="text-uppercase fw-semibold text-secondary small">Order Notes</p>
            <p>{{ $order->order_notes }}</p>
            <hr>
        @endif

        {{-- DETAILS --}}
        <div class="row py-2 border-bottom">
            <div class="col-md-4 text-label">PAYMENT METHOD</div>
            <div class="col-md-8 text-end">
                {{ ucwords(str_replace('_',' ',$order->payment_method)) }}
            </div>
        </div>

        <div class="row py-2 border-bottom">
            <div class="col-md-4 text-label">ORDERED ON</div>
            <div class="col-md-8 text-end">
                {{ date('d.m.Y H:i:s', strtotime($order->created_at)) }}
            </div>
        </div>

        <div class="row py-2 border-bottom">
            <div class="col-md-4 text-label">DELIVERY ADDRESS</div>
            <div class="col-md-8 text-end">
                {{ $order->street_address }},
                {{ $order->city }},
                {{ $order->state }} - {{ $order->pincode }}
            </div>
        </div>

        <div class="row py-2 border-bottom">
            <div class="col-md-4 text-label">MOBILE</div>
            <div class="col-md-8 text-end">
                {{ $order->phone }}
            </div>
        </div>

        <div class="row py-2 border-bottom">
            <div class="col-md-4 text-label">EMAIL</div>
            <div class="col-md-8 text-end">
                {{ $order->email }}
            </div>
        </div>

        <div class="row py-2">
            <div class="col-md-4 text-label">CUSTOMER NAME</div>
            <div class="col-md-8 text-end">
                {{ $order->first_name }} {{ $order->last_name }}
            </div>
        </div>

    </div>
</div>

<p>We’ll notify you once your order is shipped.</p>

<p>
    Thanks,<br>
    <strong>{{ config('app.name') }}</strong>
</p>

</body>
</html>
