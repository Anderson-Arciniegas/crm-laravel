@extends("layouts.users-layout")
@section("title", "Projects")
@section("content")
<div class="container">
    <section>
        <div class="container-fluid h-custom mt-5">

            <h2>Project </h2>
        </div>

        @if($project)
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{$project->id }}# {{$project->name }}</h2>
                <h3 class="card-subtitle mb-2 text-muted">{{ $project->status }}</h3>
                <div class="card-subtitle mb-2">{{ $project->description }}</div>
                <div class="card-subtitle mb-2">{{ $project->start_date }}</div>
                <div class="card-subtitle mb-2">{{ $project->end_date }}</div>
            </div>
        </div>
        @if($isAdmin)
        <div class="mt-5 p-0" style="width:100%;">

            <a style="width:100%;" class="btn btn-primary btn-lg" href="{{route('projects.team', $project->id)}}" role="button">Team</a>

            <a style="width:100%;" class="mt-3 btn btn-primary btn-lg" href="{{route('projects.edit', $project->id)}}" role="button">Edit</a>

            <form class="mt-3 p-0" method="POST" action="{{ route('projects.destroy', $project->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" style="width:100%;" class="btn btn-danger btn-lg">Delete</button>
            </form>
        </div>
        @endif
        @endif
    </section>
</div>

@endsection