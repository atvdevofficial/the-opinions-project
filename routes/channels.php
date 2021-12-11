<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('test.{userId}', function($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('chat.{chatId}', function($user, $chatId) {

    /**
     * Split string in to an array
     */
    $chatIds = explode('-', $chatId);

    /**
     * Verify if user owns the chat
     */
    if (in_array($user->id, $chatIds)) {
        return true;
    }

    return false;
});
