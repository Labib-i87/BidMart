@extends('layout.navbar')
@section('title', 'Product Information')
@section('content')
    <h1>Product Info</h1>
    <br>
    @if (Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    @if (Session::has('fail'))
        <div class="alert alert-danger">{{ Session::get('fail') }}</div>
    @endif
    <div class="container p-0">
        <div class="card px-4">
            <p class="h8 py-3">Product</p>
            <div class="container mt-5 mb-5">

                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <img src="{{ asset('uploads/' . $product->image_path) }}" alt="Product Image" class="img-fluid">
                    </div>
                    <div class="col-lg-6">
                        <h2>{{ $product->product_name }}</h2>
                        <hr>
                        <h6>Description:</h6>
                        <p>{{ $product->description }}</p>
                        <hr>
                        <h6>Starting Price: {{ $product->start_price }}</h6>
                        @if ($product->status == 'sold')
                            <h6>Purchase Price: {{ $product->current_price }}</h6>
                        @else
                            <h6>Current Bid: {{ $product->current_price }}</h6>
                        @endif
                        <hr>
                        @if ($data->user_type == '1' && $product->status != 'sold')
                            <a href="{{ url('edit-product/' . $product->pid) }}" class="btn btn-primary btn-sm mx-2"
                                style="width: 75px;">Edit</a>
                            <a href="{{ url('delete-product/' . $product->pid) }}" class="btn btn-danger btn-sm mx-2"
                                style="width: 75px;">Delete</a>
                            <a href="{{ url('sell-product/' . $product->pid) }}" class="btn btn-success btn-sm mx-2"
                                style="width: 75px;">Sell</a>
                        @elseif ($data->user_type != '1' && $product->status != 'sold')
                            <a href="{{ route('bid', $product->pid) }}" class="btn btn-success btn-sm mx-2"
                                style="width: 75px;">Bid</a>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
