<?php

use App\Models\Course;
use App\Models\Enrollment;
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

// Test 11 - Teacher User
test('teachers can dele their own courses', function () {
    $teacher = User::factory()->teacher()->create();

    $course = Course::factory()->create(['teacher_id' => $teacher->id]);

    $response = $this
        ->actingAs($teacher)
        ->delete(route('courses.destroy', $course));

    $this->assertDatabaseMissing('courses', ['id' => $course->id]);
});

// Test 12 - Teacher User
test('teachers cannot delete other teachers courses', function () {
    $teacher = User::factory()->teacher()->create();

    $course = Course::factory()->create();

    $response = $this
        ->actingAs($teacher)
        ->delete(route('courses.destroy', $course));

    $response->assertForbidden();
    $this->assertDatabaseHas('courses', ['id' => $course->id]);
});

// Test 13 - Student User
test('students do not see courses they are already enrolled in', function () {
    $student = User::factory()->create();

    $enrolledCourse = Course::factory()->create(['language' => 'Klingon']);
    $availableCourse = Course::factory()->create(['language' => 'Elvish']);

    Enrollment::factory()->create([
        'student_id' => $student->id,
        'course_id' => $enrolledCourse->id,
    ]);

    $response = $this
        ->actingAs($student)
        ->get('/courses');

    $response->assertOk();
    $response->assertSee('Elvish');
    $response->assertDontSee('Klingon');
});

// Test 14 - Teacher User
test('teachers see the students enrolled in their own course', function () {
    $teacher = User::factory()->teacher()->create();
    $course = Course::factory()->create(['teacher_id' => $teacher->id]);
    $enrollment = Enrollment::factory()->create(['course_id' => $course->id]);

    $response = $this
        ->actingAs($teacher)
        ->get(route('courses.show', $course));

    $response->assertOk();
    $response->assertSee($enrollment->student->email);
});

// Test 15 - Student User
test('students do not see who else is enrolled in a course', function () {
    $student = User::factory()->create();
    $course = Course::factory()->create();
    $enrollment = Enrollment::factory()->create(['course_id' => $course->id]);

    $response = $this
        ->actingAs($student)
        ->get(route('courses.show', $course));

    $response->assertOk();
    $response->assertDontSee($enrollment->student->email);
});
