@extends("layouts.users-layout")
@section("title", "Profile")
@section("content")
<div class="container">
    <section>
        <div class="container-fluid h-custom mt-5">

            <h2>Update password</h2>
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
            <form method="POST" action="{{route('auth.login')}}">
                @csrf

                @if(session() -> has("success"))
                <div class="alert alert-success" role="alert">
                    {{session() -> get("success")}}
                </div>
                @endif

                @if(session() -> has("error"))
                <div class="alert alert-danger" role="alert">
                    {{session() -> get("error")}}
                </div>
                @endif

                <!-- Password input -->
                <div data-mdb-input-init class="form-outline mb-3">
                    <input type="password" id="old_password" name="old_password" class="form-control form-control-lg" placeholder="Enter old password" />
                    <label class="form-label" for="form3Example4">Old password</label>
                </div>

                <div data-mdb-input-init class="form-outline mb-3">
                    <input type="new-password" id="new_password" name="new_password" class="form-control form-control-lg" placeholder="Enter new password" />
                    <label class="form-label" for="form3Example4">New password</label>
                </div>


                <div class="text-center text-lg-start mt-4 pt-2">
                    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">Update password</button>
                </div>

            </form>
        </div>

    </section>
</div>

@endsection