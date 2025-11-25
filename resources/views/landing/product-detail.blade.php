@extends('landing.layout')

@section('content')
    <div class="page-wraper">
        <div class="page-content bg-light">
            <div class="d-sm-flex justify-content-between container-fluid py-3">
                <nav aria-label="breadcrumb" class="breadcrumb-row">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item">{{ $category->category_name }}</li>
                        <li class="breadcrumb-item active">{{ $subcategory->category_name }}</li>
                    </ul>
                </nav>
            </div>
		
            <section class="content-inner py-0">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-4 col-md-4">
                            <div class="dz-product-detail sticky-top">
                                <div class="swiper-btn-center-lr">
                                    <div class="swiper product-gallery-swiper2 rounded" >
                                        <div class="swiper-wrapper" id="lightgallery2">
                                            <div class="swiper-slide">
                                                <div class="dz-media DZoomImage">
                                                    <a class="mfp-link lg-item" href="images/products/product-detail2/img1.jpg" data-src="images/products/product-detail2/img1.jpg">
                                                        <i class="feather icon-maximize dz-maximize top-left"></i>
                                                    </a>
                                                    <img src="images/products/product-detail2/img1.jpg" alt="image">
                                                </div>
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="dz-media DZoomImage">
                                                    <a class="mfp-link lg-item" href="images/products/product-detail2/img2.jpg" data-src="images/products/product-detail2/img2.jpg">
                                                        <i class="feather icon-maximize dz-maximize top-left"></i>
                                                    </a>
                                                    <img src="images/products/product-detail2/img2.jpg" alt="image">
                                                </div>
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="dz-media DZoomImage">
                                                    <a class="mfp-link lg-item" href="images/products/product-detail2/img4.jpg" data-src="images/products/product-detail2/img4.jpg">
                                                        <i class="feather icon-maximize dz-maximize top-left"></i>
                                                    </a>
                                                    <img src="images/products/product-detail2/img4.jpg" alt="image">
                                                </div>
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="dz-media DZoomImage">
                                                    <a class="mfp-link lg-item" href="images/products/product-detail2/img3.jpg" data-src="images/products/product-detail2/img3.jpg">
                                                        <i class="feather icon-maximize dz-maximize top-left"></i>
                                                    </a>
                                                    <img src="images/products/product-detail2/img3.jpg" alt="image">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper product-gallery-swiper thumb-swiper-lg">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide">
                                                <img src="images/products/product-detail2/thumb-img/1.jpg" alt="image">
                                            </div>
                                            <div class="swiper-slide">
                                                <img src="images/products/product-detail2/thumb-img/2.jpg" alt="image">
                                            </div>
                                            <div class="swiper-slide">
                                                <img src="images/products/product-detail2/thumb-img/3.jpg" alt="image">
                                            </div>
                                            <div class="swiper-slide">
                                                <img src="images/products/product-detail2/thumb-img/4.jpg" alt="image">
                                            </div>
                                        </div>
                                    </div>
                                </div>							
                            </div>	
                        </div>
                        <div class="col-xl-8 col-md-8">
                            <div class="row">
                                <div class="col-xl-7">
                                    <div class="dz-product-detail style-2 p-t20 ps-0">
                                        <div class="dz-content">
                                            <div class="dz-content-footer">
                                                <div class="dz-content-start">
                                                    <span class="badge bg-secondary mb-2">SALE 20% Off</span>
                                                    <h4 class="title mb-1">{{ $product->title }}</h4>
                                                    <div class="review-num">
                                                        <ul class="dz-rating me-2">
                                                            <li class="star-fill">
                                                                <i class="flaticon-star-1"></i>
                                                            </li>										
                                                            <li class="star-fill">
                                                                <i class="flaticon-star-1"></i>
                                                            </li>
                                                            <li class="star-fill">
                                                                <i class="flaticon-star-1"></i>
                                                            </li>
                                                            <li>
                                                                <i class="flaticon-star-1"></i>
                                                            </li>
                                                            <li>
                                                                <i class="flaticon-star-1"></i>
                                                            </li>
                                                        </ul>
                                                        <span class="text-secondary me-2">4.7 Rating</span>
                                                        <a href="javascript:void(0);">(5 customer reviews)</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="para-text">
                                                {!! $product->description !!}
                                            </p>
                                            
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="m-b25">
                                                        <label class="label-title">Pack Of*</label>
                                                        <select class="default-select form-select w-100">
                                                            <option selected>Select Quanity</option>
                                                            <option value="1">34 Pieces</option>
                                                            <option value="2">46 Pieces</option>
                                                            <option value="3">54 Pieces</option>
                                                            <option value="3">86 Pieces</option>
                                                        </select>	
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="m-b25">
                                                        <label class="label-title">Delivery To</label>
                                                        <div class="form-group m-b25">
                                                            <input name="dzName" required="" class="form-control m-b15" placeholder="Enter Pincode">
                                                        </div>	
                                                    </div>
                                                </div>
                                            </div>		
                                            
                                            <div class="dz-info">
                                                <ul>
                                                    <li><strong>SKU:</strong></li>
                                                    <li>{{ $product->product_item_code }}</li>
                                                </ul>
                                                <ul>
                                                    <li><strong>Category:</strong></li>
                                                    <li><a href="shop-standard.html">{{ $category->category_name }}</a></li>												
                                                </ul>
                                                <ul>
                                                    <li><strong>Tags:</strong></li>
                                                    <li>
                                                        <a href="shop-standard.html">{{ $product->meta_tags }}</a>
                                                    </li>												
                                                </ul>
                                                <ul class="social-icon">
                                                    <li><strong>Share:</strong></li>
                                                    <li>
                                                        <a href="https://www.facebook.com/dexignzone" target="_blank">
                                                            <i class="fa-brands fa-facebook-f"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="https://www.linkedin.com/showcase/3686700/admin/" target="_blank">
                                                            <i class="fa-brands fa-linkedin-in"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="https://www.instagram.com/dexignzone/" target="_blank">
                                                            <i class="fa-brands fa-instagram"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="https://twitter.com/dexignzones" target="_blank">
                                                            <i class="fa-brands fa-twitter"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="banner-social-media">
                                            <ul>
                                                <li>
                                                    <a href="https://www.instagram.com/dexignzone/">Instagram</a>
                                                </li>
                                                <li>
                                                    <a href="https://www.facebook.com/dexignzone">Facebook</a>
                                                </li>
                                                <li>
                                                    <a href="https://twitter.com/dexignzones">twitter</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-5">
                                    <div class="cart-detail">
                                        <a href="javascript:void(0);" class="btn btn-outline-secondary w-100 m-b20">Manufacturing Information</a>
                                        <div class="icon-bx-wraper style-4 m-b15">
                                            <div class="icon-bx">
                                                <i class="flaticon flaticon-ship"></i>
                                            </div>
                                            <div class="icon-content">
                                                <span class=" font-14">Country of Origin</span>
                                                <h6 class="dz-title">India</h6>
                                            </div>
                                        </div>
                                        <div class="icon-bx-wraper style-4 m-b30">
                                            <div class="icon-bx">
                                                {{-- Brand Logo --}}
                                                @if($product->brand)
                                                    <img src="{{ asset('storage/' . $product->brand->brand_logo) }}" alt="{{ $product->brand->brand_name }}">
                                                @else
                                                    <img src="{{ asset('images/default-brand.png') }}" alt="Brand">
                                                @endif
                                            </div>

                                            <div class="icon-content">
                                                <h6 class="dz-title">{{ $product->brand->brand_name ?? 'Unknown Brand' }}</h6>
                                                @php
                                                    $cleanDesc = html_entity_decode(strip_tags($product->brand->description));
                                                    $shortDesc = Str::words($cleanDesc, 20, '...');
                                                @endphp
                                                <p>{{ $shortDesc }}</p>
                                            </div>  
                                        </div>

                                        <div class="save-text">
                                            <i class="icon feather icon-check-circle"></i>
                                            <span class="m-l10">You will save ₹ 35 on this order</span>
                                        </div>
                                        <table>
                                            <tbody>
                                                <tr class="total">
                                                    <td>
                                                        <h6 class="mb-0">Total</h6>
                                                    </td>
                                                    <td class="price">
                                                        ₹ 512
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <a href="shop-wishlist.html" class="btn btn-outline-secondary btn-icon m-b20">
                                            <svg width="19" height="17" viewBox="0 0 19 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.24805 16.9986C8.99179 16.9986 8.74474 16.9058 8.5522 16.7371C7.82504 16.1013 7.12398 15.5038 6.50545 14.9767L6.50229 14.974C4.68886 13.4286 3.12289 12.094 2.03333 10.7794C0.815353 9.30968 0.248047 7.9162 0.248047 6.39391C0.248047 4.91487 0.755203 3.55037 1.67599 2.55157C2.60777 1.54097 3.88631 0.984375 5.27649 0.984375C6.31552 0.984375 7.26707 1.31287 8.10464 1.96065C8.52734 2.28763 8.91049 2.68781 9.24805 3.15459C9.58574 2.68781 9.96875 2.28763 10.3916 1.96065C11.2292 1.31287 12.1807 0.984375 13.2197 0.984375C14.6098 0.984375 15.8885 1.54097 16.8202 2.55157C17.741 3.55037 18.248 4.91487 18.248 6.39391C18.248 7.9162 17.6809 9.30968 16.4629 10.7792C15.3733 12.094 13.8075 13.4285 11.9944 14.9737C11.3747 15.5016 10.6726 16.1001 9.94376 16.7374C9.75136 16.9058 9.50417 16.9986 9.24805 16.9986ZM5.27649 2.03879C4.18431 2.03879 3.18098 2.47467 2.45108 3.26624C1.71033 4.06975 1.30232 5.18047 1.30232 6.39391C1.30232 7.67422 1.77817 8.81927 2.84508 10.1066C3.87628 11.3509 5.41011 12.658 7.18605 14.1715L7.18935 14.1743C7.81021 14.7034 8.51402 15.3033 9.24654 15.9438C9.98344 15.302 10.6884 14.7012 11.3105 14.1713C13.0863 12.6578 14.6199 11.3509 15.6512 10.1066C16.7179 8.81927 17.1938 7.67422 17.1938 6.39391C17.1938 5.18047 16.7858 4.06975 16.045 3.26624C15.3152 2.47467 14.3118 2.03879 13.2197 2.03879C12.4197 2.03879 11.6851 2.29312 11.0365 2.79465C10.4585 3.24179 10.0558 3.80704 9.81975 4.20255C9.69835 4.40593 9.48466 4.52733 9.24805 4.52733C9.01143 4.52733 8.79774 4.40593 8.67635 4.20255C8.44041 3.80704 8.03777 3.24179 7.45961 2.79465C6.811 2.29312 6.07643 2.03879 5.27649 2.03879Z" fill="black"></path>
                                            </svg>
                                            Add To Wishlist
                                        </a>
                                        <a href="shop-cart.html" class="btn btn-secondary w-100">ADD TO CART</a>
                                    </div>	
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
		
            <section class="content-inner-3 pb-0"> 
                <div class="container">
                    <div class="product-description">
                        <div class="dz-tabs">					
                            <ul class="nav nav-tabs center" id="myTab1" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Description</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Reviews (12)</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                    <div class="detail-bx text-center">
                                        <h5 class="title">Huggies Baby Diaper</h5>
                                        <p class="para-text">
                                            Introducing New & Improved Huggies Comfy Pants 1. Upto 50% faster absorption~ for quick dry feel 2. With 1000 absorption funnels allow faster absorption and improved air flow 3. Bubble Softness - India's 1st Diaper Pants with Bubble Bed to wrap your baby in softness 4. Up to 10 Hours of Overnight Absorption# for comfortable all night sleep 5. Double Leak Guard - Extra Padding to prevent thigh leakage 6. 360 degree Soft Fit Waistband@ - Cushiony waistband to ensure a snug fit 7. Available in all diaper sizes - diaper XS Size (new born diaper - up to 5 Kgs) , diaper S size (4-8 Kgs) , diaper M size (7-12 Kgs) , diaper L size (9-14 Kgs) , diaper XL size (12-17 Kgs)
                                        </p>
                                        <ul class="feature-detail">
                                            <li>
                                                <i class="icon feather icon-check"></i>
                                                <h5>Closure Type : Adhesive Band</h5>
                                            </li>
                                            <li>
                                                <i class="icon feather icon-check"></i>
                                                <h5>Effective Duration : 25 hours</h5>
                                            </li>
                                            <li>
                                                <i class="icon feather icon-check"></i>
                                                <h5>Maximum Shelf Life : 1095 Days</h5>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="product-specification">
                                        <h4 class="specification-title">Specifications</h4>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 m-lg-b0 m-md-b30">
                                            <ul class="specification-list m-b40">
                                                <li class="list-info">Manufacturer <span>Indra Hosiery Mills</span></li>
                                                <li class="list-info">ASIN<span>B07WK128569</span></li>
                                                <li class="list-info">Country of Origin<span>India</span></li>
                                                <li class="list-info">Department<span>Women</span></li>
                                                <li class="list-info">Included Components<span>Women's Jacket</span></li>
                                                <li class="list-info">Item Dimensions LxWxH<span> 71.1 x 45.7 x 7.6 Centimeters</span></li>
                                                <li class="list-info">Manufacture<span> Indra Hosiery Mills</span></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="row g-lg-4 g-3">
                                        <div class="col-xl-4 col-md-4 col-sm-4 col-6">
                                            <div class="related-img dz-media">
                                                <img src="images/feature/product-feature/1.jpg" alt="/">
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-sm-4 col-6">
                                            <div class="related-img dz-media">
                                                <img src="images/feature/product-feature/2.jpg" alt="/">
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-sm-4">
                                            <div class="related-img dz-media">
                                                <img src="images/feature/product-feature/3.jpg" alt="/">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                                    <div class="clear" id="comment-list">
                                        <div class="post-comments comments-area style-1 clearfix">
                                            <h4 class="comments-title mb-2">Comments (02)</h4>
                                            <p class="dz-title-text">There are many variations of passages of Lorem Ipsum available.</p>
                                            <div id="comment">
                                                <ol class="comment-list">
                                                    <li class="comment even thread-even depth-1 comment" id="comment-2">
                                                        <div class="comment-body">
                                                            <div class="comment-author vcard">
                                                                    <img src="images/profile4.jpg" alt="/" class="avatar">
                                                                    <cite class="fn">Michel Poe</cite> 
                                                            </div>
                                                            <div class="comment-content dz-page-text">
                                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                                                            </div>
                                                            <div class="reply">
                                                                <a rel="nofollow" class="comment-reply-link" href="javascript:void(0);">Reply</a>
                                                            </div>
                                                        </div>
                                                        <ol class="children">
                                                            <li class="comment byuser comment-author-w3itexpertsuser bypostauthor odd alt depth-2 comment" id="comment-3">
                                                                <div class="comment-body" id="div-comment-3">
                                                                    <div class="comment-author vcard">
                                                                    <img src="images/profile3.jpg" alt="/" class="avatar">
                                                                    <cite class="fn">Celesto Anderson</cite>
                                                                    </div>
                                                                    <div class="comment-content dz-page-text">
                                                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                                                                    </div>
                                                                    <div class="reply">
                                                                    <a class="comment-reply-link" href="javascript:void(0);"> Reply</a>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ol>
                                                    </li>
                                                    <li class="comment even thread-odd thread-alt depth-1 comment" id="comment-4">
                                                        <div class="comment-body" id="div-comment-4">
                                                            <div class="comment-author vcard">
                                                                <img src="images/profile2.jpg" alt="/" class="avatar">
                                                                <cite class="fn">Monsur Rahman Lito</cite>
                                                            </div>
                                                            <div class="comment-content dz-page-text">
                                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                                                            </div>
                                                            <div class="reply">
                                                                <a class="comment-reply-link" href="javascript:void(0);"> Reply</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ol>
                                            </div>
                                            <div class="default-form comment-respond style-1" id="respond">
                                                <h4 class="comment-reply-title mb-2" id="reply-title">Good Comments</h4>
                                                <p class="dz-title-text">There are many variations of passages of Lorem Ipsum available.</p>
                                                <div class="comment-form-rating d-flex">
                                                    <label class="pull-left m-r10 m-b20  text-secondary">Your Rating</label>
                                                    <div class="rating-widget">
                                                        <!-- Rating Stars Box -->
                                                        <div  class="rating-stars">
                                                            <ul id="stars">
                                                                <li class="star" title="Poor" data-value="1">
                                                                    <i class="fas fa-star fa-fw"></i>
                                                                </li>
                                                                <li class="star" title="Fair" data-value="2">
                                                                    <i class="fas fa-star fa-fw"></i>
                                                                </li>
                                                                <li class="star" title="Good" data-value="3">
                                                                    <i class="fas fa-star fa-fw"></i>
                                                                </li>
                                                                <li class="star" title="Excellent" data-value="4">
                                                                    <i class="fas fa-star fa-fw"></i>
                                                                </li>
                                                                <li class="star" title="WOW!!!" data-value="5">
                                                                    <i class="fas fa-star fa-fw"></i>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix">
                                                    <form method="post" id="comments_form" class="comment-form" novalidate>
                                                        <p class="comment-form-author"><input id="name" placeholder="Author" name="author" type="text" value=""></p>
                                                        <p class="comment-form-email"><input id="email" required="required" placeholder="Email" name="email" type="email" value=""></p>
                                                        <p class="comment-form-comment"><textarea id="comments" placeholder="Type Comment Here" class="form-control4" name="comment" cols="45" rows="3" required="required"></textarea></p>
                                                        <p class="col-md-12 col-sm-12 col-xs-12 form-submit">
                                                            <button id="submit" type="submit" class="submit btn btn-secondary btnhover3 filled">
                                                            Submit Now
                                                            </button>
                                                        </p>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
		
		    <section class="content-inner-1  overflow-hidden">
			    <div class="container">
                    <div class="section-head style-2 d-md-flex justify-content-between align-items-center">
                        <div class="left-content">
                            <h2 class="title mb-0">Related products</h2>
                        </div>
                        <a href="shop-list.html" class="text-secondary font-14 d-flex align-items-center gap-1">See all products
                            <i class="icon feather icon-chevron-right font-18"></i>
                        </a>			
                    </div>
				    <div class="swiper-btn-center-lr">
					    <div class="swiper swiper-four">
						    <div class="swiper-wrapper">
							    <div class="swiper-slide">
								    <div class="shop-card style-1">
									    <div class="dz-media">
										    <img src="images/shop/product/1.jpg" alt="image">
                                            <div class="shop-meta">
                                                <a href="javascript:void(0);" class="btn btn-secondary btn-md btn-rounded" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    <i class="fa-solid fa-eye d-md-none d-block"></i>
                                                    <span class="d-md-block d-none">Quick View</span>
                                                </a>
                                                <div class="btn btn-primary meta-icon dz-wishicon">
                                                    <i class="icon feather icon-heart dz-heart"></i>
                                                    <i class="icon feather icon-heart-on dz-heart-fill"></i>
                                                </div>
                                                <div class="btn btn-primary meta-icon dz-carticon">
                                                    <i class="flaticon flaticon-basket"></i>
                                                    <i class="flaticon flaticon-shopping-basket-on dz-heart-fill"></i>
                                                </div>
                                            </div>								
									    </div>
									    <div class="dz-content">
									        <h5 class="title"><a href="#">MamyPoko Pants Standard Diapers....</a></h5>
									        <h5 class="price">₹ 359</h5>
									    </div>
									    <div class="product-tag">
									        <span class="badge ">Get 20% Off</span>
									    </div>
								    </div>
							    </div>
							    <div class="swiper-slide">
								    <div class="shop-card style-1">
									    <div class="dz-media">
										    <img src="images/shop/product/2.jpg" alt="image">
										    <div class="shop-meta">
                                                <a href="javascript:void(0);" class="btn btn-secondary btn-md btn-rounded" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    <i class="fa-solid fa-eye d-md-none d-block"></i>
                                                    <span class="d-md-block d-none">Quick View</span>
                                                </a>
                                                <div class="btn btn-primary meta-icon dz-wishicon">
                                                    <i class="icon feather icon-heart dz-heart"></i>
                                                    <i class="icon feather icon-heart-on dz-heart-fill"></i>
                                                </div>
                                                <div class="btn btn-primary meta-icon dz-carticon">
                                                    <i class="flaticon flaticon-basket"></i>
                                                    <i class="flaticon flaticon-shopping-basket-on dz-heart-fill"></i>
                                                </div>
                                            </div>								
									    </div>
									    <div class="dz-content">
                                            <h5 class="title"><a href="shop-list.html">Wowper Fresh Baby Diaper Pants...</a></h5>
                                            <h5 class="price">₹ 469</h5>
                                        </div>
                                        <div class="product-tag">
                                            <span class="badge ">Get 20% Off</span>
                                        </div>
								    </div>
							    </div>
							    <div class="swiper-slide">
								    <div class="shop-card style-1">
									    <div class="dz-media">
										    <img src="images/shop/product/3.jpg" alt="image">
										    <div class="shop-meta">
                                                <a href="javascript:void(0);" class="btn btn-secondary btn-md btn-rounded" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    <i class="fa-solid fa-eye d-md-none d-block"></i>
                                                    <span class="d-md-block d-none">Quick View</span>
                                                </a>
                                                <div class="btn btn-primary meta-icon dz-wishicon">
                                                    <i class="icon feather icon-heart dz-heart"></i>
                                                    <i class="icon feather icon-heart-on dz-heart-fill"></i>
                                                </div>
                                                <div class="btn btn-primary meta-icon dz-carticon">
                                                    <i class="flaticon flaticon-basket"></i>
                                                    <i class="flaticon flaticon-shopping-basket-on dz-heart-fill"></i>
                                                </div>
                                            </div>								
									    </div>
										<div class="dz-content">
                                            <h5 class="title"><a href="shop-list.html">CooCoo Premium Extra Large...</a></h5>
                                            <h5 class="price">₹ 469</h5>
                                        </div>
										<div class="product-tag">
											<span class="badge ">Get 20% Off</span>
										</div>
									</div>
							    </div>
                                <div class="swiper-slide">
                                    <div class="shop-card style-1">
                                        <div class="dz-media">
                                            <img src="images/shop/product/4.jpg" alt="image">
                                            <div class="shop-meta">
                                                <a href="javascript:void(0);" class="btn btn-secondary btn-md btn-rounded" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    <i class="fa-solid fa-eye d-md-none d-block"></i>
                                                    <span class="d-md-block d-none">Quick View</span>
                                                </a>
                                                <div class="btn btn-primary meta-icon dz-wishicon">
                                                    <i class="icon feather icon-heart dz-heart"></i>
                                                    <i class="icon feather icon-heart-on dz-heart-fill"></i>
                                                </div>
                                                <div class="btn btn-primary meta-icon dz-carticon">
                                                    <i class="flaticon flaticon-basket"></i>
                                                    <i class="flaticon flaticon-shopping-basket-on dz-heart-fill"></i>
                                                </div>
                                            </div>								
                                        </div>
                                        <div class="dz-content">
                                            <h5 class="title"><a href="shop-list.html">Pampers All Round Protection Diaper...</a></h5>
                                            <h5 class="price">₹ 592</h5>
                                        </div>
                                        <div class="product-tag">
                                            <span class="badge ">Get 20% Off</span>
                                        </div>
                                    </div>
							    </div>
                                <div class="swiper-slide">
                                    <div class="shop-card style-1">
                                        <div class="dz-media">
                                            <img src="images/shop/product/5.jpg" alt="image">
                                            <div class="shop-meta">
                                                <a href="javascript:void(0);" class="btn btn-secondary btn-md btn-rounded" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    <i class="fa-solid fa-eye d-md-none d-block"></i>
                                                    <span class="d-md-block d-none">Quick View</span>
                                                </a>
                                                <div class="btn btn-primary meta-icon dz-wishicon">
                                                    <i class="icon feather icon-heart dz-heart"></i>
                                                    <i class="icon feather icon-heart-on dz-heart-fill"></i>
                                                </div>
                                                <div class="btn btn-primary meta-icon dz-carticon">
                                                    <i class="flaticon flaticon-basket"></i>
                                                    <i class="flaticon flaticon-shopping-basket-on dz-heart-fill"></i>
                                                </div>
                                            </div>								
                                        </div>
                                        <div class="dz-content">
                                            <h5 class="title"><a href="shop-list.html">Little's Soft Cleansing Baby Wipes...</a></h5>
                                            <h5 class="price">₹ 352</h5>
                                        </div>
                                        <div class="product-tag">
                                            <span class="badge ">Get 20% Off</span>
                                        </div>
                                    </div>
                                </div>
						    </div>
					    </div>
                        <div class="pagination-align">
                            <div class="tranding-button-prev btn-prev">
                                <i class="flaticon flaticon-left-chevron"></i>
                            </div>
                            <div class="tranding-button-next btn-next">
                                <i class="flaticon flaticon-chevron"></i>
                            </div>
                        </div>
				    </div>
			    </div>
		    </section>
	    </div>
    </div>
@endsection