<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:fetch-articles-from-apis')->hourly();
