@extends("layouts.users-layout")
@section("title", "Projects")
@section("content")
<div class="container">
    <section>
        <div class="container-fluid h-custom mt-5">

            <h2>Create project</h2>
        </div>

        @if(Auth::check())
        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1 mb-4 mt-5">
            <form action="{{route('projects.create')}}" method="POST">
                @csrf <!-- Token CSRF para proteger tu formulario -->
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required maxlength="255">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="start_date">Start date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label for="end_date">End date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                </div>
                <div class="text-center text-lg-start mt-4 pt-2">
                    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">Create project</button>
                </div>
            </form>
        </div>
        @endif
    </section>
</div>

@endsection