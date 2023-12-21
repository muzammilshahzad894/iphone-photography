<?php

namespace App\Repositories;

use App\Interfaces\AchievementRepositoryInterface;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;

class AchievementRepository implements AchievementRepositoryInterface
{
    public function getUnlockedAchievements(string|int $userId): array
    {
        return Achievement::where('user_id', $userId)->pluck('achievement_name')->toArray();
    }

    public function getNextAvailableAchievements(string|int $userId): array
    {
        $lastLessonAchievementName = $this->getLastAchievementName($userId, 'lesson');
        $lastCommentAchievementName = $this->getLastAchievementName($userId, 'comment');

        $nextLessonAchievement = $this->getNextLessonAchievement($lastLessonAchievementName);
        $nextCommentAchievement = $this->getNextCommentAchievement($lastCommentAchievementName);

        return [$nextLessonAchievement, $nextCommentAchievement];
    }

    public function getLastAchievementName(string|int $userId, string $achievementType): string|null
    {
        return Achievement::where('user_id', $userId)
            ->where('achievement_type', $achievementType)
            ->orderBy('created_at', 'desc')
            ->pluck('achievement_name')
            ->first();
    }

    public function getNextLessonAchievement(string|null $lastAchievementName): string
    {
        switch ($lastAchievementName) {
            case 'First Lesson Watched':
                return '5 Lessons Watched';
            case '5 Lessons Watched':
                return '10 Lessons Watched';
            case '10 Lessons Watched':
                return '25 Lessons Watched';
            case '25 Lessons Watched':
                return '50 Lessons Watched';
            case '50 Lessons Watched':
                return 'N/A';
            default:
                return 'First Lesson Watched';
        }
    }

    public function getNextCommentAchievement(string|null $lastAchievementName): string
    {
        switch ($lastAchievementName) {
            case 'First Comment Written':
                return '3 Comments Written';
            case '3 Comments Written':
                return '5 Comments Written';
            case '5 Comments Written':
                return '10 Comments Written';
            case '10 Comments Written':
                return '20 Comments Written';
            case '20 Comments Written':
                return 'N/A';
            default:
                return 'First Comment Written';
        }
    }

    public function getCurrentBadge(string|int $userId): string
    {
        $currentBadge = Badge::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->pluck('badge_name')
            ->first();

        return $currentBadge ?? 'Beginner';
    }

    public function getNextBadge(string|int $userId): string
    {
        $currentBadge = $this->getCurrentBadge($userId);

        switch ($currentBadge) {
            case 'Beginner':
                return 'Intermediate';
            case 'Intermediate':
                return 'Advanced';
            case 'Advanced':
                return 'Master';
            case 'Master':
                return 'N/A';
            default:
                return 'Beginner';
        }
    }

    public function getRemainingToUnlockNextBadge(string|int $userId): int
    {
        $user = User::findOrFail($userId);
        $watchLessonCount = $user->watched()->count();
        $commentCount = $user->comments()->count();
        $achievementCount = $watchLessonCount + $commentCount;

        $badgeThresholds = [0, 4, 8, 10];

        // Special case: User has not achieved any badges
        if ($achievementCount === 0) {
            return $badgeThresholds[1];
        }

        foreach ($badgeThresholds as $threshold) {
            if ($achievementCount < $threshold) {
                return $threshold - $achievementCount;
            }
        }

        return 0;
    }

}