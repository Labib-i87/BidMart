@extends('layout.navbar')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard</h1>
    <br>
    <div class="row">
        <div class="col mb-4">
            @if (Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
            @if (Session::has('fail'))
                <div class="alert alert-danger">{{ Session::get('fail') }}</div>
            @endif
            <div class="card bg-light mb-4" style="max-width: 25rem;">
                <div class="card-header">User Information</div>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    @if ($data->user_type == 1)
                        <div class="d-flex align-items-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-check-circle-fill text-success me-2" style="color: #FFC107; viewBox="0 0 16
                                16">
                                <path
                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                            </svg>
                            <span class="text-success" style="color: #FFC107;">Verified Seller</span>
                        </div>

                        <p class="card-text">Name: {{ $seller->full_name }}</p>
                    @endif
                    <p class="card-text">Username: {{ $data->username }}</p>
                    <p class="card-text">Email Address: {{ $data->email }}</p>

                </div>
            </div>
            @if ($data->user_type == 0)
                <div class="card bg-light mb-4" style="max-width: 25rem;">
                    <div class="card-header bg-warning">Become a verified seller</div>
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <a class="btn btn-outline-warning" href="verification" role="button">Verify</a>

                    </div>
                </div>
            @elseif ($data->user_type == 1)
                <div class="card bg-light mb-4" style="max-width: 25rem;">
                    <div class="card-header bg-info">Product Upload</div>
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <a class="btn btn-outline-info" href="product-entry" role="button">Upload Product</a>

                    </div>
                </div>
                <div class="card bg-light mb-4" style="max-width: 25rem;">
                    <div class="card-header bg-dark text-white">Product History</div>
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <a class="btn btn-outline-dark" href="product-history" role="button">View Products</a>

                    </div>
                </div>
            @elseif ($data->user_type == 2)
                <div class="card bg-light mb-4" style="max-width: 25rem;">
                    <div class="card-header bg-success text-white">Cart</div>
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <a class="btn btn-outline-success" href="cart" role="button">View Cart</a>

                    </div>
                </div>
                <div class="card bg-light mb-4" style="max-width: 25rem;">
                    <div class="card-header bg-dark text-white">Purchase History</div>
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <a class="btn btn-outline-dark" href="purchase-history" role="button">View History</a>

                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
