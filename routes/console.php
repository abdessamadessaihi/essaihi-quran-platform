<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// تذكيرات الورد — كل يوم الساعة 8 صباحاً
Schedule::command('notify:ward-reminders')->dailyAt('08:00');

// تذكيرات المراجعة — كل يوم الساعة 7 صباحاً
Schedule::command('notify:revision-reminders')->dailyAt('07:00');

// تعليم المراجعات المتأخرة — كل يوم منتصف الليل
Schedule::command('revisions:mark-overdue')->dailyAt('00:00');

// Exécute la commande de nettoyage tous les jours à minuit
Schedule::command('users:clean-unverified')->daily();