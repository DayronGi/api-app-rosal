<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Device;

class AuthController extends Controller
{
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
                $user = User::where('user_id', $device->user_id)->with('role')->first();
                return response()->json(['exists' => true, 'user' => $user]);
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