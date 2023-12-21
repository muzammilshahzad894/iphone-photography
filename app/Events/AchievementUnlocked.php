<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

class AchievementUnlocked
{
    use SerializesModels;

    public $achievementName;
    public $achievementType;
    public $user;

    /**
     * Create a new event instance.
     *
     * @param string $achievementName
     * @param User $user
     */
    public function __construct(string $achievementName, string $achievementType, User $user)
    {
        $this->achievementName = $achievementName;
        $this->achievementType = $achievementType;
        $this->user = $user;
    }
}
