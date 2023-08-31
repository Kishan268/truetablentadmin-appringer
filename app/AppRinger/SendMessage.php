<?php

namespace App\AppRinger;

use Ably;

class SendMessage
{

    public static function sendMessageEvent($channel_name, $message)
    {
        $client  = new Ably\AblyRest(env('ABLY_KEY'));
        $channel = $client->channels->get($channel_name);
        $channel->publish('message', $message);
    }

    public static function sendChatEvent($channel_name, $message)
    {
        $client  = new Ably\AblyRest(env('ABLY_KEY'));
        $channel = $client->channels->get($channel_name);
        $channel->publish('message', $message);
    }
}
