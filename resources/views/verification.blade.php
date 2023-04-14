@extends('layout.navbar')
@section('title', 'Verification')
@section('content')
    <div class="container p-0">
        <div class="card px-4">
            <p class="h8 py-3">Verify Yourself</p>
            <div class="row gx-3">
                <form action="{{ route('verify-user') }}" method="post">
                    @if (Session::has('success'))
                        <div class="alert alert-success">{{ Session::get('success') }}</div>
                    @endif
                    @if (Session::has('fail'))
                        <div class="alert alert-danger">{{ Session::get('fail') }}</div>
                    @endif
                    @csrf
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <p class="text mb-1">Enter Full Name</p>
                            <input class="form-control mb-3" type="text" name="name" placeholder="First Last"
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
                            <p class="text mb-1">NID</p>
                            <input class="form-control mb-3" type="text" name="nid" placeholder="123 456 7890"
                                value="{{ old('nid') }}">
                            <span class="text-danger">
                                @error('nid')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <p class="text mb-1">Date of Birth</p>
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
                            <p class="text mb-1">Phone No.</p>
                            <input class="form-control mb-3 pt-2 " type="text" name="number"
                                placeholder="+880 123 456 7890" value="{{ old('number') }}">
                            <span class="text-danger">
                                @error('number')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning mb-4">Verify</button>
                    <br>

                </form>
            </div>
        </div>
    </div>
@endsection
