<?php

use App\Models\SharedTrip;
use App\Models\Trip;
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

Broadcast::channel('App.Models.User.{user_id}', function ($user, $user_id) {
    return (int) $user->user_id === (int) $user_id;
});

    Broadcast::channel('public', function () {
    return true;
});

Broadcast::channel('private.{user_id}', function ($user, $user_id) {
    return (int) $user->user_id === (int) $user_id;
});

Broadcast::channel('privateTrip.{trip_id}', function ($trip_id) {

 return $trip_id;

});







Broadcast::channel('presence.{groupId}', function ($user,int $groupId) {

    if ($user->canJoinGroup($groupId)) {
        return ['id' => $user->user_id, 'name' => $user->name];
    }
});
