@extends("layouts.users-layout")
@section("title", "Add member")
@section("content")
<div class="container">
    <section>
        <div class="container-fluid h-custom mt-5">

            <h2>Add project member</h2>
        </div>

        @if($project && $isAdmin)
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{$project->id }}# {{$project->name }}</h2>
                <h3 class="card-subtitle mb-2 text-muted">{{ $project->status }}</h3>
            </div>
        </div>

        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1 mb-4 mt-5">
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

            <form method="POST" action="{{route('projects.addUserToProjectTeam', $project->id)}}">
                @csrf
                @method('PUT')

                <!-- Email input -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Enter a valid email address" />
                    <label class="form-label" for="form3Example3">Email address</label>
                </div>

                <div data-mdb-input-init class="form-outline mb-4">
                    <select class="form-select" id="is_admin" name="is_admin" aria-label="Select the user type">
                        <option value="0" selected>Member</option>
                        <option value="1">Admin</option>
                    </select>
                    <label class="form-label" for="form3Example3">Role</label>
                </div>

                <div class="text-center text-lg-start mt-4 pt-2">
                    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">Add</button>
                </div>
            </form>
        </div>
        @endif
    </section>
</div>

@endsection