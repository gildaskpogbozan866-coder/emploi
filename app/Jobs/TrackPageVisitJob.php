<?php

namespace App\Jobs;

use App\Models\PageVisit;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;

/**
 * Dispatché uniquement via ::dispatch(...)->afterResponse() (voir TrackVisit
 * middleware) — n'implémente volontairement pas ShouldQueue : la prod n'a pas
 * de worker de queue actif, afterResponse() exécute le job en synchrone juste
 * après l'envoi de la réponse au visiteur, sans passer par une vraie queue.
 */
class TrackPageVisitJob
{
    use Dispatchable;

    private const LOCAL_IPS = ['127.0.0.1', '::1', 'localhost'];

    public function __construct(
        public readonly string $ip,
        public readonly string $userAgent,
        public readonly string $url,
        public readonly ?string $page,
        public readonly string $deviceType,
        public readonly string $browser,
        public readonly string $os,
        public readonly string $sessionId,
        public readonly ?int $userId,
    ) {}

    public function handle(): void
    {
        [$country, $city] = $this->geolocate($this->ip);

        PageVisit::create([
            'session_id'  => $this->sessionId,
            'url'         => $this->url,
            'page'        => $this->page,
            'device_type' => $this->deviceType,
            'browser'     => $this->browser,
            'os'          => $this->os,
            'country'     => $country,
            'city'        => $city,
            'ip_hash'     => hash('sha256', $this->ip),
            'user_id'     => $this->userId,
        ]);
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
}
