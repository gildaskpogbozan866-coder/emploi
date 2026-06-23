<?php

namespace App\Jobs;

use App\Models\Offre;
use App\Services\AlerteService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifierAlertesOffreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 120;

    public function __construct(public readonly Offre $offre) {}

    public function handle(AlerteService $service): void
    {
        $this->offre->loadMissing(['competences', 'type', 'metier']);
        $service->notifierImmediat($this->offre);
    }
}
