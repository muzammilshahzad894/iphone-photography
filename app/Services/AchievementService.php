<?php

namespace App\Services;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\User;

class AchievementService
{
    public static function unlockCommentAchievement(User $user, int $count)
    {
        switch ($count) {
            case 1:
                event(new AchievementUnlocked('First Comment Written', 'comment', $user));
                break;
            case 3:
                event(new AchievementUnlocked('3 Comments Written', 'comment', $user));
                break;
            case 5:
                event(new AchievementUnlocked('5 Comments Written', 'comment', $user));
                break;
            case 10:
                event(new AchievementUnlocked('10 Comments Written', 'comment', $user));
                break;
            case 20:
                event(new AchievementUnlocked('20 Comments Written', 'comment', $user));
                break;
        }
    }

    public static function unlockLessonAchievement(User $user, int $count)
    {
        switch ($count) {
            case 1:
                event(new AchievementUnlocked('First Lesson Watched', 'lesson', $user));
                break;
            case 5:
                event(new AchievementUnlocked('5 Lessons Watched', 'lesson', $user));
                break;
            case 10:
                event(new AchievementUnlocked('10 Lessons Watched', 'lesson', $user));
                break;
            case 25:
                event(new AchievementUnlocked('25 Lessons Watched', 'lesson', $user));
                break;
            case 50:
                event(new AchievementUnlocked('50 Lessons Watched', 'lesson', $user));
                break;
        }
    }

    public static function unlockBadge(User $user, int $count)
    {
        switch ($count) {
            case 0:
                event(new BadgeUnlocked('Beginner', $user));
                break;
            case 4:
                event(new BadgeUnlocked('Intermediate', $user));
                break;
            case 8:
                event(new BadgeUnlocked('Advanced', $user));
                break;
            case 10:
                event(new BadgeUnlocked('Master', $user));
                break;
        }
    }
}
