<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Models\User;
use App\Models\LessonUser;
use App\Services\AchievementService;
use Illuminate\Contracts\Queue\ShouldQueue;

class LessonWatchedListener implements ShouldQueue
{
    public function handle(LessonWatched $event)
    {
        $event->user->watched()->syncWithoutDetaching([$event->lesson->id => ['watched' => true]]);

        $user = $event->user;
        $watchLessonCount = $user->watched()->count();
        $commentCount = $user->comments()->count();
        $achievementCount = $watchLessonCount + $commentCount;

        $lessonUnlockPoints = config('achievements.lessonUnlockNumbers');
        if (in_array($watchLessonCount, $lessonUnlockPoints)) {
            AchievementService::unlockLessonAchievement($user, $watchLessonCount);
        }

        $badgeUnlockPoints = config('achievements.badgeUnlockNumbers');
        if (in_array($achievementCount, $badgeUnlockPoints)) {
            AchievementService::unlockBadge($user, $achievementCount);
        }
    }
}
