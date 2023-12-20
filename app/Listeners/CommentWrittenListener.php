<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentWrittenListener implements ShouldQueue
{
    public function handle(CommentWritten $event)
    {
        // Logic to be executed when CommentWritten event is fired
    }
}