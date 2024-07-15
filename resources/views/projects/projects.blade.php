@extends("layouts.users-layout")
@section("title", "Projects")
@section("content")
<div class="container">
    <section>
        <div class="container-fluid h-custom mt-5">

            <h2>Projects</h2>
        </div>

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

        @if(Auth::check())
        <div class="row mb-4 mt-4">
            <div class="col">
                <form action="{{ route('projects.search') }}" method="GET">
                    <div class="form-group">
                        <input type="text" class="form-control" name="search" placeholder="Search" value="{{ request()->query('search') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>

        @if(count($projects) > 0)
        <table class="table mt-5 mb-5">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Description</th>
                    <th scope="col">Start date</th>
                    <th scope="col">End date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)

                <tr onclick="window.location.href='{{ route('projects.details', $project->id)}}';" style="cursor:pointer;">
                    <td>#{{ $project->id }}</td>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->status }}</td>
                    <td>{{ $project->description }}</td>
                    <td>{{ $project->start_date }}</td>
                    <td>{{ $project->end_date }}</td>
                </tr>

                @endforeach
            </tbody>
        </table>
        @endif
        <div class="mt-5 p-0" style="width:100%;">
            <a style="width:100%;" class=" btn btn-primary btn-lg" href="{{route('createProject')}}" role="button">Create project</a>
        </div>
        @endif
    </section>
</div>

@endsection