@extends('landing.layout')

@section('content')
    <div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body text-center p-5">

                    <!-- Success Icon -->
                    <div class="mb-4">
                        <i class="fa fa-check-circle" style="font-size: 80px; color: #28a745;"></i>
                    </div>

                    <!-- Heading -->
                    <h2 class="fw-bold" style="color:#28a745;">Order Placed Successfully!</h2>

                    <!-- Order Number -->
                    <p class="mt-3" style="font-size: 18px; color:#555;">
                        Thank you for shopping with us! <br>
                        Your order has been placed and is being processed.
                    </p>


                    <!-- Order Summary Box -->
                    <div class="mt-4 p-4 rounded-3" style="background:#f7f7f7;">
                        <h5 class="fw-bold mb-3">Order Summary</h5>
                        {{-- <p class="mb-1"><strong>Subtotal:</strong> ₹{{ number_format($order->subtotal) }}</p> --}}
                        <p class="mb-1"><strong>Order Id:</strong> {{ $order->order_id }}</p>
                        <p class="mb-1"><strong>Total (Incl. GST):</strong> ₹{{ number_format($order->total) }}</p>
                        <p class="mb-0"><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
