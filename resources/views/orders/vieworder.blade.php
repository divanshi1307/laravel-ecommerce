@extends('layouts.app')

@section('content')
<div class="container">

    <div class="title">
        <h1 class="text-dark float-left">Orders</h1>
        <a class="btn btn-primary btn-sm float-right" href="{{ url('/admin/orders') }}">
            <i class="fa fa-reply text-light"></i>
        </a>
        <div class="clearfix"></div>
    </div>

    <div class="content">

        <div class="loader"><p><i class="fa fa-spinner fa-5x fa-spin"></i></p></div>

        <h2>Order Detail</h2>
        <hr>

        <div class="clearfix"></div>
        <button class="my-3 btn border border-dark font-weight-bold float-left">
            Order ID: <span>{{ $order->order_id }}</span>
        </button>
        <button class="my-3 btn border border-dark font-weight-bold float-right order-status-{{$order->status}}" data-toggle="dropdown">
            Order Status: <span>{{ $order->status() }}</span>
        </button>
        @if($order->order_status!=4)
        <div class="dropdown-menu dropdown-menu-right">
            @foreach($order::$order_status as $k=>$v)
            <a href="javascript:void(0)" class="dropdown-item update-order-status" id="{{ $k }}">{{ $v }}</a>
            @endforeach
        </div>
        @endif
        <div class="clearfix"></div>
        <hr>

        @if(session('status'))
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i> {{ session('status') }}
            </div>
        @endif

        <div class="col-sm-12">

            {{-- ITEMS --}}
            <p class="text-grey">ITEMS</p>

            <div class="mb-3">

                @foreach($order->items as $item)
                    <div class="w-100 mb-3">

                        {{-- PRODUCT IMAGE --}}
                        <img src="{{ asset('uploads/products/' . $item->product->display_image) }}" alt="{{ $item->product->title }}" class="box-shadow float-left mr-3 mb-3" width="70">

                        <div class="m-0">

                            <div class="float-left">
                                {{ $item->quantity }} × {{ $item->product->title ?? 'Product' }}

                                {{-- Variant --}}
                                @if($item->variant_name)
                                    <br><b>{{ $item->variant_name }}:</b> {{ $item->variant_option_name }}
                                @endif
                            </div>

                            <span class="text-dark float-right px-1 rounded border ml-4 mt-2">
                                {{ config('app.currency_symbol') }} {{ $order->total }}
                            </span>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                @endforeach
            </div>

            <hr>

            {{-- ORDER TOTAL --}}
            <div class="mb-3">
                <div class="float-left w-50 font-weight-bold" style="font-size:18px;">Order Total</div>
                <div class="float-right w-50 font-weight-bold text-right" style="font-size:18px;">
                    {{ config('app.currency_symbol') }} {{ $order->total }}
                </div>
                <div class="clearfix"></div>
            </div>

            <hr>

            {{-- ORDER INSTRUCTIONS --}}
            @if($order->order_notes)
                <div class="mb-3">
                    <p class="text-grey">ORDER NOTES</p>
                    {{ $order->order_notes }}
                </div>
            @endif

            {{-- PAYMENT --}}
            <div class="mb-3 text-right pb-2 border-bottom">
                <p class="text-grey float-left">PAYMENT METHOD</p>
                {{ ucwords(str_replace('_',' ',$order->payment_method)) }}
            </div>

            <div class="mb-3 text-right pb-2 border-bottom">
                <p class="text-grey float-left">ORDERED ON</p>
                {{ date('d.m.Y H:i:s', strtotime($order->created_at)) }}
            </div>

            <div class="mb-3 text-right pb-2 border-bottom">
                <p class="text-grey float-left">DELIVERY ADDRESS</p>
                {{ $order->street_address }}, 
                {{ $order->city }}, 
                {{ $order->state }}, 
                {{ $order->pincode }}
            </div>

            <div class="mb-3 text-right pb-2 border-bottom">
                <p class="text-grey float-left">MOBILE</p>
                {{$order->phone}}
            </div>
            <div class="mb-3 text-right pb-2 border-bottom">
                <p class="text-grey float-left">EMAIL</p>
                {{$order->email}}
            </div>
            <div class="mb-3 text-right pb-2 border-bottom">
                <p class="text-grey float-left">CUSTOMER NAME</p>
                {{ $order->first_name . ' ' . $order->last_name }}
            </div>

        </div>

        <br><br>
        <h2>Order Updates</h2>

        <form method="POST" action="{{ url('/admin/orderupdate') }}">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div class="form-group">
                <textarea name="content" id="content" class="form-control" data-height="200">{{ old('content') }}</textarea>
            </div>

            <button class="btn btn-primary" type="submit">Submit</button>
        </form>

        <hr>

        {{-- ORDER UPDATES LIST --}}
        @if($order->history)
            <table class="table table-bordered">
                <tr>
                    <th>Message</th>
                    <th>Date</th>
                </tr>

                @foreach($order->history as $message)
                    <tr>
                        <td>{!! $message->content !!}</td>
                        <td>{{ date('d-m-Y h:i:s', strtotime($message->date_created)) }}</td>
                    </tr>
                @endforeach

            </table>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    CKEDITOR.replace('content');
    $(document).ready(function() {
        $('.update-order-status').click(function () {
            $('.loader').show(); 
            $.ajax({
                type: "POST",
                url: "{{ url('/admin/updateOrderStatus') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    status: $(this).attr('id'),
                    id: {{ $order->id }}
                },
                success: function(res){
                    $('.loader').hide();
                    if (res.msg == 1) {
                        alert("Status updated successfully");
                        location.reload();
                    } else {
                        $('.loader').hide();
                        alert("Error updating status!");
                    }
                }
            });
        });

    });
</script>
@endsection
