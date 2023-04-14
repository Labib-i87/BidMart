@extends('layout.navbar')
@section('title', 'Edit Product')
@section('content')
    <div class="container p-0">
        <div class="card px-4">
            <p class="h8 py-3">Edit Product</p>
            <div class="row gx-3">
                <form method="post" enctype="multipart/form-data" action="{{ url('update-product/' . $product->pid) }}">
                    @if (Session::has('success'))
                        <div class="alert alert-success">{{ Session::get('success') }}</div>
                    @endif
                    @if (Session::has('fail'))
                        <div class="alert alert-danger">{{ Session::get('fail') }}</div>
                    @endif
                    @csrf
                    @method('put')
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <p class="text mb-1">Product Name</p>
                            <input class="form-control mb-3" type="text" name="name" placeholder="Product Name"
                                value="{{ $product->product_name }}">
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
                                value="">{{ $product->description }}</textarea>
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
                            <input class="form-control mb-3" type="text" name="price"
                                placeholder="Enter Starting Price" value="{{ $product->start_price }}">

                            <span class="text-danger">
                                @error('price')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>


                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <p class="text mb-3">Change Status</p>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="option" id="option1"
                                            value="offline" {{ $product->status == 'offline' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="option1">offline</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="option" id="option2"
                                            value="online" {{ $product->status == 'online' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="option2">online</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="option" id="option3"
                                            value="bidding" {{ $product->status == 'bidding' ? 'checked' : '' }} disabled>
                                        <label class="form-check-label" for="option3">bidding</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="option" id="option4"
                                            value="sold" {{ $product->status == 'carted' ? 'checked' : '' }} disabled>
                                        <label class="form-check-label" for="option4">carted</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="option" id="option4"
                                            value="sold" {{ $product->status == 'sold' ? 'checked' : '' }} disabled>
                                        <label class="form-check-label" for="option4">sold</label>
                                    </div>
                                </div>
                            </div>


                            <span class="text-danger">
                                @error('status')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="d-flex flex-column mb-3">
                            <p class="text mb-1">Edit Image</p>
                            <input class="form-control mb-3 pt-2 " type="file" name="image" placeholder=""
                                value="">
                            <img src="{{ asset('uploads/' . $product->image_path) }}" width=150px height=150px
                                alt="Product Image" class="img-fluid">
                            <span class="text-danger">
                                @error('image')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mb-4">Update</button>
                    <br>

                </form>
            </div>
        </div>
    </div>
@endsection
