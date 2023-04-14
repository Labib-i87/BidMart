@extends('layout.navbar')
@section('title', 'Upload Product')
@section('content')
    <div class="container p-0">
        <div class="card px-4">
            <p class="h8 py-3">Upload Product</p>
            <div class="row gx-3">
                <form method="post" enctype="multipart/form-data" action="{{ route('product-upload') }}">
                    @if (Session::has('success'))
                        <div class="alert alert-success">{{ Session::get('success') }}</div>
                    @endif
                    @if (Session::has('fail'))
                        <div class="alert alert-danger">{{ Session::get('fail') }}</div>
                    @endif
                    @csrf
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <p class="text mb-1">Product Name</p>
                            <input class="form-control mb-3" type="text" name="name" placeholder="Product Name"
                                value="{{ old('name') }}">
                            <span class="text-danger">
                                @error('name')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <p class="text mb-1">Description</p>
                            <textarea class="form-control mb-3 pb-5" type="text" name="description" placeholder="Enter Product Description"
                                value="{{ old('description') }}"></textarea>
                            <span class="text-danger">
                                @error('description')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <p class="text mb-1">Starting Price</p>
                            <input class="form-control mb-3" type="text" name="starting_price"
                                placeholder="Enter Starting Price" value="{{ old('starting_price') }}">

                            <span class="text-danger">
                                @error('starting_price')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <p class="text mb-1">Buyout Price</p>
                            <input class="form-control mb-3" type="text" name="buyout_price"
                                placeholder="Enter Buyout Price" value="{{ old('buyout_price') }}">

                            <span class="text-danger">
                                @error('buyout_price')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <p class="text mb-1">Upload Image</p>
                            <input class="form-control mb-3 pt-2 " type="file" name="image" placeholder=""
                                value="">
                            <span class="text-danger">
                                @error('image')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mb-4">Upload</button>
                    <br>

                </form>
            </div>
        </div>
    </div>
@endsection
