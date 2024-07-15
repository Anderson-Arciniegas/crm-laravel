@extends("layouts.users-layout")
@section("title", "Project Team")
@section("content")
<div class="container">
    <section>
        <div class="container-fluid h-custom mt-5">

            <h2>Project </h2>
        </div>

        @if($project && $isAdmin)
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{$project->id }}# {{$project->name }}</h2>
                <h3 class="card-subtitle mb-2 text-muted">{{ $project->status }}</h3>
            </div>
        </div>
        @if(count($members) > 0)
        <table class="table mt-5">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Address</th>
                    <th scope="col">Delete</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($members as $user)

                <tr>
                    <td>#{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->address }}</td>
                    <td>
                        <form class="p-0" method="POST" action="{{ route('projects.deleteUserProjectTeam', ['projectId' => $project->id, 'userId' => $user->id]) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" style="width:100%;" class="btn btn-danger btn-lg">Delete</button>
                        </form>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
        @endif
        <a style="width:100%;" class="btn btn-primary btn-lg" href="{{route('projects.member', $project->id)}}" role="button">Add Member</a>
        @endif
    </section>
</div>

@endsection