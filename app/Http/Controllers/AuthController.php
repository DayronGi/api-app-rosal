<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Device;

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

    public function check(Request $request)
    {
        // Obtener el 'ui' desde la solicitud
        $uid = $request->uid;

        // Verificar si el dispositivo ya existe
        $device = Device::where('uid', $uid)->first();

        if ($device) {
            // Si el dispositivo ya existe, verificar su estado
            if ($device->status == 2) {
                // Si el dispositivo está activo, devolver respuesta positiva
                return response()->json(['exists' => true, 'message' => 'Dispositivo ya registrado y activo.']);
            } else if ($device->status == 1) {
                // Si el dispositivo está inactivo, devolver respuesta negativa
                return response()->json(['exists' => false, 'message' => 'Dispositivo ya registrado pero inactivo.']);
            }
        } else {
            // Si el dispositivo no existe, registrarlo
            Device::create([
                'uid' => $uid,
                'pwd' => 1234, // Si no tienes un valor de contraseña inicial, usa null o ajusta según necesidad
                'user_id' => 1, // Ajusta según tu lógica de asignación de usuario
                'status' => 1, // Estado inicial del dispositivo (inactivo)
            ]);
    
            return response()->json(['exists' => false, 'message' => 'Dispositivo registrado exitosamente.']);
        }
    
        // Respuesta por defecto (en caso de que algo salga mal)
        return response()->json(['exists' => false, 'message' => 'Error al procesar la solicitud.']);

    }

}
