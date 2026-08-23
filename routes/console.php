<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('loyalty:process')->daily();
