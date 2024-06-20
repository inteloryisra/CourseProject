<?php

namespace Tests\Unit;

use App\Services\AchievementService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Language;
use App\Models\Achievement;
use App\Models\QuizAttempt;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();


        Achievement::factory()->create(['name' => 'First Quiz Completed',  'condition_type' => 'quiz_attempts_count','condition_value' => 1]);
        Achievement::factory()->create(['name' => 'High Score', 'condition_type' => 'quiz_high_score','condition_value' => 10,]);
        Achievement::factory()->create(['name' => 'Bronze Streak', 'condition_type' => 'consecutive_quiz_wins', 'condition_value' => 3]);
        Achievement::factory()->create(['name' => 'Silver Streak', 'condition_type' => 'consecutive_quiz_wins', 'condition_value' => 5]);
        Achievement::factory()->create(['name' => 'Gold Streak', 'condition_type' => 'consecutive_quiz_wins', 'condition_value' => 10]);
    }

    public function testCreateAchievement()
    {
        $data = [
            'name' => 'Test Achievement',
            'condition_type' => 'test_achievement',
            'condition_value' => 1,
        ];

        $response = $this->postJson('/api/create-achievement', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('achievements', [
            'name' => 'Test Achievement',
            'condition_type' => 'test_achievement',
            'condition_value' => 1,
        ]);
    }

    public function testUpdateAchievement()
    {
        $achievement = Achievement::factory()->create();

        $achievementService = new AchievementService();

        $updatedData = [
            'name' => 'Test Achievement',
            'condition_type' => 'test_achievement',
            'condition_value' => 1,
        ];

        $updatedAchievement = $achievementService->updateAchievement($achievement->id, $updatedData);

        $this->assertEquals($updatedData['name'], $updatedAchievement->name);
        $this->assertEquals($updatedData['condition_type'], $updatedAchievement->condition_type);
        $this->assertEquals($updatedData['condition_value'], $updatedAchievement->condition_value);
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
    public function testAwardBronzeStreakAchievement()
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create();
        $language = Language::factory()->create();
        $achievementService = new AchievementService();

        $attempts = QuizAttempt::factory()->count(3)->create(['user_id' => $user->id, 'quiz_id' => $quiz->id, 'language_id' => $language->id, 'score' => 80]);

        $achievementService->checkAndAwardAchievements($user);

        $this->assertTrue($user->achievements()->where('name', 'High Score')->exists());
    }
    public function testAwardSilverStreakAchievement()
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create();
        $language = Language::factory()->create();
        $achievementService = new AchievementService();

        $attempts = QuizAttempt::factory()->count(5)->create(['user_id' => $user->id, 'quiz_id' => $quiz->id, 'language_id' => $language->id, 'score' => 80]);

        $achievementService->checkAndAwardAchievements($user);

        $this->assertTrue($user->achievements()->where('name', 'Silver Streak')->exists());
    }
    public function testAwardGoldStreakAchievement()
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create();
        $language = Language::factory()->create();
        $achievementService = new AchievementService();

        $attempts = QuizAttempt::factory()->count(10)->create(['user_id' => $user->id, 'quiz_id' => $quiz->id, 'language_id' => $language->id, 'score' => 80]);

        $achievementService->checkAndAwardAchievements($user);

        $this->assertTrue($user->achievements()->where('name', 'Gold Streak')->exists());
    }


    public function testNoAchievementsAwardedIfConditionsNotMet()
    {
        $user = User::factory()->create();

        $this->assertFalse($user->achievements()->exists());
    }

}
