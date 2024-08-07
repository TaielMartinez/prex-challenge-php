<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;
use League\OAuth2\Server\ResourceServer;
use Laravel\Passport\TokenRepository;

class HandleApiAuthentication
{
    private $server;
    private $repository;

    public function __construct(ResourceServer $server, TokenRepository $repository)
    {
        $this->server = $server;
        $this->repository = $repository;
    }

    public function handle($request, Closure $next)
    {
        try {
            // Verifica las credenciales del cliente utilizando Passport
            (new CheckClientCredentials($this->server, $this->repository))->handle($request, function ($request) use ($next) {
                return $next($request);
            });
        } catch (AuthenticationException $e) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }
}
