<?php

use App\Models\Course;

// Test 1 - Guest Users
test('guest users are able to see available courses', function () {
    $course = Course::factory()->create();

    $response = $this
        ->get('/');

    $response->assertOk();
    $response->assertSee($course->schedule);
});

// Test 2 - Guest Users
test('guest users are not shown assigned teachers', function () {
    $course = Course::factory()->create();

    $response = $this
        ->get('/');

    $response->assertOk();
    $response->assertDontSee($course->teacher->name);
});
