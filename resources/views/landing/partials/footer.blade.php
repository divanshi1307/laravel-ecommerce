<footer class="site-footer style-1">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                @foreach($categories as $category)
                    <div class="col-xl-2 col-md-4 col-sm-4 col-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="widget widget_services">
                            
                            <h5 class="footer-title">{{ $category->category_name }}</h5>

                            @if($category->children->count() > 0)
                                <ul>
                                    @foreach($category->children as $sub)
                                        <li><a href="{{ url('/subcategory/'.$sub->slug) }}">{{ $sub->category_name }}</a></li>
                                    @endforeach
                                </ul>
                            @endif

                        </div>
                    </div>
                @endforeach

                <div class="col-xl-2 col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="widget widget_about me-2">
                        <div class="footer-logo logo-white">
                            <a href="{{"/"}}"><img src="{{ asset('landing/images/diaper-india-logo.png')}}" alt=""></a> 
                        </div>
                        <ul class="widget-address">
                            <li>support@diaperindia.com</li>
                            <li>(064) 332-1233</li>
                        </ul>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom">
        <div class="container">
            <div class="row fb-inner wow fadeInUp" data-wow-delay="0.1s">
                <div class="col-lg-6 col-md-12 text-start"> 
                    <p class="copyright-text">© <span class="current-year">{{date('Y')}}</span> <a href="{{"/"}}">Diaper India - Brand of Bharat Hygiene India Pvt. Ltd.</a> All Rights Reserved.</p>
                </div>
                <div class="col-lg-6 col-md-12 text-end"> 
                    <div class="d-flex align-items-center justify-content-center justify-content-md-center justify-content-xl-end">
                        <span class="me-3">We Accept: </span>
                        <img src="{{asset('landing/images/footer-img.png')}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</footer>