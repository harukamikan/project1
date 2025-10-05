<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            DMルーム
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <h3 class="text-lg font-semibold mb-4">参加者:</h3>
            <ul>
                @foreach($room->users as $user)
                    <li>{{ $user->name }}</li>
                @endforeach
            </ul>

            <hr class="my-4">

            <div class="space-y-2">
                @foreach($room->messages as $message)
                    <div>
                        <strong>{{ $message->user->name }}:</strong>
                        {{ $message->content }}
                        <span class="text-xs text-gray-500">({{ $message->created_at->format('H:i') }})</span>
                    </div>
                @endforeach
            </div>

            <form action="{{ route('message.store', $room) }}" method="POST" class="mt-4 flex">
                @csrf
                <input type="text" name="content" class="flex-1 border rounded px-2 py-1" placeholder="メッセージを入力">
                <button type="submit" class="ml-2 bg-indigo-600 text-white px-4 py-1 rounded">送信</button>
            </form>

        </div>
    </div>
</x-app-layout>
