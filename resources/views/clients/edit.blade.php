@extends("layouts.admin-layout")
@section("title", "Edit Client")
@section("content")
<div class="container">
    <section>
        <div class="container-fluid h-custom mt-5">

            <h2>Edit Client</h2>
        </div>
        @if($user)
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{ $user->name }}</h2>
                <h3 class="card-subtitle mb-2 text-muted">{{ $user->email }}</h3>
            </div>
        </div>
        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1 mb-4 mt-5">
            <form method="POST" action="{{route('users.edit', $user->id)}}">
                @csrf
                @method('PUT')

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

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Name input -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" id="name" name="name" class="form-control form-control-lg" placeholder="Enter your name" value="{{ $user->name }}" />
                    <label class="form-label" for="form3Example3">Name</label>
                </div>

                <!-- Email input -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Enter a valid email address" value="{{ $user->email }}" />
                    <label class="form-label" for="form3Example3">Email address</label>
                </div>

                <!-- Phone input -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="tel" id="phone" name="phone" class="form-control form-control-lg" placeholder="Enter your phone number" pattern="04[0-9]{2}[0-9]{3}[0-9]{4}" required value="{{ $user->phone }}" />
                    <label class="form-label" for="phone">Phone number</label>
                </div>

                <!-- Client type select -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <select class="form-select" id="client_type" name="client_type" aria-label="Select the user type">
                        <option value="person" {{ $user->client_type == 'person' ? 'selected' : '' }}>Person</option>
                        <option value="business" {{ $user->client_type == 'business' ? 'selected' : '' }}>Business</option>
                    </select>
                    <label class="form-label" for="form3Example3">Client type</label>
                </div>

                <!-- Address input -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" id="address" name="address" class="form-control form-control-lg" placeholder="Enter your address" value="{{ $user->address }}" />
                    <label class="form-label" for="form3Example3">Address</label>
                </div>

                <div class="text-center text-lg-start mt-4 pt-2">

                    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">Save</button>
                </div>

            </form>

            @endif
    </section>
</div>

@endsection