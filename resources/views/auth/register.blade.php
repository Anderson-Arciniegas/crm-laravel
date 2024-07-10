@extends("layouts.users-layout")
@section("title", "Register")
@section("content")
<div class="container">
    <section>
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center min-vh-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="{{asset('images/register.webp')}}" style="width:100%;" class="img-fluid rounded shadow" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <form method="POST" action="{{route("register")}}">
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
                            <h1 class="text-center fw-bold mx-3 mb-0">Register</h1>
                        </div>

                        <!-- Name input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="text" id="name" name="name" class="form-control form-control-lg" placeholder="Enter your name" />
                            <label class="form-label" for="form3Example3">Name</label>
                        </div>

                        <!-- Email input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Enter a valid email address" />
                            <label class="form-label" for="form3Example3">Email address</label>
                        </div>

                        <div data-mdb-input-init class="form-outline mb-4">
                            <select class="form-select" id="client_type" name="client_type" aria-label="Select the user type">
                                <option value="person" selected>Person</option>
                                <option value="business">Business</option>
                            </select>
                            <label class="form-label" for="form3Example3">Client type</label>
                        </div>

                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="date" class="form-control" id="birth_date" name="birth_date" placeholder="Select your birth date" required>

                            <label class="form-label" for="form3Example3">Birth date</label>
                        </div>

                        <!-- address input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="text" id="address" name="address" class="form-control form-control-lg" placeholder="Enter your address" />
                            <label class="form-label" for="form3Example3">Address</label>
                        </div>

                        <!-- Password input -->
                        <div data-mdb-input-init class="form-outline mb-3">
                            <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Enter password" />
                            <label class="form-label" for="form3Example4">Password</label>
                        </div>


                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">Register</button>
                            <p class="small fw-bold mt-2 pt-1 mb-0">Do you have an account? <a href="{{route('login')}}" class="link-danger">Login</a></p>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </section>
</div>
@endsection