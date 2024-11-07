<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        Log::info('Intentando iniciar sesión con:', ['username' => $credentials['username']]);

        $user = User::where('username', $credentials['username'])->first();

        if ($user) {
            Log::info('Usuario encontrado:', ['username' => $user->username]);

            if (sha1($credentials['password']) === $user->password) {
                Log::info('Contraseña verificada para el usuario:', ['username' => $user->username]);

                Auth::login($user);

                Log::info('Inicio de sesión exitoso para el usuario:', ['username' => $user->username]);
                Log::info('Id del usuario:', ['id' => $user->user_id]);
                return redirect()->route('tasks.list');

            } else {
                Log::warning('Contraseña incorrecta para el usuario:', ['username' => $user->username]);
                return response(['message' => 'Credenciales inválidas'], Response::HTTP_UNAUTHORIZED);
            }
        } else {
            Log::warning('Usuario no encontrado:', ['username' => $credentials['username']]);
            return response(['message' => 'Credenciales inválidas'], Response::HTTP_UNAUTHORIZED);
        }
    }
}
