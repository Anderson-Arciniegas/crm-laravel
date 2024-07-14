@extends("layouts.users-layout")
@section("title", "Dashboard")
@section("content")
<div class="container">
    <section>
        <div class="container-fluid d-flex justify-content-center h-custom">
            <div class="col-md-8 col-lg-4 col-xl-8 offset-xl-1 mb-2">
                <form method="POST" action="{{route('tickets.create')}}">
                    @csrf
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

                    <div class="divider column d-flex align-items-center my-3">
                        <h1 class="text-center fw-bold mx-3 mb-0">Create Ticket</h1>
                    </div>
                    <div class="divider column d-flex align-items-center my-3">
                        <p class="text-left mx-3 mb-0">Tell us your problem</p>
                    </div>

                    <!-- Name input -->
                    <div data-mdb-input-init class="form-outline mb-2">
                        <input type="text" id="title" name="title" class="form-control form-control-lg" placeholder="Enter your name" />
                        <label class="form-label" for="title">Title</label>
                    </div>

                    <div data-mdb-input-init class="form-outline mb-2">
                        <select class="form-select" id="ticket_types" name="ticket_types" aria-label="Select the ticket type">
                            <option value="1" selected>General</option>
                        </select>
                        <label class="form-label" for="ticket_types">Ticket Types</label>
                    </div>

                    <!-- Email input -->
                    <div data-mdb-input-init class="form-outline mb-2">
                        <textarea id="description" name="description" class="form-control form-control-lg" placeholder="Enter a brief description" rows="5"></textarea>
                        <label class="form-label" for="description">Description</label>
                    </div>

                    <div class="text-center text-lg-start mt-2 pt-2">
                        <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">Create</button>
                    </div>

                </form>
            </div>
        </div>

    </section>
</div>
@endsection