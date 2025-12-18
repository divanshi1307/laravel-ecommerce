@forelse($wishlist as $item)
<li data-id="{{ $item->product_id }}">
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

                @php
                    $product = $item->product;
                    $gstPercentage = 0;
                    if ($product->gst) {
                        $gstPercentage = (float) str_replace('%', '', $product->gst->gst_percentage);
                    }
                @endphp

                {{-- SIMPLE PRODUCT --}}
                @if ($product->product_type == 'simple')

                    @php
                        $price = $product->price ?? 0;
                        $priceWithGst = $price + ($price * $gstPercentage / 100);
                    @endphp

                    <h6 class="dz-price mb-0">₹ {{ number_format($priceWithGst, 0) }}</h6>

                {{-- VARIANT / ADULT PRODUCT --}}
                @elseif ($product->product_type == 'variant' || $product->product_type == 'adult')

                    @php
                        $minAttributePrice = $product->attributeRelations->min('price'); 

                        if ($minAttributePrice) {
                            $minPriceWithGst = $minAttributePrice + ($minAttributePrice * $gstPercentage / 100);
                        } else {
                            $minPriceWithGst = 0;
                        }
                    @endphp

                    <h6 class="dz-price mb-0">₹ {{ number_format($minPriceWithGst, 0) }}</h6>
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
