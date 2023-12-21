<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Lesson;
use App\Models\Comment;
use App\Events\CommentWritten;
use App\Events\LessonWatched;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class AchievementsControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     */
    public function test_it_returns_404_when_user_does_not_exist()
    {
        $response = $this->getJson('/api/users/1/achievements');

        $response->assertStatus(ResponseAlias::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function test_it_return_achievements_when_user_has_1_lesson_achievement()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        $this->actingAs($user);

        LessonWatched::dispatch($lesson, $user);

        $response = $this->getJson("/users/{$user->id}/achievements");
        $response->assertStatus(ResponseAlias::HTTP_OK);
        $response->assertJson([
            'unlocked_achievements' => ['First Lesson Watched'],
            'next_available_achievements' => ['5 Lessons Watched', 'First Comment Written'],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaing_to_unlock_next_badge' => 3
        ]);
    }

    /**
     * @test
     */
    public function test_it_return_achievements_when_user_has_1_comment_achievement()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->actingAs($user);

        CommentWritten::dispatch($comment, $user);

        $response = $this->getJson("/users/{$user->id}/achievements");
        $response->assertStatus(ResponseAlias::HTTP_OK);
        $response->assertJson([
            'unlocked_achievements' => ['First Comment Written'],
            'next_available_achievements' => ['First Lesson Watched', '3 Comments Written'],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaing_to_unlock_next_badge' => 3
        ]);
    }

    /**
     * @test
     */
    public function test_it_return_achievements_when_user_has_1_lesson_and_1_comment_achievement()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        $comment = Comment::factory()->create();

        $this->actingAs($user);

        LessonWatched::dispatch($lesson, $user);
        CommentWritten::dispatch($comment, $user);

        $response = $this->getJson("/users/{$user->id}/achievements");
        $response->assertStatus(ResponseAlias::HTTP_OK);
        $response->assertJson([
            'unlocked_achievements' => ['First Lesson Watched', 'First Comment Written'],
            'next_available_achievements' => ['5 Lessons Watched', '3 Comments Written'],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaing_to_unlock_next_badge' => 2
        ]);
    }

    /**
     * @test
     */
    public function test_it_return_achievements_when_user_has_2_lesson_and_2_comment_achievement()
    {
        $user = User::factory()->create();
        $lesson1 = Lesson::factory()->create();
        $comment1 = Comment::factory()->create();
        $lesson2 = Lesson::factory()->create();
        $comment2 = Comment::factory()->create();

        $this->actingAs($user);

        LessonWatched::dispatch($lesson1, $user);
        CommentWritten::dispatch($comment1, $user);
        LessonWatched::dispatch($lesson2, $user);
        CommentWritten::dispatch($comment2, $user);

        $response = $this->getJson("/users/{$user->id}/achievements");
        $response->assertStatus(ResponseAlias::HTTP_OK);
        $response->assertJson([
            'unlocked_achievements' => ['First Lesson Watched', 'First Comment Written'],
            'next_available_achievements' => ['5 Lessons Watched', '3 Comments Written'],
            'current_badge' => 'Intermediate',
            'next_badge' => 'Advanced',
            'remaing_to_unlock_next_badge' => 4
        ]);
    }
}