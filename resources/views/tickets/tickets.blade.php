@extends("layouts.admin-layout")
@section("title", "Dashboard")
@section("content")
<div class="container">
    <section>
        <div class="container-fluid d-flex justify-content-center h-custom">
        <section class="text-center text-md-start col-md-8 col-lg-4 col-xl-8 offset-xl-1">
                <div class="col-md-8 my-4 text-left">
                    <h1> My Tickets</h1>
                </div>
                <!-- Post -->
                @if(count($tickets) > 0)
                    <ul class="list-group">
                        @foreach ($tickets as $ticket)
                            <li class="d-flex justify-content-around align-items-center border border-primary mb-3" style="cursor: pointer; flex-direction: column; border-radius: 8px;">
                                <div class="row w-100 d-flex align-items-center justify-content-between mb-2 border-bottom" style="height: 55px;">
                                    <div class="d-flex bold align-items-center" style="width: 15%;">
                                        #{{$ticket->id}}
                                    </div>
                                    <div class="d-flex bold align-items-center" style="width: 50%;">
                                        {{$ticket->title}}
                                    </div>
                                    <div class="d-flex bold align-items-center" style="width: 20%;">
                                        {{$ticket->status}}
                                    </div>
                                </div>
                                <div class="w-100 d-flex align-items-start justify-content-between mb-2 border-bottom" style="min-height: 100px; padding: 0px 8px;">
                                    <p>{{$ticket->description}}</p>
                                </div>
                                @if($ticket->status !== 'Completed')
                                    <div class="w-100 row d-flex align-items-center justify-content-center mb-2" style="padding: 0px 8px; flex-direction: row;">
                                        <form class="p-0" style="width:120px;"   method="POST" action="{{ route('tickets.manage', ['id' => $ticket->id, 'status' => 'Completed']) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" style="width:100%;" class="btn btn-primary btn-lg">Approve</button>
                                        </form>
                                        <form class="p-0 ml-1" style="width:120px;"   method="POST" action="{{ route('tickets.manage', ['id' => $ticket->id, 'status' => 'Deleted']) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" style="width:100%;" class="btn btn-danger btn-lg">Deny</button>
                                        </form>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <h1>There are no tickets</h1>
                @endif
            </section>
        </div>

    </section>
</div>
@endsection