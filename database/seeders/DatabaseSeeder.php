<?php

    namespace Database\Seeders;

    use App\Models\User;
    use Illuminate\Database\Seeder;

    class DatabaseSeeder extends Seeder
    {
        /**
         * Seed the application's database.
         */
        public function run(): void
        {
            // デフォルトのユーザーを作成
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
            
            // TweetSeederを呼び出してツイートを作成
            $this->call(TweetSeeder::class);
        }
    }