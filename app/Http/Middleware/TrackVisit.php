<?php

namespace App\Http\Middleware;

use App\Jobs\TrackPageVisitJob;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    // Préfixes de routes à ignorer
    private const SKIP_PREFIXES = ['admin', 'api'];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Dispatché en file d'attente : la géolocalisation fait un appel HTTP
        // externe (ip-api.com) qui ne doit jamais bloquer la réponse au visiteur.
        if ($this->shouldTrack($request)) {
            $this->track($request);
        }

        return $response;
    }

    private function shouldTrack(Request $request): bool
    {
        if (!$request->isMethod('GET') || $request->ajax()) return false;

        $first = $request->segment(1);
        if (in_array($first, self::SKIP_PREFIXES)) return false;

        // Ignorer les bots courants
        $ua = strtolower($request->userAgent() ?? '');
        if (preg_match('/bot|crawler|spider|slurp|curl|wget|python|scrapy|phpunit/i', $ua)) return false;

        return true;
    }

    private function track(Request $request): void
    {
        try {
            $ip = $request->ip();
            $ua = $request->userAgent() ?? '';

            TrackPageVisitJob::dispatch(
                ip: $ip,
                userAgent: $ua,
                url: substr($request->fullUrl(), 0, 500),
                page: $request->route()?->getName(),
                deviceType: $this->detectDevice($ua),
                browser: $this->detectBrowser($ua),
                os: $this->detectOs($ua),
                sessionId: substr(session()->getId(), 0, 64),
                userId: auth()->id(),
            )->afterResponse();
        } catch (\Throwable) {
            // Ne jamais casser l'app à cause du tracking
        }
    }

    private function detectDevice(string $ua): string
    {
        if (preg_match('/ipad|tablet|playbook|silk|(android(?!.*mobile))/i', $ua)) return 'tablette';
        if (preg_match('/mobile|android|iphone|ipod|blackberry|opera mini|iemobile|windows phone/i', $ua)) return 'mobile';
        return 'ordinateur';
    }

    private function detectBrowser(string $ua): string
    {
        if (str_contains($ua, 'Edg'))    return 'Edge';
        if (str_contains($ua, 'OPR') || str_contains($ua, 'Opera')) return 'Opera';
        if (str_contains($ua, 'Chrome')) return 'Chrome';
        if (str_contains($ua, 'Firefox'))return 'Firefox';
        if (str_contains($ua, 'Safari')) return 'Safari';
        return 'Autre';
    }

    private function detectOs(string $ua): string
    {
        if (preg_match('/windows/i',        $ua)) return 'Windows';
        if (preg_match('/macintosh|mac os/i',$ua)) return 'macOS';
        if (preg_match('/android/i',        $ua)) return 'Android';
        if (preg_match('/iphone|ipad|ipod/i',$ua)) return 'iOS';
        if (preg_match('/linux/i',          $ua)) return 'Linux';
        return 'Autre';
    }
}
