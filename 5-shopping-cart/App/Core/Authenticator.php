<?php

namespace App\Core;

use App\Models\User;

class Authenticator
{
    public function attempt($phone, $password, $remember = false): bool
    {
        $user = (new User)->byPhone($phone);

        if ($user && password_verify($password, $user->password)) {
            $this->login($user);

            if ($remember) {
                $this->remember($user);
            }

            return true;
        }

        return false;
    }

    public function check(): bool
    {
        if (Session::has('user')) {
            return true;
        }

        if (Cookie::has('remember')) {
            $this->loginByCookie();
        }

        return false;
    }

    public function username(): string|null
    {
        return $this->check() ? Session::get('user')['phone'] : null;
    }

    public function user(): User
    {
        return (new User)->byPhone($this->username());
    }


    public function login($user): void
    {
        Session::put('user', [
            'phone' => $user->phone
        ]);

        session_regenerate_id(true);
    }

    public function logout(): void
    {
        $this->user()->deleteToken();
        Session::destroy();
        Cookie::delete('remember');
    }

    private function setRememberCookie($value): void
    {
        $expires_at = time() + 14 * 24 * 60 * 60;

        Cookie::set('remember', $value, $expires_at);
    }

    /**
     * @param User $user
     * @return void
     */
    public function remember(User $user): void
    {
        $token = generateRandom(32);
        $this->setRememberCookie($token);
        $user->setRememberToken($token);
    }

    private function loginByCookie(): void
    {
        $user = (new User)->byToken(Cookie::get('remember'));

        if ($user) {
            $this->login($user);
            return;
        }

        cookie::delete('remember');
    }
}
