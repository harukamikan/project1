<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tweet;
use Carbon\Carbon;

class TweetSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $keyword = 'test';

        // 1人のユーザーを先に作る
        $user = \App\Models\User::factory()->create();

        // test を含むツイート 30件
        for ($i = 0; $i < 30; $i++) {
            Tweet::factory()->create([
                'user_id' => $user->id,
                'tweet' => "This is a {$keyword} tweet",
                'created_at' => $now->copy()->subSeconds(100 - $i),
                'updated_at' => $now->copy()->subSeconds(100 - $i),
            ]);
        }

        // ランダムツイート 70件
        for ($i = 30; $i < 100; $i++) {
            Tweet::factory()->create([
                'user_id' => $user->id,
                'created_at' => $now->copy()->subSeconds(100 - $i),
                'updated_at' => $now->copy()->subSeconds(100 - $i),
            ]);
        }
    }
}
