<?php

namespace App\Console\Commands;

use App\Models\Publicite;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ExpirerPublicites extends Command
{
    protected $signature   = 'publicites:expirer';
    protected $description = 'Supprime les publicités approuvées dont la date de fin est dépassée.';

    public function handle(): int
    {
        $expirées = Publicite::where('statut', 'approuve')
            ->whereNotNull('date_fin')
            ->whereDate('date_fin', '<', Carbon::today())
            ->get();

        foreach ($expirées as $pub) {
            Storage::disk('public')->delete($pub->image);
            $pub->delete();
        }

        $nb = $expirées->count();
        $this->info("{$nb} publicité(s) expirée(s) et supprimée(s).");

        return self::SUCCESS;
    }
}
