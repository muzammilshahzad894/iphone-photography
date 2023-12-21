<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use App\Models\Badge;
use Illuminate\Contracts\Queue\ShouldQueue;

class BadgeUnlockedListener implements ShouldQueue
{
    public function handle(BadgeUnlocked $event)
    {
        $badge = $event->badgeName;
        $user = $event->user;

        /* check if badge is already unlocked for this user, do nothing */
        if ($user->badges()->where('badge_name', $badge)->exists()) {
            return;
        }

        Badge::create([
            'badge_name' => $badge,
            'user_id' => $user->id,
        ]);

    }
}