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


    //    $trip = Trip::find($trip_id)->first();
//
//    // Sprawdzamy, czy użytkownik jest właścicielem wycieczki
//    if ( auth()->$user->user_id === $trip->owner_id) {
//        return true;
//    }
//    // Sprawdzamy, czy użytkownik ma udział w wycieczce na podstawie SharedTrip
//    $sharedTrip = SharedTrip::where('trip_id', $trip_id)->where('user_id', auth()->$user->user_id)->first();
//    if ($sharedTrip && $sharedTrip->trip_id === $trip->trip_id) {
//        return true;
//    }
});

Broadcast::channel('presence.{groupId}', function ($user,int $groupId) {

    if ($user->canJoinGroup($groupId)) {
        return ['id' => $user->user_id, 'name' => $user->name];
    }
});
