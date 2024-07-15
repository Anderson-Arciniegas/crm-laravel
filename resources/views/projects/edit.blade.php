@extends("layouts.users-layout")
@section("title", "Projects")
@section("content")
@php
use Carbon\Carbon;
@endphp


<div class="container">
    <section>
        <div class="container-fluid h-custom mt-5">

            <h2>Edit project</h2>
        </div>

        @if($project)
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{$project->id }}# {{$project->name }}</h2>
                <h3 class="card-subtitle mb-2 text-muted">{{ $project->status }}</h3>
            </div>
        </div>
        @if($isAdmin)
        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1 mb-4 mt-5">
            <form action="{{route('projects.update', $project->id)}}" method="POST">
                @csrf <!-- Token CSRF para proteger tu formulario -->
                @method('PUT')
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required maxlength="255" value="{{ $project->name }}">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" required>{{ $project->description }}</textarea>
                </div>
                <div class="form-group">
                    <label for="start_date">Start date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required value="{{ Carbon::parse($project->start_date)->format('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label for="end_date">End date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" required value="{{ Carbon::parse($project->end_date)->format('Y-m-d') }}">
                </div>
                <div class="text-center text-lg-start mt-4 pt-2">
                    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">Save</button>
                </div>
            </form>
        </div>
        @endif
        @endif
    </section>
</div>

@endsection