<?php

use App\Models\User;

test('guests are redirected to login', function () {
    $response = $this->get('/admin/users');

    $response->assertRedirect(route('login'));
});

test('non-admin users are forbidden from seeing users', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/admin/users');

    $response->assertForbidden();
});

test('admin users are shown users', function () {
    $user = User::factory()->create([
        'is_admin' => true,
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/admin/users');

    $response->assertOk();
});
