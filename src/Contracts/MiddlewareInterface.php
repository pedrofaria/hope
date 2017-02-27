<?php
namespace Hope\Contracts;

use \Closure;

interface MiddlewareInterface
{
    /**
     * Handle an incoming request.
     *
     * @param  \Hope\Http\Request  $request
     * @param  \Closure  $next
     * @return \Hope\Http\Response
     */
    public function handle($request, Closure $next)
}
