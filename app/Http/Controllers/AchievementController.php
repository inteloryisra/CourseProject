<?php

namespace App\Http\Controllers;

use App\Services\AchievementService;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    protected $achievementService;

    public function __construct(AchievementService $achievementservice)
    {
        $this->achievementService = $achievementservice;
    }

    public function createAchievement(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        return $this->achievementService->createAcievement($data);
    }

    public function updateAchievement(Request $request, $achievementId)
    {
        $data = $request->validate([
            'name' => 'string',
            'description' => 'string',
        ]);

        return $this->achievementService->updateAchievement($achievementId, $data);
    }

    public function deleteAchievement($achievementId)
    {
        return $this->achievementService->deleteAchievement($achievementId);
    }

    public function getAllAchievements()
    {
        return $this->achievementService->getAllAchievements();
    }

    public function getAchievementById($achievementId)
    {
        return $this->achievementService->getAchievementById($achievementId);
    }

}
