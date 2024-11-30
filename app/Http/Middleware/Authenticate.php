<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {

        // Si la requête s'attend à recevoir une réponse JSON
    if ($request->expectsJson()) {
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }

    // Si la requête ne s'attend pas à du JSON, vous pouvez choisir de retourner null ou une autre réponse
    return null;
       
        // return $request->expectsJson() ? null : route('login');
    }
}
