<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    /**
     * 新規ルームを作成または既存ルームにリダイレクトする
     */
    public function store(User $user)
    {
        $currentUserId = auth()->id();

        // ログインユーザーと相手ユーザーが参加している既存のルームを探す
        // 1対1のDMルームを探すための複雑なクエリ
        $room = Room::whereHas('users', function ($query) use ($currentUserId, $user) {
            $query->where('user_id', $currentUserId)
                  ->whereIn('room_id', function ($query) use ($user) {
                      // 相手ユーザーも参加しているルームIDのリストを取得
                      $query->select('room_id')
                            ->from('room_user')
                            ->where('user_id', $user->id);
                  });
        })
        ->withCount('users')
        ->having('users_count', 2) // 参加者が2人のルームのみを対象（1対1DM）
        ->first();


        if ($room) {
            // 既存のルームがあれば、そのルームの表示ページにリダイレクト
            return redirect()->route('room.show', $room);
        }

        // ルームが存在しない場合は新規作成
        try {
            DB::beginTransaction();
            
            $newRoom = Room::create();
            
            // ログインユーザーと相手ユーザーを新しいルームに追加
            $newRoom->users()->attach([$currentUserId, $user->id]);

            DB::commit();

            // 新規ルームの表示ページにリダイレクト
            return redirect()->route('room.show', $newRoom);

        } catch (\Exception $e) {
            DB::rollBack();
            // 失敗した場合はエラーログを出力し、プロフィールページに戻す
            \Log::error('Room creation failed: ' . $e->getMessage());
            return back()->withErrors(['dm' => 'DMルームの作成に失敗しました。']);
        }
    }

    /**
     * 指定されたルームのメッセージを表示する
     */
    public function show(Room $room)
    {
        // ログインユーザーがそのルームのメンバーか確認
        if (!$room->users->contains(auth()->id())) {
            // メンバーでなければダッシュボードなどにリダイレクト（セキュリティ）
            return redirect('/dashboard')->withErrors(['dm' => 'アクセス権限がありません。']);
        }

        // ルームのメッセージとメンバーを取得
        // messagesには送信者(user)情報も事前にロード(eager load)しておく
        $room->load(['messages.user', 'users']);

        // ビュー（room.show.blade.php）にデータを渡す
        return view('room.show', compact('room'));
    }
}
