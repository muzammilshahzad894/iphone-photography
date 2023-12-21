<?php

namespace App\Http\Controllers;

use App\Interfaces\AchievementRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AchievementsController extends Controller
{
    public function __construct(protected AchievementRepositoryInterface $achievementRepository) {}

    public function index(string|int $userId)
    {
        $user = User::findOrFail($userId);
        try {
            $unlockedAchievements = $this->achievementRepository->getUnlockedAchievements($user->id);
            $nextAvailableAchievements = $this->achievementRepository->getNextAvailableAchievements($user->id);
            $currentBadge = $this->achievementRepository->getCurrentBadge($user->id);
            $nextBadge = $this->achievementRepository->getNextBadge($user->id);
            $remainingToUnlockNextBadge = $this->achievementRepository->getRemainingToUnlockNextBadge($user->id);

            return response()->json([
                'unlocked_achievements' => $unlockedAchievements,
                'next_available_achievements' => $nextAvailableAchievements,
                'current_badge' => $currentBadge,
                'next_badge' => $nextBadge,
                'remaing_to_unlock_next_badge' => $remainingToUnlockNextBadge
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'error' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
}
