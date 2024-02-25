<?php

namespace App\Http\Middleware;

use App\Traits\ResponseMessage;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExceptionHandlerMiddleware
{

    use ResponseMessage;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try
        {
            return $next($request);
        }
        catch(Exception $error)
        {
            $this->SendMessage("Something went wrong.", 500);
        }
    }
}
