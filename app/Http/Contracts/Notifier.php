<?php

namespace App\Http\Contracts;

interface Notifier
{
    public function notify(string $receiver, string $message): void;
}
