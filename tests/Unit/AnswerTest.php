<?php


use Tests\TestCase;
use App\Services\AnswerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Language;
use App\Models\Quiz;

class AnswerTest extends TestCase
{
    use RefreshDatabase;
    protected $answerService;

    public function setUp(): void
    {
        parent::setUp();
        $this->answerService = new AnswerService();
    }

    public function testCreateAnswer()
    {
        $question = Question::factory()->create();
        $language = Language::factory()->create();


        $data = [
            'question_id' => $question->id,
            'answer' => 'Sample answer',
            'is_correct' => true,
            'language_id' => $language->id,
        ];

        $response = $this->postJson('/api/create-answer', $data);


        $response->assertStatus(201);


        $this->assertDatabaseHas('answers', [
            'question_id' => $question->id,
            'answer' => 'Sample answer',
            'is_correct' => true,
            'language_id' => $language->id,
        ]);
    }

    public function testUpdateAnswer()
    {
        $answer = Answer::factory()->create();
        $question = Question::factory()->create();
        $language = Language::factory()->create();

        $data = [
            'question_id' => $question->id,
            'answer' => 'Sample answer',
            'is_correct' => true,
            'language_id' => $language->id,
        ];

        $answerService = new AnswerService();
        $updatedAnswer = $answerService->updateAnswer($answer->id, $data);

        $this->assertEquals($data['question_id'], $updatedAnswer->question_id);
        $this->assertEquals($data['answer'], $updatedAnswer->answer);
        $this->assertEquals($data['is_correct'], $updatedAnswer->is_correct);
        $this->assertEquals($data['language_id'], $updatedAnswer->language_id);

    }

    public function testDeleteAnswer()
    {

        $answer = Answer::factory()->create();


        $answerService = new AnswerService();
        $deleted = $answerService->deleteAnswer($answer->id);


        $this->assertEquals(1, $deleted);


        $this->assertDatabaseMissing('answers', [
            'id' => $answer->id,
        ]);
    }

    public function testGetAnswerById()
    {

        $quiz = Quiz::factory()->create();


        $language = Language::factory()->create();


        $question = Question::factory()->create([
            'quiz_id' => $quiz->id,
            'language_id' => $language->id,
        ]);


        $answer = Answer::factory()->create([
            'question_id' => $question->id,
            'language_id' => $language->id,
        ]);


        $answerService = new AnswerService();
        $retrievedAnswer = $answerService->getAnswerById($answer->id);


        $this->assertEquals($answer->id, $retrievedAnswer->id);
    }

    public function testGetAllAnswers()
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


        $answerService = new AnswerService();
        $retrievedAnswers = $answerService->getAllAnswers();


        $this->assertCount(3, $retrievedAnswers);


        foreach ($answers as $answer) {
            $this->assertTrue($retrievedAnswers->contains($answer));
        }
    }

}
