<?php

use App\Models\DailyWard;
use App\Models\User;

it('shows updated ward statistics on the dashboard', function () {
    $user = User::factory()->create();

    DailyWard::create([
        'user_id' => $user->id,
        'ward_date' => today(),
        'target_unit' => DailyWard::UNIT_PAGES,
        'target_value' => 5,
        'actual_value' => 0,
        'adherence_pct' => 0,
        'is_completed' => false,
    ]);

    $this->actingAs($user)->post(route('ward.complete'))->assertRedirect();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response
        ->assertOk()
        ->assertSee('ورد اليوم مكتمل')
        ->assertSee('ورد مكتمل')
        ->assertSee('10');
});
