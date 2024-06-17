<?php

namespace Tests\Unit;

use App\Services\AchievementService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Language;
use App\Models\Achievement;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();


        Achievement::factory()->create(['name' => 'First Quiz Completed', 'description' => 'Completed your first quiz!']);
        Achievement::factory()->create(['name' => 'High Score', 'description' => 'Scored 10 or higher on a quiz!']);
    }

    public function testCreateAchievement()
    {
        $data = [
            'name' => 'Test Achievement',
            'description' => 'This is a test achievement.',
        ];

        $response = $this->postJson('/api/create-achievement', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('achievements', [
            'name' => 'Test Achievement',
            'description' => 'This is a test achievement.',
        ]);
    }

    public function testUpdateAchievement()
    {
        $achievement = Achievement::factory()->create();

        $achievementService = new AchievementService();

        $updatedData = [
            'name' => 'Updated Achievement',
            'description' => 'This achievement has been updated.',
        ];

        $updatedAchievement = $achievementService->updateAchievement($achievement->id, $updatedData);

        $this->assertEquals($updatedData['name'], $updatedAchievement->name);
        $this->assertEquals($updatedData['description'], $updatedAchievement->description);
    }

    public function testDeleteAchievement()
    {
        $achievement = Achievement::factory()->create();

        $achievementService = new AchievementService();

        $deleted = $achievementService->deleteAchievement($achievement->id);

        $this->assertEquals(1, $deleted);

        $this->assertDatabaseMissing('achievements', [
            'id' => $achievement->id,
        ]);
    }

    public function testGetAllAchievements()
    {
        $achievements = Achievement::factory()->count(5)->create();

        $achievementService = new AchievementService();

        $retrievedAchievements = $achievementService->getAllAchievements();

        $this->assertCount(5, $achievements);
        foreach ($achievements as $achievement) {
            $this->assertTrue($retrievedAchievements->contains($achievement));
        }
    }

    public function testGetAchievementById()
    {
        $achievement = Achievement::factory()->create();

        $achievementService = new AchievementService();

        $retrievedAchievement = $achievementService->getAchievementById($achievement->id);

        $this->assertEquals($achievement->id, $retrievedAchievement->id);
    }

    public function testAwardFirstQuizCompletedAchievement()
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create();
        $language = Language::factory()->create();
        $achievementService = new AchievementService();

        $user->quizAttempts()->create(['quiz_id' => $quiz->id, 'language_id' => $language->id, 'score' => 80]);

        $achievementService->checkAndAwardAchievements($user);

        $this->assertTrue($user->achievements()->where('name', 'First Quiz Completed')->exists());
    }

    public function testAwardHighScoreAchievement()
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create();
        $language = Language::factory()->create();
        $achievementService = new AchievementService();

        $user->quizAttempts()->create(['quiz_id' => $quiz->id, 'language_id' => $language->id, 'score' => 80]);

        $achievementService->checkAndAwardAchievements($user);

        $this->assertTrue($user->achievements()->where('name', 'High Score')->exists());
    }

    public function testNoAchievementsAwardedIfConditionsNotMet()
    {
        $user = User::factory()->create();

        $this->assertFalse($user->achievements()->exists());
    }

}
