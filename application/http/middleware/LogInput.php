<?php

namespace app\http\middleware;

class LogInput
{
    public function handle($request, \Closure $next)
    {
        $url = $request->url(true);
        $method = $request->method();
        $input = file_get_contents('php://input');
        $text = <<<T
----input----
{$method}::
{$url}::
{$input}
----input----
T;
        \Log::write($text);
    
        return $next($request);
    }
}
