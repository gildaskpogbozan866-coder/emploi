<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('offres:expirer')->dailyAt('01:00');
Schedule::command('alertes:envoyer quotidien')->dailyAt('07:00');
Schedule::command('alertes:envoyer hebdomadaire')->weeklyOn(1, '08:00'); // lundi 8h
