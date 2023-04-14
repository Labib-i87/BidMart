@extends('layout.navbar')
@section('title', 'Product Bidding')
@section('content')
    <h1>Bid</h1>
    <br>
    <div class="container p-0">
        <div class="card px-4">
            <p class="h8 py-3">Product</p>
            <div class="container mt-5 mb-5">
                @if (Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
                @if (Session::has('fail'))
                    <div class="alert alert-danger">{{ Session::get('fail') }}</div>
                @endif
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
                        <h6>Current Bid: {{ $product->current_price }}</h6>
                        <hr>
                        <form action="{{ url('entry-payment/' . $product->pid) }}" enctype="multipart/form-data"
                            method="GET">
                            @csrf
                            <div class="form-group mt-3">
                                <label for="bid_amount">Enter Bid Amount:</label>
                                <input type="number" class="form-control mt-3" id="bid_amount" name="bid_amount"
                                    value="{{ $product->current_price == 0 ? $product->start_price : $product->current_price }}">

                                <span class="text-danger">
                                    @error('bid_amount')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            {{-- <input type="hidden" name="product_id" value="{{ $product->pid }}"> --}}
                            <button type="submit" class="btn btn-primary mt-3">Bid</button>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
