<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

class BadgeUnlocked
{
    use SerializesModels;

    public $badgeName;
    public $user;

    /**
     * Create a new event instance.
     *
     * @param string $badgeName
     * @param User $user
     */
    public function __construct(string $badgeName, User $user)
    {
        $this->badgeName = $badgeName;
        $this->user = $user;
    }
}