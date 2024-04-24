<?php

namespace App\Helpers;

use DateTimeImmutable;
use Exception;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token;
use Pzamani\Auth\app\Models\Session;
use Pzamani\Auth\app\Models\User;
use Symfony\Component\HttpFoundation\Response;

class JwtHelper
{
    private static ?Configuration $config = null;
    private static string $secret = 'uXNyXbjSSDZi1pAE9e9twkDoo7oy4PSrxxSGbKqsoan2ehE13zXgbNffsvAC2vOIo0sJAzdRmjHAfqMxTks8eC';

    public static function createToken(string $sessionId, string $userId, int $validity): Token
    {
        self::getConfig();
        $now = new DateTimeImmutable();
        return self::$config->builder()
            ->issuedBy(request()->fullUrl())
            ->permittedFor(config('app.url'))
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+' . $validity . ' second' . ($validity != 1 ? 's' : '')))
            ->identifiedBy($sessionId)
            ->relatedTo($userId)
            ->getToken(self::$config->signer(), self::$config->signingKey());
    }

    private static function getConfig(): void
    {
        if (is_null(self::$config)) {
            self::$config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText(self::$secret));
        }
    }

    public static function getSession(string $token): ?Session
    {
        try {
            $token = self::parseToken($token);
            /** @var Session $session */
            $session = Session::query()->find($token->claims()->get('jti'));
            return $session;
        } catch (Exception) {
            return null;
        }
    }

    /**
     * @throws Exception
     */
    public static function parseToken(string $token): Token
    {
        if (trim($token) === '') {
            throw new Exception('JWT Not Given', Response::HTTP_FORBIDDEN);
        }
        if (!self::checkJWT($token)) {
            throw new Exception('Invalid Token', Response::HTTP_FORBIDDEN);
        }
        self::getConfig();
        try {
            $result = self::$config->parser()->parse($token);
        } catch (Exception) {
            throw new Exception('Invalid Token', Response::HTTP_FORBIDDEN);
        }
        return $result;
    }

    private static function checkJWT(string $token): bool
    {
        $parts = explode('.', $token);
        return count($parts) == 3 && isset(json_decode(base64_decode($parts[0]), true)['alg']);
    }

    public static function getUser(string $token): ?User
    {
        try {
            $token = self::parseToken($token);
            /** @var User $user */
            $user = User::query()->find($token->claims()->get('sub'));
            return $user;
        } catch (Exception) {
            return null;
        }
    }

    public static function checkToken(string $token): bool
    {
        try {
            $token = self::parseToken($token);
            return !$token->isExpired(new DateTimeImmutable());
        } catch (Exception) {
            return false;
        }
    }
}
