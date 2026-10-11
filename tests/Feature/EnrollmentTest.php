<?php

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;

// Test 1 - Student User
test('students can enroll in courses', function () {
    $student = User::factory()->create();

    $course = Course::factory()->create();

    $response = $this
        ->actingAs($student)
        ->post(route('courses.enrollments.store', $course));

    $this->assertDatabaseHas('enrollments', ['student_id' => $student->id, 'course_id' => $course->id]);
});

// Test 2 - Student User
test('students cannot enroll in course twice', function () {
    $student = User::factory()->create();

    $course = Course::factory()->create();

    $enrollment = Enrollment::factory()->create([
        'course_id' => $course->id,
        'student_id' => $student->id,
    ]);

    $response = $this
        ->actingAs($student)
        ->post(route('courses.enrollments.store', $course));

    $response->assertForbidden();
    $this->assertDatabaseCount('enrollments', 1);
});

// Test 3 - Teacher User
test('teachers cannot enroll in courses', function () {
    $teacher = User::factory()->teacher()->create();

    $course = Course::factory()->create();

    $response = $this
        ->actingAs($teacher)
        ->post(route('courses.enrollments.store', $course));

    $response->assertForbidden();
    $this->assertDatabaseCount('enrollments', 0);
});

// Test 4 - Student User
test('students can unenroll from their own courses', function () {
    $student = User::factory()->create();

    $course = Course::factory()->create();

    $enrollment = Enrollment::factory()->create([
        'course_id' => $course->id,
        'student_id' => $student->id,
    ]);

    $response = $this
        ->actingAs($student)
        ->delete(route('courses.enrollments.destroy', [$course, $enrollment]));

    $this->assertDatabaseMissing('enrollments', ['id' => $enrollment->id]);
});

// Test 5 - Student User
test('students cannot unenroll other students from their courses', function () {
    $student = User::factory()->create();

    $enrollment = Enrollment::factory()->create();

    $course = $enrollment->course;

    $response = $this
        ->actingAs($student)
        ->delete(route('courses.enrollments.destroy', [$course, $enrollment]));

    $response->assertForbidden();
    $this->assertDatabaseHas('enrollments', ['id' => $enrollment->id]);
});
