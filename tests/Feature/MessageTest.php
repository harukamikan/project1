<?php

use App\Models\User;
use App\Models\Room;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can send a message in a room', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $room = Room::factory()->create();
    $room->users()->attach([$user1->id, $user2->id]);

    $this->actingAs($user1)
         ->post(route('message.store', $room), ['content' => 'Hello!'])
         ->assertRedirect();

    $this->assertDatabaseHas('messages', [
        'room_id' => $room->id,
        'user_id' => $user1->id,
        'content' => 'Hello!'
    ]);
});
