<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="{{URL::asset('js/scripts.js')}}" async></script>
    <script src="https://www.w3schools.com/lib/w3.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container-fluid">
        @yield('content')
    </div>
    <footer class="footer bg-dark text-light">
        <nav class="navbar navbar-fixed-bottom d-flex justify-content-center">
            <div class="d-flex btn-group" style="overflow-x: auto;">
                <a class="link-light mx-3 text-center" href="{{route('tasks.list')}}">
                    <i class="fs-4 fas fa-list mb-1"></i><br />
                    <span>PLANILLAS</span>
                </a>
                <a class="link-light mx-3 text-center" href="{{route('schedules.list')}}">
                    <i class="fs-4 fa-solid fa-calendar-check mb-1"></i><br />
                    <span>PROGRAMAS</span>
                </a>
                <a class="link-light mx-3 text-center" href="{{route('licenses.list')}}">
                    <i class="fs-4 fa-solid fa-person-walking-arrow-right mb-1"></i><br />
                    <span>PERMISOS</span>
                </a>
                <a class="link-light mx-3 text-center" href="{{route('assignedtasks.list')}}">
                    <i class="fs-4 fa-solid fa-clipboard-list mb-1"></i><br />
                    <span>TAREAS</span>
                </a>
                <a class="link-light mx-3 text-center" href="{{route('meetings.list')}}">
                    <i class="fs-4 fas fa-comments mb-1"></i><br />
                    <span>REUNIONES</span>
                </a>
                <a class="link-light mx-3 text-center" href="{{route('handbooks.list')}}">
                    <i class="fs-4 fas fa-book mb-1"></i><br />
                    <span>MANUALES</span>
                </a>
                <a class="link-light mx-3 text-center" href="{{ url('/rosal') }}">
                    <i class="fs-4 fas fa-spa mb-1"></i><br />
                    <span>ROSAL</span>
                </a>
            </div>
        </nav>
    </footer>
</body>

</html>