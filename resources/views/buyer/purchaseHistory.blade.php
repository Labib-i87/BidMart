@extends('layout.navbar')
@section('title', 'Purchase History')
@section('content')
    <h1>Purchase History</h1>
    <br>
    @if (Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    @if (Session::has('fail'))
        <div class="alert alert-danger">{{ Session::get('fail') }}</div>
    @endif
    @if ($products->count() > 0)
        <div class="container p-0">
            <div class="card px-4">

                <div class="row gx-3">

                    @csrf
                    <div class="container mt-4">


                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Starting Price</th>
                                    <th>Purchase Price</th>


                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr onclick="window.location.href='{{ url('view-product/' . $product->pid) }}';"
                                        style="background-color: #fff;" onmouseover="this.style.backgroundColor='#f0f0f0';"
                                        onmouseout="this.style.backgroundColor='#fff';">>
                                        <td><img src="{{ asset('uploads/' . $product->image_path) }}" width=150px
                                                height=150px alt="Product Image" class="img-fluid"></td>
                                        <td>{{ $product->product_name }}</td>
                                        <td>bought</td>
                                        <td>{{ $product->start_price }}</td>
                                        <td>{{ $product->current_price }}</td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    @endif
@endsection
