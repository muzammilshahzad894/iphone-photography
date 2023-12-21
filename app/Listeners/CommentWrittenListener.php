<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Models\User;
use App\Services\AchievementService;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentWrittenListener implements ShouldQueue
{
    public function handle(CommentWritten $event)
    {
        $event->user->comments()->save($event->comment);
        
        $user = $event->user;
        $watchLessonCount = $user->watched()->count();
        $commentCount = $user->comments()->count();
        $achievementCount = $watchLessonCount + $commentCount;

        $achievementUnlockPoints = config('achievements.achievementUnlockNumbers');
        if (in_array($commentCount, $achievementUnlockPoints)) {
            AchievementService::unlockCommentAchievement($user, $commentCount);
        }

        $badgeUnlockPoints = config('achievements.badgeUnlockNumbers');
        if (in_array($achievementCount, $badgeUnlockPoints)) {
            AchievementService::unlockBadge($user, $achievementCount);
        }
    }
}