<div>
 <x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('User詳細') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          
          <!-- 一覧に戻るリンク -->
          <a href="{{ route('tweets.index') }}" class="text-blue-500 hover:text-blue-700 mr-2">一覧に戻る</a>
          
          <!-- ユーザー名と作成日時 -->
          <p class="text-gray-800 dark:text-gray-300 text-lg">{{ $user->name }}</p>
          <div class="text-gray-600 dark:text-gray-400 text-sm">
            <p>アカウント作成日時: {{ $user->created_at->format('Y-m-d H:i') }}</p>
          </div>

          <!-- 自分以外のユーザーの場合 -->
          @if ($user->id !== auth()->id())
          <div class="text-gray-900 dark:text-gray-100">

            <!-- フォロー / アンフォローボタン -->
            @if ($user->followers->contains(auth()->id()))
            <form action="{{ route('follow.destroy', $user) }}" method="POST">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-red-500 hover:text-red-700">unFollow</button>
            </form>
            @else
            <form action="{{ route('follow.store', $user) }}" method="POST">
              @csrf
              <button type="submit" class="text-blue-500 hover:text-blue-700">follow</button>
            </form>
            @endif

            <!-- DMボタン -->
            <div class="mt-4">
              <form method="POST" action="{{ route('room.store', $user) }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                  DMを開始する
                </button>
              </form>
            </div>

          </div>
          @endif

          <!-- フォロー / フォロワー数 -->
          <p>following: {{ $user->follows->count() }}</p>
          <p>followers: {{ $user->followers->count() }}</p>

          <!-- ユーザーのツイート表示 -->
          @if ($tweets->count())
          
          <!-- ページネーション（上部） -->
          <div class="mb-4">
            {{ $tweets->appends(request()->input())->links() }}
          </div>

          @foreach ($tweets as $tweet)
          <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
            <p class="text-gray-800 dark:text-gray-300">{{ $tweet->tweet }}</p>
            <a href="{{ route('profile.show', $tweet->user) }}">
              <p class="text-gray-600 dark:text-gray-400 text-sm">投稿者: {{ $tweet->user->name }}</p>
            </a>
            <a href="{{ route('tweets.show', $tweet) }}" class="text-blue-500 hover:text-blue-700">詳細を見る</a>

            <div class="flex">
              @if ($tweet->liked->contains(auth()->id()))
              <form action="{{ route('tweets.dislike', $tweet) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700">dislike {{ $tweet->liked->count() }}</button>
              </form>
              @else
              <form action="{{ route('tweets.like', $tweet) }}" method="POST">
                @csrf
                <button type="submit" class="text-blue-500 hover:text-blue-700">like {{ $tweet->liked->count() }}</button>
              </form>
              @endif
            </div>
          </div>
          @endforeach

          <!-- ページネーション（下部） -->
          <div class="mt-4">
            {{ $tweets->appends(request()->input())->links() }}
          </div>

          @else
          <p>No tweets found.</p>
          @endif

        </div>
      </div>
    </div>
  </div>

 </x-app-layout>
</div>
