<?php

namespace App\Http\Contracts;

interface PaymentGateway
{
    public function requestAuthorityCode(string $paymentId, int $amount): string;

    public function verify(string $authorityCode): ?string;

    public function settle(string $referenceCode): bool;
}
