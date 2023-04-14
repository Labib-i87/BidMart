@extends('layout.navbar')
@section('title', 'Product History')
@section('content')
    <h2>Product History</h2>
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
                                    <th>Selling Price</th>
                                    <th colspan="3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr onclick="window.location.href='{{ url('view-product/' . $product->pid) }}';"
                                        style="background-color: #fff;" onmouseover="this.style.backgroundColor='#f0f0f0';"
                                        onmouseout="this.style.backgroundColor='#fff';">
                                        <td><img src="{{ asset('uploads/' . $product->image_path) }}" width=150px
                                                height=150px alt="Product Image" class="img-fluid"></td>
                                        <td>{{ $product->product_name }}</td>
                                        <td>{{ $product->status }}</td>
                                        <td>{{ $product->start_price }}</td>
                                        <td>{{ $product->current_price }}</td>
                                        @if ($product->status != 'sold')
                                            <td><a href="{{ url('edit-product/' . $product->pid) }}"
                                                    class="btn btn-primary btn-sm" style="width: 75px;">Edit</a></td>
                                            <td><a href="{{ url('delete-product/' . $product->pid) }}"
                                                    class="btn btn-danger btn-sm" style="width: 75px;">Delete</a></td>

                                            <td><a href="{{ url('view-product/' . $product->pid) }}"
                                                    class="btn btn-success btn-sm" style="width: 75px;">Sell</a></td>
                                        @else
                                            <td><a href="{{ url('edit-product/' . $product->pid) }}"
                                                    class="btn btn-primary btn-sm invisible">Edit</a></td>
                                            <td><a href="{{ url('delete-product/' . $product->pid) }}"
                                                    class="btn btn-danger btn-sm invisible">Delete</a></td>
                                            <td><a href="{{ url('view-product/' . $product->pid) }}"
                                                    class="btn btn-primary btn-sm invisible" style="width: 75px;">View</a>
                                            </td>
                                        @endif

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
