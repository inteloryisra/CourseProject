<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AchievementService
{

    public function createAchievement($data)
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
        $achievements = Achievement::all();


        foreach ($achievements as $achievement) {


            $condition = $achievement->condition_value;


            if ($achievement->condition_type == 'quiz_attempts_count') {
                if (isset($condition) && $user->quizAttempts()->count() >= $condition) {
                    $this->awardAchievement($user, $achievement->name);
                }
            }


            if ($achievement->condition_type == 'quiz_high_score') {
                if (isset($condition) && $user->quizAttempts()->where('score', '>=', $condition)->exists()) {
                    $this->awardAchievement($user, $achievement->name);
                }
            }
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


