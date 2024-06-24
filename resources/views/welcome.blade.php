@extends("layouts.users-layout")
@section("title", "Home")
@section("content")
<div class="container">
    <section>
        <div class="row d-flex justify-content-center align-items-start min-vh-100">
            <!-- <div class="col-md-5">
                <img style="width:450px;" src="{{asset('images/portada.jpg')}}" class="img-fluid rounded shadow" alt="Sample image">
            </div> -->
            <div class="col-md-7 mt-5">
                <div class="jumbotron">
                    <h2 class="display-5">Bienvenido a Kench</h2>
                    <p class="lead">Join our community and share your thoughts with the world.</p>
                    <hr class="my-4">
                    <p>Click the button below to login and start exploring.</p>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection