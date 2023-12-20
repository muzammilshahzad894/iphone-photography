<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use Illuminate\Contracts\Queue\ShouldQueue;

class LessonWatchedListener implements ShouldQueue
{
    public function handle(LessonWatched $event)
    {
        // Logic to be executed when LessonWatched event is fired
    }
}
