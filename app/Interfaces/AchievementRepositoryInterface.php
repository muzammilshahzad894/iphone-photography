<?php

namespace App\Interfaces;

interface AchievementRepositoryInterface
{
    public function getUnlockedAchievements(string|int $userId): array;

    public function getNextAvailableAchievements(string|int $userId): array;

    public function getCurrentBadge(string|int $userId): string;

    public function getNextBadge(string|int $userId): string;

    public function getRemainingToUnlockNextBadge(string|int $userId): int;
}