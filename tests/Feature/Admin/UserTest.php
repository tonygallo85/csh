<?php

use App\Models\User;

// Test 1 - Guest
test('guests are redirected to login', function () {
    $response = $this->get('/admin/users');

    $response->assertRedirect(route('login'));
});

// Test 2 - Non-Admin User
test('non-admin users are forbidden from seeing users', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/admin/users');

    $response->assertForbidden();
});

// Test 3 - Admin User
test('admin users are shown users', function () {
    $admin = User::factory()->create([
        'is_admin' => true,
    ]);

    $student = User::factory()->create();

    $response = $this
        ->actingAs($admin)
        ->get('/admin/users');

    $response->assertOk();
    $response->assertSee($student->email);
});

// Test 4 - Admin User
test('admin can upgrade student to teacher', function () {
    $admin = User::factory()->create([
        'is_admin' => true,
    ]);

    $student = User::factory()->create();

    $response = $this
        ->actingAs($admin)
        ->patch(route('admin.users.update', $student), [
            'is_teacher' => true,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/admin/users');

    $student->refresh();

    expect($student->is_teacher)->toBeTrue();
});

// Test 5 - Non-Admin User
test('non-admin users cannot upgrade themselves to teacher', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)

        ->patch(route('admin.users.update', $user), [
            'is_teacher' => true,
        ]);

    $response->assertForbidden();

    $user->refresh();

    expect($user->is_teacher)->toBeFalse();
});
