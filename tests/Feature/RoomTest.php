use App\Models\User;
use App\Models\Room;

it('has room page', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $room = Room::factory()->create();
    $room->users()->attach([$user1->id, $user2->id]);

    $this->actingAs($user1)
         ->get(route('room.show', $room))
         ->assertStatus(200)
         ->assertSee($user2->name); // 参加者の名前が表示されるか確認
});
