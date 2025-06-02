<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Admin;
use App\Models\Profile;

class EnsureOwnsProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Admin|null $user */
        $user = $request->user();

        if (!$user instanceof Admin) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        // Récupérer le profil depuis la route
        $profile = $request->route('profile');

        if ($profile instanceof Profile && $profile->admin_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized. You can only access your own profiles.'
            ], 403);
        }

        return $next($request);
    }
}
