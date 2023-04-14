<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a href="{{ route('homepage') }}" class="navbar-brand">BidMart</a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav">
                    <a href="#" class="nav-item nav-link active">Home</a>
                    <a href="{{ route('back') }}" class="nav-item nav-link">Back</a>
                </div>
                @if (Request::route()->getName() == 'dashboard')
                    <form class="d-flex" action="{{ route('homepage') }}" method="GET">
                    @else
                        <form class="d-flex" action="" method="GET">
                @endif

                <div class="input-group">
                    @if (isset($search))
                        <input type="search" class="form-control" placeholder="Search" name="search"
                            value="{{ $search }}">
                    @else
                        <input type="search" class="form-control" placeholder="Search" name="search" value="">
                    @endif
                    <button type="submit" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-search" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg>
                    </button>
                </div>
                </form>
                <div class="navbar-nav dropdown">
                    @if ($data->user_type == '2')
                        <div class="navbar-nav">
                            <a href="{{ route('cart') }}" class="nav-item nav-link">Cart</a>
                        </div>
                    @elseif ($data->user_type == '1')
                        @php
                            $wallet = \App\Models\Wallet::where('id', '=', $data->id)->first();
                        @endphp

                        <div class="navbar-nav">
                            <a href="#" class="nav-item nav-link">Balance: {{ $wallet->balance }} TK</a>
                        </div>
                    @endif
                    <a href="#" class="nav-link dropdown-toggle"
                        data-bs-toggle="dropdown">{{ $data->username }}</a>
                    <div class="dropdown-menu">
                        <a href="{{ route('dashboard') }}" class="dropdown-item">Dashboard</a>
                        <a href="{{ route('logout') }}" class="dropdown-item">Logout</a>
                    </div>

                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <br>
        @yield('content')

    </div>




</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
</script>

</html>
