<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * メッセージをルームに保存する
     */
    public function store(Request $request, Room $room)
    {
        // ログインユーザーがルームのメンバーか確認
        if (!$room->users->contains(auth()->id())) {
            // メンバーでなければリダイレクト
            return redirect('/dashboard')->withErrors(['dm' => 'このルームにメッセージを送信できません。']);
        }

        // 入力値の検証
        $validated = $request->validate([
            // あなたのDBに合わせて'content'を使用
            'content' => 'required|string|max:1000',
        ]);

        // メッセージを作成・保存
        $room->messages()->create([
            'content' => $validated['content'], // <-- 'content'を使用して保存
            'user_id' => auth()->id(),
        ]);

        // 元のルーム画面に戻る
        return redirect()->route('room.show', $room);
    }
}
