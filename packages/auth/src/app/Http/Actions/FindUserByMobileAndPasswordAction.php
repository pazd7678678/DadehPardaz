<?php

namespace Pzamani\Auth\app\Http\Actions;

use Exception;
use Illuminate\Support\Facades\Hash;
use Pzamani\Auth\app\Models\User;

class FindUserByMobileAndPasswordAction
{
    /**
     * @throws Exception
     */
    public function run(string $mobile, string $password): User
    {
        /** @var User $user */
        $mobile = substr(preg_replace('#[^0-9]#', '', $mobile), -9);
        if (!$user = User::query()->where('mobile', $mobile)->first()) {
            throw new Exception('Invalid credentials');
        }
        if (!Hash::check($password, $user->password)) {
            throw new Exception('Invalid credentials');
        }
        return $user;
    }
}
