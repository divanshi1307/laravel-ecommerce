@extends('layouts.app')

@section('content')
<div class="container">

    <div class="title">
        <h1 class="text-dark float-left">Orders</h1>
        <div class="clearfix"></div>
    </div>

    <div class="content">
        <h2>Orders List</h2>

        {{-- Success Message --}}
        @if(session()->has('status'))
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i> {{ session()->get('status') }}
            </div>
        @endif

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p class="m-0 pb-2">
                        <i class="fa fa-exclamation-triangle"></i> {{ $error }}
                    </p>
                @endforeach
            </div>
        @endif

        {{-- Filter Form --}}
        <form method="get" action="{{ url('admin/orders') }}">
            <div class="form-group border bg-light border-info row mt-3 mb-5 mx-0">
                <h5 class="col-sm-12 py-2 m-0 border-bottom-info bg-white">Filter</h5>

                <div class="form-group col-sm-3 p-0">
                    <label class="col-sm-12">Order ID</label>
                    <div class="col-sm-12">
                        <input type="text" name="order_id" class="form-control" placeholder="Order ID" value="{{ request()->order_id }}">
                    </div>
                </div>

                <div class="form-group col-sm-3 p-0">
                    <label class="col-sm-12">From Date</label>
                    <div class="col-sm-12">
                        <input type="text" name="from" id="fromDate" class="form-control" placeholder="From Date" value="{{ request()->from }}">
                    </div>
                </div>

                <div class="form-group col-sm-3 p-0">
                    <label class="col-sm-12">To Date</label>
                    <div class="col-sm-12">
                        <input type="text" name="to" id="toDate" class="form-control" placeholder="To Date" value="{{ request()->to }}">
                    </div>
                </div>

                <div class="form-group col-sm-3 p-0">
                    <label class="col-sm-12">Status</label>
                    <div class="col-sm-12">
                        <select name="status" class="form-control">
                            <option value="">Select</option>
                            <option value="pending" @if(request()->status=="pending") selected @endif>Pending</option>
                            <option value="under_processing" @if(request()->status=="under_processing") selected @endif>Under Processing</option>
                            <option value="cancelled" @if(request()->status=="cancelled") selected @endif>Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-3 p-0">
                    <label class="col-sm-12">Payment Type</label>
                    <div class="col-sm-12">
                        <select name="payment_method" class="form-control">
                            <option value="">Select</option>
                            <option value="cod" @if(request()->payment_method=="cod") selected @endif>COD</option>
                            <option value="prepaid" @if(request()->payment_method=="prepaid") selected @endif>Prepaid</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-3 p-0">
                    <label class="col-sm-12">&nbsp;</label>
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary btn-sm mr-2">
                            <i class="fa fa-filter pr-1"></i> Filter
                        </button>
                        <a href="{{ url('admin/orders') }}" class="btn btn-danger btn-sm">Clear</a>
                    </div>
                </div>
            </div>
        </form>

        <hr>
            <a href="{{ url('admin/orders') }}" class="px-3 text-dark">All Orders</a>
        <hr>

        {{-- Orders List --}}
        @if($results->count() > 0)
            <div class="row">
                @foreach($results as $order)
                    <div class="col-sm-12 mb-4">

                        <div class="border">
                            <h6 class="p-3 border-bottom font-weight-normal bg-light d-flex align-items-center">
                                <span class="w-50">
                                    <b class="text-dark">ORDER ID:</b> {{ $order->order_id }}
                                </span>
                                <div class="text-right w-50">
                                    <a class="btn border-dark font-weight-normal mr-3 order-status-{{ $order->status }}">
                                        <b>Order Status:</b> {{ $order->status() }}
                                    </a>
                                    <a href="{{ url('admin/vieworder', $order->id) }}"
                                       class="btn border-secondary bg-white font-weight-normal">
                                        View Order
                                    </a>
                                </div>
                            </h6>

                            {{-- Order Info --}}
                            <div class="col-sm-12 row mx-0">

                                <div class="col-lg-3 col-sm-12 mb-3">
                                    <p class="text-grey">TOTAL AMOUNT</p>
                                    ₹ {{ $order->total }}
                                </div>

                                <div class="col-lg-3 col-sm-12 mb-3">
                                    <p class="text-grey">PAYMENT METHOD</p>
                                    {{ strtoupper($order->payment_method) }}
                                </div>

                                <div class="col-lg-3 col-sm-12 mb-3">
                                    <p class="text-grey">ORDERED ON</p>
                                    {{ $order->created_at->format('d-m-Y H:i:s') }}
                                </div>

                                <div class="col-lg-3 col-sm-12 mb-3">
                                    <p class="text-grey">MOBILE</p>
                                    {{ $order->phone }}
                                </div>

                                <div class="col-lg-3 col-sm-12 mb-3">
                                    <p class="text-grey">CUSTOMER NAME</p>
                                    {{ $order->first_name }} {{ $order->last_name }}
                                </div>

                                <div class="col-lg-9 col-sm-12 mb-3">
                                    <p class="text-grey">DELIVERY ADDRESS</p>
                                    {{ $order->street_address }},
                                    {{ $order->city }},
                                    {{ $order->state }},
                                    {{ $order->pincode }}
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <br>

            {{-- Pagination --}}
            <div class="co-sm-12">
                <div class="float-left">{{ $results->links() }}</div>
                <div class="text-left float-left">
                    Showing {{ $results->count() }} of {{ $results->total() }} Records
                </div>
                <div class="clearfix"></div>
            </div>

        @else
            <div class="alert alert-info">
			    No orders yet !
			</div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function(){
    $('.delete').on('click', function(){
        return confirm("Are you sure you want to delete?");
    });

    $('#fromDate, #toDate').datetimepicker({
        format: 'YYYY-MM-DD', // Only date
        icons: {
            time: 'fa fa-clock',
            date: 'fa fa-calendar',
            up: 'fa fa-arrow-up',
            down: 'fa fa-arrow-down'
        }
    });
});
</script>
@endsection
