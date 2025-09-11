<?php

use App\Domains\Bill\Jobs\NotificationBillJob;
use Illuminate\Support\Facades\Schedule;



Schedule::command('auth:clear-resets')->dailyAt('03:00');
Schedule::command('sanctum:prune-expired --hours=24')->dailyAt('04:00');

Schedule::job(NotificationBillJob::class)->dailyAt('09:00');
