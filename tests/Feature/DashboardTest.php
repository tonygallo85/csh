<?php

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;

// Test 1 - Student User
test('students see only the courses they are enrolled in', function () {
    $student = User::factory()->create();

    $enrolledCourse = Course::factory()->create(['language' => 'Klingon']);
    $otherCourse = Course::factory()->create(['language' => 'Elvish']);

    Enrollment::factory()->create([
        'student_id' => $student->id,
        'course_id' => $enrolledCourse->id,
    ]);

    $response = $this
        ->actingAs($student)
        ->get('/dashboard');

    $response->assertOk();
    $response->assertSee('Klingon');
    $response->assertDontSee('Elvish');
});

// Test 2 - Teacher User
test('teachers see only the courses they teach', function () {
    $teacher = User::factory()->teacher()->create();

    Course::factory()->create(['language' => 'Klingon', 'teacher_id' => $teacher->id]);
    Course::factory()->create(['language' => 'Elvish']);

    $response = $this
        ->actingAs($teacher)
        ->get('/dashboard');

    $response->assertOk();
    $response->assertSee('Klingon');
    $response->assertDontSee('Elvish');
});

// Test 3 - Non-Admin User
test('users without courses see a message instead of an empty table', function () {
    $student = User::factory()->create();

    $response = $this
        ->actingAs($student)
        ->get('/dashboard');

    $response->assertOk();
    $response->assertSee('You are not in any course yet.');
});
