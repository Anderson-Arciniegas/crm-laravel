@extends("layouts.users-layout")
@section("title", "Task")
@section("content")
<div class="container">
    <section>
        <div class="container-fluid h-custom mt-5">
            <h2>Details task</h2>
        </div>

        @if($project && $task)
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{$project->id }}# {{$project->name }}</h2>
                <h3 class="card-subtitle mb-2 text-muted">{{ $project->status }}</h3>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h2 class="card-title">{{$task->id }}# {{$task->title }}</h2>
                <h3 class="card-subtitle mb-2 text-muted">{{ $task->status }}</h3>
                <div class="card-subtitle mb-2">{{ $task->description }}</div>
                <div class="card-subtitle mb-2">{{ $task->deadline }}</div>
                <div class="card-subtitle mb-2">{{ $task->id_user }}</div>
            </div>
        </div>
        @if($assigned && !$isAdmin && $task->status != 'Completed')
        <div class="mt-5 p-0" style="width:100%;">
            <form class="mt-3 p-0" method="POST" action="{{ route('tasks.complete', $task->id) }}">
                @csrf
                @method('PUT')
                <button type="submit" style="width:100%;" class="btn btn-success btn-lg">Complete</button>
            </form>
        </div>
        @endif
        @if($isAdmin && $task->status != 'Completed')
        <div class="mt-3 p-0" style="width:100%;">
            <form class="mt-3 p-0" method="POST" action="{{ route('tasks.complete', ['id' => $project->id, 'id_task' => $task->id]) }}">
                @csrf
                @method('PUT')
                <button type="submit" style="width:100%;" class="btn btn-success btn-lg">Complete</button>
            </form>

            <form class="mt-3 p-0" method="POST" action="{{ route('tasks.destroy', ['id' => $project->id, 'id_task' => $task->id]) }}">
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