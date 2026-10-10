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

// Test 3 - Guest User
test('guests are redirected to login when trying to view course details', function () {

    $course = Course::factory()->create();

    $response = $this
        ->get(route('courses.show', $course));

    $response->assertRedirect(route('login'));
});

// Test 4 - Logged User
test('logged users are shown course details', function () {
    $user = User::factory()->create();

    $course = Course::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('courses.show', $course));

    $response->assertOk();
    $response->assertSee($course->schedule);
});

// Test 5 - Teacher User
test('teachers can create courses', function () {
    $user = User::factory()->teacher()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('courses.store'), [
            'language' => 'Italian',
            'level' => 'A2',
            'schedule' => 'Thursday 8:00',
        ]);

    $response->assertRedirect(route('courses.index'));
    $this->assertDatabaseHas('courses', [
        'language' => 'Italian',
        'level' => 'A2',
        'schedule' => 'Thursday 8:00',
        'teacher_id' => $user->id,
    ]);
});

// Test 6 - Student User
test('students cannot create courses', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('courses.store'), [
            'language' => 'Italian',
            'level' => 'A2',
            'schedule' => 'Thursday 8:00',
        ]);

    $response->assertForbidden();
    $this->assertDatabaseCount('courses', 0);
});

// Test 7 - Admin User
test('admin users creates course with selected teacher', function () {
    $admin = User::factory()->create([
        'is_admin' => true,
    ]);

    $teacher = User::factory()->teacher()->create();

    $response = $this
        ->actingAs($admin)
        ->post(route('courses.store'), [
            'language' => 'Italian',
            'level' => 'A2',
            'schedule' => 'Thursday 8:00',
            'teacher_id' => $teacher->id,
        ]);

    $response->assertRedirect(route('courses.index'));
    $this->assertDatabaseHas('courses', [
        'language' => 'Italian',
        'level' => 'A2',
        'schedule' => 'Thursday 8:00',
        'teacher_id' => $teacher->id,
    ]);
});

// Test 8 - Admin User
test('admin users cannot creates course with student user', function () {
    $admin = User::factory()->create([
        'is_admin' => true,
    ]);

    $student = User::factory()->create();

    $response = $this
        ->actingAs($admin)
        ->post(route('courses.store'), [
            'language' => 'Italian',
            'level' => 'A2',
            'schedule' => 'Thursday 8:00',
            'teacher_id' => $student->id,
        ]);

    $response->assertSessionHasErrors('teacher_id');
    $this->assertDatabaseCount('courses', 0);
});

// Test 9 - Teacher User
test('teachers can update only their courses', function () {
    $teacher = User::factory()->teacher()->create();

    $course = Course::factory()->create(['teacher_id' => $teacher->id]);

    $response = $this
        ->actingAs($teacher)
        ->patch(route('courses.update', $course), [
            'language' => 'Italian',
            'level' => 'A2',
            'schedule' => 'Thursday 8:00',
        ]);

    $this->assertDatabaseHas('courses', [
        'language' => 'Italian',
        'level' => 'A2',
        'schedule' => 'Thursday 8:00',
        'teacher_id' => $teacher->id,
    ]);
});

// Test 10 - Teacher User
test('teachers cannot update other teachers courses', function () {
    $user = User::factory()->teacher()->create();

    $course = Course::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('courses.update', $course), [
            'language' => 'Greek',
            'level' => 'C3',
            'schedule' => 'Thursday 8:00',
        ]);

    $response->assertForbidden();
    $this->assertDatabaseMissing('courses', [
        'language' => 'Greek',
        'level' => 'C3',
        'schedule' => 'Thursday 8:00',
    ]);
});
