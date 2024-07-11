@extends("layouts.users-layout")
@section("title", "Admin Dashboard")
@section("content")
<div class="container">
    <section>
        <div class="container-fluid h-custom mt-5">

            <h2>Admin Dashboard</h2>
        </div>

        @if(Auth::check())
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{ Auth::user()->name }}</h5>
                    <h3 class="card-subtitle mb-2 text-muted">{{ Auth::user()->email }}</h6>

            </div>
        </div>
        @endif
        <div class="mt-5 p-0" style="width:100%;">
            <a style="width:100%;" class=" btn btn-primary btn-lg" href="{{route('profile.change-password')}}" role="button">Change password</a>

            <form class="mt-3 p-0" method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button type="submit" style="width:100%;" class="btn btn-danger btn-lg">Logout</button>
            </form>
        </div>

    </section>
</div>

@endsection