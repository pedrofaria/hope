<?php
namespace Hope\Contracts;

use Hope\Http\Request;
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
    public function handle(Request $request, Closure $next)
}
