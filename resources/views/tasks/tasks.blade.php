@extends("layouts.users-layout")
@section("title", "Tasks")
@section("content")
<div class="container">
    <section>
        <div class="container-fluid h-custom mt-5">
            <h2>Project tasks</h2>
        </div>

        @if($project)
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{$project->id }}# {{$project->name }}</h2>
                <h3 class="card-subtitle mb-2 text-muted">{{ $project->status }}</h3>
            </div>
        </div>

        @if(count($tasks) > 0)
        <table class="table mt-5 mb-5">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Description</th>
                    <th scope="col">Deadline</th>
                    <th scope="col">assigned user</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)

                <tr onclick="window.location.href='{{ route('tasks.details', ['id' => $project->id, 'id_task' => $task->id])}}';" style="cursor:pointer;">
                    <td>#{{ $task->id }}</td>
                    <td>{{ $task->title}}</td>
                    <td>{{ $task->status }}</td>
                    <td>{{ $task->description }}</td>
                    <td>{{ $task->deadline }}</td>
                    <td>{{ $task->id_user }}</td>
                </tr>

                @endforeach
            </tbody>
        </table>
        @endif


        <div class="mt-5 p-0" style="width:100%;">
            <a style="width:100%;" class=" btn btn-primary btn-lg" href="{{route('task.create', $project->id)}}" role="button">Create task</a>
        </div>
        @endif
    </section>
</div>

@endsection