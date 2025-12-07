<header class="site-header mo-left header border-bottom">		
	<div class="sticky-header main-bar-wraper navbar-expand-lg">
		<div class="main-bar clearfix">
			<div class="container-fluid clearfix d-lg-flex d-block">
                <div class="logo-header logo-dark me-md-2">
                    <a href="{{"/"}}">
                        <img src="{{ asset('landing/images/diaper-india-logo.png')}}" alt="logo">
                    </a>
                </div>
			<div>
			<form class="header-item-search" action="{{ url('/') }}" method="GET">
			    <div class="input-group search-input">
                    <select class="default-select">
                        <option>All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="search" name="q" class="form-control" placeholder="Search Product" value="{{ request('q') }}">
                    <button class="btn btn-light" type="submit">
                        <i class="iconly-Light-Search"></i>
                    </button>
			    </div>
		    </form>
	    </div>
        <button class="navbar-toggler collapsed navicon justify-content-end" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span></span>
            <span></span>
            <span></span>
        </button>
					
        <div class="header-nav w3menu navbar-collapse collapse justify-content-start" id="navbarNavDropdown">
            <div class="logo-header logo-dark">
                <a href="index.html">
                    <img src="images/diaper-india-logo.png" alt="">
                </a>
            </div>
                        
            <div class="dz-social-icon">
                <ul>
                    <li><a class="fab fa-facebook-f" target="_blank" href="https://www.facebook.com/dexignzone"></a></li>
                    <li><a class="fab fa-twitter" target="_blank" href="https://twitter.com/dexignzones"></a></li>
                    <li><a class="fab fa-linkedin-in" target="_blank" href="https://www.linkedin.com/showcase/3686700/admin/"></a></li>
                    <li><a class="fab fa-instagram" target="_blank" href="https://www.instagram.com/dexignzone/"></a></li>
                </ul>
            </div>
        </div>

        @if(auth()->check())
            <div class="header-nav w3menu navbar-collapse collapse justify-content-start" id="navbarNavDropdown">
                <ul class="nav navbar-nav ms-auto">
                    <li class="sub-menu-down">
                        <a href="javascript:void(0);"><span>My Account</span> <i class="fas fa-chevron-down tabindex"></i></a>
                        <ul class="sub-menu">						
                            <li><a href="{{ route('account.dashboard') }}">Dashboard</a></li>
                            <li><a href="{{route('orders.index')}}">Orders</a></li>
                            <li><a href="account-order-details.html">Orders Details</a></li>
                            
                        </ul>
                    </li>
                </ul>
            </div>
        @endif
				
        <!-- EXTRA NAV -->
        <div class="extra-nav">
            <div class="extra-cell">						
                <ul class="header-right">
                    <li class="nav-item login-link">
                        @if(auth()->check())
                            <li class="nav-item dropdown">
                                <a class="nav-link d-flex align-items-center gap-2"
                                    href="#" id="userMenu"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ auth()->user()->profile_image ? asset('uploads/profile/' . auth()->user()->profile_image) : 'https://cdn-icons-png.flaticon.com/512/847/847969.png' }}" 
                                        alt="User Avatar" class="rounded-circle" style="width:32px; height:32px; object-fit:cover;">

                                    <span class="fw-semibold">{{ auth()->user()->name }}</span>
                                </a>
                            </li>
                        @else
                            <a class="nav-link" href="{{"/login"}}">
                                Login / Register
                            </a>
                        @endif
                    </li>
                    
                    <li class="nav-item wishlist-link">
                        <a class="nav-link" href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                            <i class="iconly-Light-Heart2"></i>
                        </a>
                    </li>
                    <li class="nav-item cart-link">
                        <a href="javascript:void(0);" class="nav-link cart-btn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                            <i class="iconly-Broken-Buy"></i>
                            <span class="badge badge-circle cart-count">{{ $cartItemsCount ?? 0 }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
	</div>
	</div>
	</div>
	<!-- Main Header End -->
		
	<!-- Sidebar cart -->
	<div class="offcanvas dz-offcanvas offcanvas offcanvas-end " tabindex="-1" id="offcanvasRight">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
            ×
        </button>
        <div class="offcanvas-body">
            <div class="product-description">
                <div class="dz-tabs">
                    <ul class="nav nav-tabs center" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="shopping-cart" data-bs-toggle="tab" data-bs-target="#shopping-cart-pane" type="button" role="tab" aria-controls="shopping-cart-pane" aria-selected="true">
                                Shopping Cart
                                <span class="badge badge-light cart-count">{{ $cartItemsCount ?? 0 }}</span>
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="wishlist" data-bs-toggle="tab" data-bs-target="#wishlist-pane" type="button" role="tab" aria-controls="wishlist-pane" aria-selected="false" tabindex="-1">
                                Wishlist
                                <span class="badge badge-light" id="wishlist-count">
                                    {{ $wishlist->count() }}
                                </span>
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content pt-4" id="dz-shopcart-sidebar">
                        <div class="tab-pane fade show active" id="shopping-cart-pane" role="tabpanel" aria-labelledby="shopping-cart" tabindex="0">

                            @include('components.cart-sidebar', [
                                    'cartItems' => $cartItems,
                                    'cartTotal' => $cartTotal
                            ])
                        </div>

                        <div class="tab-pane fade" id="wishlist-pane" role="tabpanel" aria-labelledby="wishlist" tabindex="0">
                            <div class="shop-sidebar-cart">
                                <ul class="sidebar-cart-list" id="wishlistArea"></ul>
                                <div class="mt-auto">
                                    <a href="{{ route('wishlist.index') }}" class="btn btn-secondary btn-block">Check Your Favourite</a>
                                </div>	
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<!-- Sidebar cart -->

    <!-- Sidebar finter -->
    {{-- <div class="offcanvas dz-offcanvas offcanvas offcanvas-end " tabindex="-1" id="offcanvasLeft">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
            ×
        </button>
        <div class="offcanvas-body">
            <div class="product-description">
                <div class="widget widget_search">
                    <div class="form-group">
                        <div class="input-group">
                            <input name="dzSearch" required="required" type="search" class="form-control" placeholder="Search Product">
                            <div class="input-group-addon">
                                <button name="submit" value="Submit" type="submit" class="btn">
                                    <i class="icon feather icon-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget">
                    <h6 class="widget-title">Price</h6>
                    <div class="price-slide range-slider">
                        <div class="price">
                            <div class="range-slider style-1">
                                <div id="slider-tooltips" class="mb-3 noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr"><div class="noUi-base"><div class="noUi-connects"><div class="noUi-connect" style="transform: translate(10%, 0px) scale(0.765, 1);"></div></div><div class="noUi-origin" style="transform: translate(-90%, 0px); z-index: 5;"><div class="noUi-handle noUi-handle-lower" data-handle="0" tabindex="0" role="slider" aria-orientation="horizontal" aria-valuemin="0.0" aria-valuemax="346.0" aria-valuenow="40.0" aria-valuetext="40"><div class="noUi-touch-area"></div><div class="noUi-tooltip">40.0</div></div></div><div class="noUi-origin" style="transform: translate(-13.5%, 0px); z-index: 4;"><div class="noUi-handle noUi-handle-upper" data-handle="1" tabindex="0" role="slider" aria-orientation="horizontal" aria-valuemin="40.0" aria-valuemax="400.0" aria-valuenow="346.0" aria-valuetext="346"><div class="noUi-touch-area"></div><div class="noUi-tooltip">346</div></div></div></div></div>
                                <span class="example-val" id="slider-margin-value-min">Min Price: $40</span>
                                <span class="example-val" id="slider-margin-value-max">Max Price: $346</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget">
                    <h6 class="widget-title">Color</h6>
                    <div class="d-flex align-items-center flex-wrap color-filter ps-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radioNoLabel" id="radioNoLabel1" value="#000000" aria-label="..." checked="">
                            <span style="background-color: rgb(0, 0, 0);"></span>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radioNoLabel" id="radioNoLabel2" value="#9BD1FF" aria-label="...">
                            <span style="background-color: rgb(155, 209, 255);"></span>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radioNoLabel" id="radioNoLabel3" value="#21B290" aria-label="...">
                            <span style="background-color: rgb(33, 178, 144);"></span>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radioNoLabel" id="radioNoLabel4" value="#FEC4C4" aria-label="...">
                            <span style="background-color: rgb(254, 196, 196);"></span>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radioNoLabel" id="radioNoLabel5" value="#FF7354" aria-label="...">
                            <span style="background-color: rgb(255, 115, 84);"></span>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radioNoLabel" id="radioNoLabel6" value="#51EDC8" aria-label="...">
                            <span style="background-color: rgb(81, 237, 200);"></span>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radioNoLabel" id="radioNoLabel7" value="#B77CF3" aria-label="...">
                            <span style="background-color: rgb(183, 124, 243);"></span>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radioNoLabel" id="radioNoLabel8" value="#FF4A76" aria-label="...">
                            <span style="background-color: rgb(255, 74, 118);"></span>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radioNoLabel" id="radioNoLabel9" value="#3E68FF" aria-label="...">
                            <span style="background-color: rgb(62, 104, 255);"></span>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radioNoLabel" id="radioNoLabe20" value="#7BEF68" aria-label="...">
                            <span style="background-color: rgb(123, 239, 104);"></span>
                        </div>
                    </div>
                </div>

                <div class="widget">
                    <h6 class="widget-title">Size</h6>
                    <div class="btn-group product-size">
                        <input type="radio" class="btn-check" name="btnradio1" id="btnradio11" checked="">
                        <label class="btn" for="btnradio11">4</label>

                        <input type="radio" class="btn-check" name="btnradio1" id="btnradio21">
                        <label class="btn" for="btnradio21">6</label>

                        <input type="radio" class="btn-check" name="btnradio1" id="btnradio31">
                        <label class="btn" for="btnradio31">8</label>
                        
                        <input type="radio" class="btn-check" name="btnradio1" id="btnradio41">
                        <label class="btn" for="btnradio41">10</label>
                        
                        <input type="radio" class="btn-check" name="btnradio1" id="btnradio51">
                        <label class="btn" for="btnradio51">12</label>
                        
                        <input type="radio" class="btn-check" name="btnradio1" id="btnradio61">
                        <label class="btn" for="btnradio61">14</label>
                        
                        <input type="radio" class="btn-check" name="btnradio1" id="btnradio71">
                        <label class="btn" for="btnradio71">16</label>
                        
                        <input type="radio" class="btn-check" name="btnradio1" id="btnradio81">
                        <label class="btn" for="btnradio81">18</label>
                        
                        <input type="radio" class="btn-check" name="btnradio1" id="btnradio91">
                        <label class="btn" for="btnradio91">20</label>
                    </div>
                </div>
                <div class="widget widget_categories">
                    <h6 class="widget-title">Category</h6>
                    <ul>
                        <li class="cat-item cat-item-26"><a href="blog-category.html">Dresses</a> (10)</li>
                        <li class="cat-item cat-item-36"><a href="blog-category.html">Top &amp; Blouses</a> (5)</li>
                        <li class="cat-item cat-item-43"><a href="blog-category.html">Boots</a> (17)</li>
                        <li class="cat-item cat-item-27"><a href="blog-category.html">Jewelry</a> (13)</li>
                        <li class="cat-item cat-item-40"><a href="blog-category.html">Makeup</a> (06)</li> 
                        <li class="cat-item cat-item-40"><a href="blog-category.html">Fragrances</a> (17)</li> 
                        <li class="cat-item cat-item-40"><a href="blog-category.html">Shaving &amp; Grooming</a> (13)</li> 
                        <li class="cat-item cat-item-43"><a href="blog-category.html">Jacket</a> (06)</li> 
                        <li class="cat-item cat-item-36"><a href="blog-category.html">Coat</a> (22)</li> 
                    </ul>
                </div>

                <div class="widget widget_tag_cloud">
                    <h6 class="widget-title">Tags</h6>
                    <div class="tagcloud"> 
                        <a href="blog-tag.html">Vintage </a>
                        <a href="blog-tag.html">Wedding</a>
                        <a href="blog-tag.html">Cotton</a>
                        <a href="blog-tag.html">Linen</a>
                        <a href="blog-tag.html">Navy</a>
                        <a href="blog-tag.html">Urban</a>
                        <a href="blog-tag.html">Business Meeting</a>
                        <a href="blog-tag.html">Formal</a>
                    </div>
                </div>
                <a href="javascript:void(0);" class="btn btn-sm font-14 btn-secondary btn-sharp">RESET</a>
            </div>
        </div>
    </div> --}}
    <!-- filter sidebar -->
		
</header>
