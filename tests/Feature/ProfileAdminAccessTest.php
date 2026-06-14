<?php

use App\Models\User;

test('admin profile does not show become a vendor link', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertDontSee(__('profile_page.become_vendor'), false);
});

test('admin profile does not show danger zone section', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertDontSee('Danger Zone', false);
});

test('regular member still sees become a vendor link when eligible', function () {
    $user = User::factory()->create(['role' => 'user']);

    $this->actingAs($user)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee(__('profile_page.become_vendor'), false);
});
