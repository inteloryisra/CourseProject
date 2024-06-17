<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AchievementService
{

    public function createAcievement($data)
    {
        return Achievement::query()->create($data);
    }

    public function updateAchievement($achievementId, $data)
    {
        $achievement = Achievement::query()->findOrFail($achievementId);
        $achievement->update($data);
        return $achievement;
    }

    public function deleteAchievement($achievementId)
    {
        return Achievement::destroy($achievementId);
    }

    public function getAllAchievements()
    {
        return Achievement::query()->get();
    }

    public function getAchievementById($achievementId)
    {
        return Achievement::query()->findOrFail($achievementId);
    }
    public function checkAndAwardAchievements(User $user)
    {

        if ($user->quizAttempts()->count() > 0) {
            $this->awardAchievement($user, 'First Quiz Completed');
        }

        if ($user->quizAttempts()->where('score', '>=', 10)->exists()) {
            $this->awardAchievement($user, 'High Score');
        }
    }

    protected function awardAchievement(User $user, string $achievementName)
    {

        $achievement = Achievement::where('name', $achievementName)->first();
        if ($achievement) {
            $userAchievementExists = $user->achievements()
                                          ->where('achievements.id', $achievement->id)
                                          ->exists();
            if (!$userAchievementExists) {
                $user->achievements()->attach($achievement->id);
            }
        }
    }

}


