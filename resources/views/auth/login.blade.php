@extends("layouts.users-layout")
@section("title", "Login")
@section("content")
<div class="container">
    <section>
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center min-vh-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="{{asset('images/login.jpg')}}" style="width:450px;" class="img-fluid rounded shadow" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
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

                        <div class="divider d-flex align-items-center my-4">
                            <h1 class="text-center fw-bold mx-3 mb-0">Login</h1>
                        </div>

                        <!-- Email input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Enter a valid email address" />
                            <label class="form-label" for="form3Example3">Email address</label>
                        </div>

                        <!-- Password input -->
                        <div data-mdb-input-init class="form-outline mb-3">
                            <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Enter password" />
                            <label class="form-label" for="form3Example4">Password</label>
                        </div>


                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
                            <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="{{route('auth.register')}}" class="link-danger">Register</a></p>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </section>
</div>

@endsection