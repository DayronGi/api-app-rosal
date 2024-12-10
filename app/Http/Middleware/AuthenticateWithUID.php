<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;

class AuthenticateWithUID
{
    /**
     * Manejar una solicitud entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $uid = trim($request->header('Authorization'));

        if (empty($uid)) {
            return response()->json(['error' => 'No token provided'], 401);
        }
    
        $device = Device::where('uid', $uid)->first();
    
        if (!$device) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
    
        if ($device->user_id) {
            Auth::loginUsingId($device->user_id);
        }
    
        return $next($request);
    }
}