<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;

class AchievementUnlockedListener implements ShouldQueue
{
    public function handle(AchievementUnlocked $event)
    {
        $achievement = $event->achievementName;
        $achievementType = $event->achievementType;
        $user = $event->user;

        /* check if achievement is already unlocked for this user, do nothing */
        if ($user->achievements()->where('achievement_name', $achievement)->exists()) {
            return;
        }

        Achievement::create([
            'achievement_name' => $achievement,
            'achievement_type' => $achievementType,
            'user_id' => $user->id,
        ]);
    }
}