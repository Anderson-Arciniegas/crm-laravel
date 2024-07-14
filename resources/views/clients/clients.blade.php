@extends("layouts.admin-layout")
@section("title", "Clients")
@section("content")
<div class="container">
    <section>
        <div class="container-fluid h-custom mt-5">

            <h2>Clients</h2>
        </div>
        <div class="row mb-4 mt-4">
            <div class="col">
                <form action="{{ route('users.getClientsByName') }}" method="GET">
                    <div class="form-group">
                        <input type="text" class="form-control" name="search" placeholder="Search by name" value="{{ request()->query('search') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
        <table class="table mt-5">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Client type</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)

                <tr onclick="window.location.href='{{ route('details', $user->id) }}';" style="cursor:pointer;">
                    <td>#{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->client_type }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->address }}</td>
                </tr>

                @endforeach
            </tbody>
        </table>

    </section>
</div>

@endsection