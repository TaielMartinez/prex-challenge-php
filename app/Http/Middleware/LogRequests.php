<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;

class LogRequests
{
    public function handle(Request $request, Closure $next)
    {
        // Configurar el logger para usar archivos diarios
        $logger = app('log')->channel('daily');

        // Registrar el log
        $logger->info('Request', [
            'method' => $request->method(),
            'url' => $request->url(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'request_body' => json_encode($request->all()) ?? '',
        ]);

        // Continuar con la solicitud
        return $next($request);
    }
}
