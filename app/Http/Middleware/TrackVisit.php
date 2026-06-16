<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    private const LOCAL_IPS = ['127.0.0.1', '::1', 'localhost'];

    // Préfixes de routes à ignorer
    private const SKIP_PREFIXES = ['admin', 'api'];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

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

            [$country, $city] = $this->geolocate($ip);

            PageVisit::create([
                'session_id'  => substr(session()->getId(), 0, 64),
                'url'         => substr($request->fullUrl(), 0, 500),
                'page'        => $request->route()?->getName(),
                'device_type' => $this->detectDevice($ua),
                'browser'     => $this->detectBrowser($ua),
                'os'          => $this->detectOs($ua),
                'country'     => $country,
                'city'        => $city,
                'ip_hash'     => hash('sha256', $ip),
                'user_id'     => auth()->id(),
            ]);
        } catch (\Throwable) {
            // Ne jamais casser l'app à cause du tracking
        }
    }

    private function geolocate(string $ip): array
    {
        if (in_array($ip, self::LOCAL_IPS)) {
            return ['Local', 'Local'];
        }

        return Cache::remember("geo_ip_{$ip}", 86400, function () use ($ip) {
            try {
                $ctx  = stream_context_create(['http' => ['timeout' => 2]]);
                $json = @file_get_contents(
                    "http://ip-api.com/json/{$ip}?fields=status,country,city",
                    false,
                    $ctx
                );
                if ($json) {
                    $data = json_decode($json, true);
                    if (($data['status'] ?? '') === 'success') {
                        return [$data['country'] ?? null, $data['city'] ?? null];
                    }
                }
            } catch (\Throwable) {}
            return [null, null];
        });
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
