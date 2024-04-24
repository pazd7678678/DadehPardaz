<?php

namespace App\Http\Services;

use App\Http\Contracts\Notifier;

class NotifyWithEmail implements Notifier
{

    public function notify(string $receiver, string $message): void
    {
        // TODO: Implement notify() method to send message via Email.
    }
}
