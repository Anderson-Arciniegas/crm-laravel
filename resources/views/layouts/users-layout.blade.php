<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield("title", "CRM-Laravel")</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-secondary">
        <div>
            <a href="{{route('dashboard')}}" style="margin-right:20px;"><img style="width:50px;" src="{{asset('images/logo.png')}}" alt=""></a>
            <a href="{{route('dashboard')}}">
                <h1 class="navbar-brand">Kench</h1>
            </a>

            @if(Auth::check())
            <a href="{{route('projects')}}" style="margin-right:30px">
                <span class="navbar-brand">Projects</span>
            </a>


            <span style="margin-left:50vw;" class="card-title">{{ Auth::user()->name }}</span>

            @endif


        </div>
    </nav>


    @yield("content")
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>