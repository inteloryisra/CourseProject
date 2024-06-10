<?php


namespace Tests\Unit;

use App\Enums\UserRoles;
use Tests\TestCase;
use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Quiz;
use App\Models\User;
use App\Models\Answer;
use App\Models\Question;
use app\Services\QuizService;
use Laravel\Sanctum\Sanctum;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateQuiz()
    {

        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        Sanctum::actingAs($admin);


        $language = Language::factory()->create();


        $data = [
            'title' => 'Sample Quiz',
            'description' => 'This is a sample quiz.',
            'language_id' => $language->id,
        ];


        $response = $this->postJson('/api/create-quiz', $data);


        $response->assertStatus(201);


        $this->assertDatabaseHas('quizzes', [
            'title' => 'Sample Quiz',
            'description' => 'This is a sample quiz.',
            'language_id' => $language->id,
        ]);
    }

    public function testUpdateQuiz()
    {

        $quiz = Quiz::factory()->create();


        $language = Language::factory()->create();


        $data = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'language_id' => $language->id,
        ];


        $quizService = new QuizService();
        $updatedQuiz = $quizService->updateQuiz($quiz->id, $data);


        $this->assertEquals($data['title'], $updatedQuiz->title);
        $this->assertEquals($data['description'], $updatedQuiz->description);
        $this->assertEquals($data['language_id'], $updatedQuiz->language_id);
    }

    public function testDeleteQuiz()
    {

        $quiz = Quiz::factory()->create();


        $quizService = new QuizService();
        $deleted = $quizService->deleteQuiz($quiz->id);


        $this->assertEquals(1, $deleted);


        $this->assertDatabaseMissing('quizzes', [
            'id' => $quiz->id,
        ]);
    }

    public function testGetQuizById()
    {

        $quiz = Quiz::factory()->create();


        $language = Language::factory()->create();


        $question = Question::factory()->create([
            'quiz_id' => $quiz->id,
            'language_id' => $language->id,
        ]);


        $answers = Answer::factory()->count(3)->create([
            'question_id' => $question->id,
            'language_id' => $language->id,
        ]);


        $quizService = new QuizService();
        $retrievedQuiz = $quizService->getQuizById($quiz->id);


        $this->assertEquals($quiz->id, $retrievedQuiz->id);
        $this->assertCount(1, $retrievedQuiz->questions);
        $this->assertCount(3, $retrievedQuiz->questions[0]->answers);
    }
    public function testGetAllQuizzes()
    {

        $quizzes = Quiz::factory()->count(4)->create();


        $quizService = new QuizService();
        $retrievedQuizzes = $quizService->getAllQuizzes();


        $this->assertCount(4, $retrievedQuizzes);


        foreach ($quizzes as $quiz) {
            $this->assertTrue($retrievedQuizzes->contains($quiz));
        }
    }

    public function testGetQuizByLanguage()
    {

        $language = Language::factory()->create();


        $quizzes = Quiz::factory()->count(3)->create(['language_id' => $language->id]);


        foreach ($quizzes as $quiz) {
            $question = Question::factory()->create(['quiz_id' => $quiz->id, 'language_id' => $language->id]);
            $answers = Answer::factory()->count(3)->create(['question_id' => $question->id, 'language_id' => $language->id]);
        }


        $quizService = new QuizService();
        $retrievedQuizzes = $quizService->getQuizByLanguage($language->id);


        $this->assertCount(3, $retrievedQuizzes);


        foreach ($quizzes as $quiz) {
            $retrievedQuiz = $retrievedQuizzes->where('id', $quiz->id)->first();
            $this->assertNotNull($retrievedQuiz);
            $this->assertCount(1, $retrievedQuiz->questions);
            $this->assertCount(3, $retrievedQuiz->questions[0]->answers);
        }
    }


}
