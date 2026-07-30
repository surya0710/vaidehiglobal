<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectLegacyPublicPath
{
    /**
     * Redirect accidentally exposed Laravel public-directory URLs before
     * route resolution can hand them to the React catch-all route.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();

        if ($path === 'public' || str_starts_with($path, 'public/')) {
            $cleanPath = ltrim(substr($path, strlen('public')), '/');
            $destination = '/'.$cleanPath;

            if ($query = $request->getQueryString()) {
                $destination .= '?'.$query;
            }

            return redirect()->to($destination, 301);
        }

        return $next($request);
    }
}
