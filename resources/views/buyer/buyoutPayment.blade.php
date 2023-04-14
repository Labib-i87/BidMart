@extends('layout.navbar')
@section('title', 'Payment')
@section('content')
    <div class="container p-0">
        <div class="card px-4">
            <p class="h8 py-3">Buyout Payment</p>
            <div class="row gx-3">
                <form action="{{ route('buyout', $product->pid) }}" enctype="multipart/form-data" method="GET">
                    @if (Session::has('success'))
                        <div class="alert alert-success">{{ Session::get('success') }}</div>
                    @endif
                    @if (Session::has('fail'))
                        <div class="alert alert-danger">{{ Session::get('fail') }}</div>
                    @endif
                    @csrf

                    <div class="col-12">
                        <div class="d-flex flex-column">
                            <p class="text mb-1">Card Holder's Name</p>
                            <input class="form-control mb-3" type="text" name="name" placeholder="Name"
                                value="{{ old('name') }}">
                            <span class="text-danger">
                                @error('name')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex flex-column">
                            <p class="text mb-1">Card Number</p>
                            <input class="form-control mb-3" type="text" name="number" placeholder="1234 5678 4356 7890"
                                value="{{ old('number') }}">
                            <span class="text-danger">
                                @error('number')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <p class="text mb-1">Expiry</p>
                            <input class="form-control mb-3" type="date" name="date" placeholder="DD/MM/YYYY"
                                value="{{ old('date') }}">
                            <span class="text-danger">
                                @error('date')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <p class="text mb-1">CVV/CVC</p>
                            <input class="form-control mb-3 pt-2 " type="password" name="cvv" placeholder="***"
                                value="{{ old('cvv') }}">
                            <span class="text-danger">
                                @error('cvv')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>


                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <p class="text mb-1">Payable Amount: </p>

                            @if ($bidder)
                                <p class="text mb-1">{{ $product->buyout_price - 0 }} - {{ 1000 }} =
                                    {{ $product->buyout_price - 1000 }} TK</p>
                            @else
                                <p class="text mb-1">
                                    {{ $product->buyout_price }} TK</p>
                            @endif
                        </div>
                    </div>
                    <br>
                    @if ($bidder)
                        <button type="submit" class="btn btn-primary mb-4">Pay {{ $product->buyout_price - 1000 }}
                            TK</button>
                    @else
                        <button type="submit" class="btn btn-primary mb-4">Pay {{ $product->buyout_price }}
                            TK</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
