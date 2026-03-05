<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Fundraiser;
use App\Models\FundraiserVisit;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TrackReferral
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref')) {
            $refCode = $request->query('ref');
            
            // Only set if fundraiser is approved
            $fundraiser = Fundraiser::where('referral_code', $refCode)->where('status', 'approved')->first();
            
            if ($fundraiser) {
                // Prevent self-referral if logged in
                if (!Auth::check() || Auth::id() !== $fundraiser->user_id) {
                    session(['referral_fundraiser_id' => $fundraiser->id]);
                    
                    // Record the visit (Unique per IP per 24 hours)
                    $ip = $request->ip();
                    $recentVisit = FundraiserVisit::where('fundraiser_id', $fundraiser->id)
                        ->where('ip_address', $ip)
                        ->where('created_at', '>=', Carbon::now()->subHours(24))
                        ->exists();

                    if (!$recentVisit) {
                        FundraiserVisit::create([
                            'fundraiser_id' => $fundraiser->id,
                            'ip_address' => $ip,
                            'user_agent' => $request->userAgent(),
                            'url_visited' => $request->fullUrl(),
                        ]);
                    }
                }
            }
        }

        return $next($request);
    }
}
