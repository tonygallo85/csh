<?php

use App\Models\Course;
use App\Models\User;

// Test 1 - Guest
test('guests are redirected to login', function () {
    $response = $this->get('/courses');

    $response->assertRedirect(route('login'));
});

// Test 2 - Logged User
test('logged users can see courses', function () {
    $user = User::factory()->create();

    $course = Course::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/courses');

    $response->assertOk();
    $response->assertSee($course->teacher->name);
});
