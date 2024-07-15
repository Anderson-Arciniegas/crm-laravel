@extends("layouts.users-layout")
@section("title", "Tasks")
@section("content")
<div class="container">
    <section>
        <div class="container-fluid h-custom mt-5">
            <h2>Create project tasks</h2>
        </div>

        @if($project && $isAdmin)
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{$project->id }}# {{$project->name }}</h2>
                <h3 class="card-subtitle mb-2 text-muted">{{ $project->status }}</h3>
            </div>
        </div>
        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1 mb-4 mt-5">
            <form action="{{route('tasks.create', $project->id)}}" method="POST">
                @csrf <!-- Token CSRF para proteger tu formulario -->
                <div class="form-group">
                    <label for="Title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required maxlength="255">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="due_date">Deadline</label>
                    <input type="date" class="form-control" id="due_date" name="due_date" required>
                </div>
                <div data-mdb-input-init class="form-outline mb-4">
                    <label for="due_date">Assigned user</label>
                    <select class="form-control" id="id_user" name="id_user" required>
                        @foreach($members as $member)
                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="text-center text-lg-start mt-4 pt-2">
                    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">Create tasks</button>
                </div>
            </form>
        </div>


        @endif
    </section>
</div>

@endsection