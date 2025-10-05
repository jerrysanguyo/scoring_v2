<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MergeCms
{
    public function handle(Request $request, Closure $next, string $table, string $resource)
    {
        $request->merge([
            'cms_table' => $table,
            'resource'  => $resource,
        ]);

        return $next($request);
    }
}
