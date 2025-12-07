@forelse($wishlist as $item)
<li>
    <div class="cart-widget">

        <!-- PRODUCT IMAGE -->
        <div class="dz-media me-3">
            <img src="{{ asset('uploads/products/' . ($item->product->first_image_url ?? 'no-image.jpg')) }}"
                 alt="{{ $item->product->title }}">
        </div>

        <!-- PRODUCT DETAILS -->
        <div class="cart-content">
            <h6 class="title">
                <a href="{{ route('product.show', $item->product->id) }}">
                    {{ $item->product->title }}
                </a>
            </h6>

            <div class="d-flex align-items-center">
                {{-- SIMPLE PRODUCT --}}
                @if ($item->product->product_type == 'simple')
                    <h6 class="dz-price mb-0">
                        ₹ {{ number_format($item->product->price, 0) }}
                    </h6>

                {{-- VARIANT PRODUCT --}}
                @elseif ($item->product->product_type == 'variant')
                    @php
                        $minPrice = $item->product->variants->min('variant_price');
                    @endphp

                    <h6 class="dz-price mb-0">
                        ₹ {{ number_format($minPrice, 0) }}
                    </h6>
                @endif
            </div>
        </div>

        <!-- REMOVE BUTTON -->
        <a href="javascript:void(0);" class="dz-close remove-wish" data-id="{{ $item->product->id }}">
            <i class="ti-close"></i>
        </a>
    </div>
</li>
@empty
<li>
    <p class="text-center fw-bold fs-5">
        No items in wishlist.
    </p>
</li>

@endforelse
