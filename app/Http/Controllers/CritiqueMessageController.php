<?php

namespace App\Http\Controllers;

use App\Http\Requests\CritiqueMessage\CritiqueMessageIndexRequest;
use App\Http\Requests\CritiqueMessage\CritiqueMessageStoreRequest;
use App\Http\Resources\MessageResource;
use App\Models\Critique;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CritiqueMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CritiqueMessageIndexRequest $request, Critique $critique)
    {
        $receiver = $request->input('receiver') ?? null;

        if ($receiver) {
            $messages = Message::where([['sender_id', $critique->id], ['receiver_id', $receiver]])->orWhere([['receiver_id', $critique->id], ['sender_id', $receiver]])->get();
            $receiver = Critique::findOrFail($receiver);

            /**
             * chatId is the combination of recipient and sender id
             * ordered by id
             */
            $users = User::find([$critique->user->id, $receiver->user->id]);
            $chatId = $users->pluck('id')->sort()->implode('-');

            return response()->json([
                'chat_id' => $chatId,
                'critique' => $receiver,
                'messages' => $messages
            ]);
        } else {
            $messages = DB::select("SELECT MAX(tblm1.created_at) as timestamp, sender_id, (SELECT name FROM critiques WHERE critiques.id = sender_id) as sender_name, receiver_id, (SELECT name FROM critiques WHERE critiques.id = receiver_id) as receiver_name FROM messages as tblm1, critiques WHERE sender_id = 1 OR receiver_id = 1 AND NOT EXISTS( SELECT sender_id, receiver_id FROM messages as tblm2 WHERE tblm2.sender_id = tblm1.receiver_id AND tblm2.receiver_id = tblm1.sender_id ) GROUP BY sender_id, receiver_id ORDER BY tblm1.created_at DESC");

            $tailoredMessages = [];
            foreach ($messages as $message) {
                if ($message->sender_id == $critique->id) {
                    $critiqueId = $message->receiver_id;
                    $critiqueName = $message->receiver_name;
                } else {
                    $critiqueId = $message->sender_id;
                    $critiqueName = $message->sender_name;
                }

                $tailoredMessages[] = [
                    'critique_id' => $critiqueId,
                    'critique_name' => $critiqueName,
                    'timestamp' => $message->timestamp,
                ];
            }

            return response()->json($tailoredMessages);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CritiqueMessageStoreRequest $request, Critique $critique)
    {
        $message = $critique->sentMessages()->create($request->validated());

        /**
         * Broadcast event
         */
        $receiver = Critique::findOrFail($request->input('receiver_id'));
        broadcast(new \App\Events\MessageBroadcastEvent($critique->user, $receiver->user, $message))->toOthers();
        // event(new \App\Events\MessageBroadcastEvent($critique->user, $receiver->user, $message));

        return new MessageResource($message);
    }
}
