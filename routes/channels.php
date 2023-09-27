<?php

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

Broadcast::channel('App.Models.User.{id}', function ($user, $user_id) {
    return (int) $user->user_id === (int) $user_id;
});

Broadcast::channel('public', function () {
    return true;
});

Broadcast::channel('private.{id}', function ($user, $user_id) {
    return (int) $user->user_id === (int) $user_id;
});

Broadcast::channel('presence.{groupId}', function ($user,int $groupId) {

    if ($user->canJoinGroup($groupId)) {
        return ['id' => $user->user_id, 'name' => $user->name];
    }
});
