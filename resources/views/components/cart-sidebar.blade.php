<div class="shop-sidebar-cart">
    <ul class="sidebar-cart-list">
        @php
            $cartTotal = 0;                                        
        @endphp

        @forelse($cartItems as $item)
            @php
                $cartTotal += $item->price * $item->quantity;
                $product = $item->product;

                // GST %
                $gstPercentage = 0;
                if (!empty($product->gst) && !empty($product->gst->gst_percentage)) {
                    $gstPercentage = (float) str_replace('%', '', $product->gst->gst_percentage);
                }

                // BASE PRICE
                if ($product->product_type === 'simple') {
                    $finalPrice = $item->price ?? $product->price ?? 0;
                } else {
                    $finalPrice = $item->price ?? 0;
                }

            @endphp
            <li>
                <div class="cart-widget">
                    <div class="dz-media me-3">
                       <img src="{{ $item->image ? asset('uploads/products/' . $item->image) : ($item->product->image 
                                    ? asset('uploads/products/' . $item->product->image) : asset('images/default-product.png')) }}" width="60">

                    </div>

                    <div class="cart-content">
                        <a href="{{ route('product.show', $item->product->id) }}">
                            {{ $item->product->title ?? 'N/A'}}
                        </a>
                        <div class="d-flex align-items-center">
                            <div class="quantity btn-quantity style-1 me-3">
                                <input type="text" value="{{ $item->quantity }}" min="1" class="form-control quantity-input" data-id="{{ $item->id }}">
                            </div>
                            <h6 class="dz-price mb-0">₹{{ number_format($finalPrice, 0) }}</h6>
                        </div>
                    </div>

                    <a href="javascript:void(0);" class="dz-close removeCartItem" data-id="{{ $item->id }}">
                        <i class="ti-close"></i>
                    </a>
                </div>
            </li>
        @empty
            <li class="text-center py-3">
                <strong style="font-size: 18px;">Your cart is empty.</strong>
            </li>
        @endforelse
    </ul>

    @php
        $subtotal = 0;

        foreach ($cartItems as $item) {

            $product = $item->product;

            $gstPercentage = 0;
            if (!empty($product->gst) && !empty($product->gst->gst_percentage)) {
                $gstPercentage = (float) str_replace('%', '', $product->gst->gst_percentage);
            }

            // -------------------------
            // BASE PRICE
            // -------------------------
            if ($product->product_type === 'simple') {
                    $finalPrice = $item->price ?? $product->price ?? 0;
            } else {
                $finalPrice = $item->price ?? 0;
            }
            $subtotal += $finalPrice * $item->quantity;
        }
    @endphp

    @if($subtotal > 0)
        <div id="cart-total-section">
            <div class="cart-total d-flex justify-content-between">
                <h5 class="mb-0">Subtotal:</h5>
                <h5 class="mb-0">₹ {{ number_format($subtotal, 0) }}</h5>
            </div>
        </div>
    @endif
    
    @if($cartItems->count() > 0 && $subtotal > 0)
        <div class="mt-auto" id="cart-action-buttons">
            <a href="{{route('checkout')}}" class="btn btn-outline-secondary btn-block m-b20">Checkout</a>	
            <a href="{{ route('cart.index') }}" class="btn btn-secondary btn-block">View Cart</a>	
        </div>	
    @endif
</div>


