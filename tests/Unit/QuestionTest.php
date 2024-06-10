<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Question;
use App\Models\Language;
use App\Models\Quiz;
use App\Services\QuestionService;

class QuestionTest extends TestCase
{
    /**
     * A basic unit test example.
     */

     use RefreshDatabase;

     public function testCreateQuestion()
     {
        $quiz = Quiz::factory()->create();
        $language = Language::factory()->create();



        $data = [
            'quiz_id' => $quiz->id,
            'question' => 'test question',
            'language_id' => $language->id,
        ];

        $response = $this->postJson('api/create-question', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('questions', [
            'quiz_id' => $quiz->id,
            'question' => 'test question',
            'language_id' => $language->id,
        ]);
     }

     public function testUpdateQuestion()
     {
       $question = Question::factory()->create();
       $quiz = Quiz::factory()->create();
       $language = Language::factory()->create();

       $data = [
        'quiz_id' => $quiz->id,
        'question' => 'updated question',
        'language_id' => $language->id,
       ];

       $questionService = new QuestionService();
       $updatedQuestion = $questionService->updateQuestion($question->id, $data);

       $this->assertEquals($data['quiz_id'], $updatedQuestion->quiz_id);
       $this->assertEquals($data['question'], $updatedQuestion->question);
       $this->assertEquals($data['language_id'], $updatedQuestion->language_id);

     }

     public function testDeleteQuestion()
    {

        $question = Question::factory()->create();

        $questionService = new QuestionService();
        $deleted = $questionService->deleteQuestion($question->id);


        $this->assertEquals(1, $deleted);


        $this->assertDatabaseMissing('questions', [
            'id' => $question->id,
        ]);
    }


}
