<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <title>Login</title>
</head>
<body>
    <div class="d-flex flex-row justify-content-center align-items-center m-5 p-0">
        <form class="card shadow p-0" style="width:24em;" id="login" method="POST" action="{{ route('auth.login') }}">
            @csrf
            <img src="{{ asset('Resources/logoh.png') }}" class="card-img-top p-4" alt="..." />
            <div class="card-body d-flex flex-column justify-content-center m-0 px-3">
                <div class="input-group my-2">
                    <label class="input-group-text px-2"><i class="fas fa-user"></i></label>
                    <input class="form-control" type="text" name="username" size="25" placeholder="Nombre de usuario..." />
                </div>

                <div class="input-group my-2">
                    <label class="input-group-text px-2"><i class="fas fa-key"></i></label>
                    <input class="form-control" type="password" name="password" size="25" placeholder="Contraseña..." />
                </div>
            </div>
            <div class="card-footer d-inline-flex flex-row justify-content-end gap-1 m-0 p-2">
                <button class="btn btn-sm btn-success" name="action" value="LOGIN"><i class="fas fa-sign-in-alt button login"></i> INGRESAR</button>
            </div>
        </form>
    </div>
</body>
</html>
