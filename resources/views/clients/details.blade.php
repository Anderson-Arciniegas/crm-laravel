@extends("layouts.admin-layout")
@section("title", "Client Details")
@section("content")
<div class="container">
    <section>
        <div class="container-fluid h-custom mt-5">

            <h2>Client Details</h2>
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
        @if($user)
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{ $user->name }}</h2>
                <h3 class="card-subtitle mb-2 text-muted">{{ $user->email }}</h3>
                <div class="card-subtitle mb-2">{{ $user->client_type }}</div>
                <div class="card-subtitle mb-2">{{ $user->address }}</div>
                <div class="card-subtitle mb-2">{{ $user->client_type }}</div>
                <div class="card-subtitle mb-2">{{ $user->phone }}</div>
                <div class="card-subtitle mb-2">{{ $user->birth_date }}</div>
            </div>
        </div>
        <div class="mt-5 p-0" style="width:100%;">
            <a style="width:100%;" class=" btn btn-primary btn-lg" href="{{route('edit', $user->id)}}" role="button">Edit</a>

            <form class="mt-3 p-0" method="POST" action="{{ route('users.delete', $user->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" style="width:100%;" class="btn btn-danger btn-lg">Delete</button>
            </form>
        </div>

        @endif
    </section>
</div>

@endsection