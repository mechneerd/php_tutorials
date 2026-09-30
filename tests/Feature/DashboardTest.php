<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('learn.index'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the learning dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('learn.index'));
    $response->assertOk();
});

test('dashboard path redirects authenticated users to learn', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get(route('dashboard'))->assertRedirect('/learn');
});
