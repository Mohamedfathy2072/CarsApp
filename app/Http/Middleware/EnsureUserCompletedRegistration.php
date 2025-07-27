<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCompletedRegistration
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();

        if (!$user || !$user->completed_registration) {
            return response()->json([
                'status' => false,
                'message' => 'You must complete your registration first.'
            ], 403);
        }

        return $next($request);
    }
}
