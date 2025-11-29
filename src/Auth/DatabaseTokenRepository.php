<?php

namespace NovinVision\MultiStepLogin\Auth;

use Illuminate\Auth\Passwords\DatabaseTokenRepository as DatabaseTokenRepositoryBase;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Carbon;

class DatabaseTokenRepository extends DatabaseTokenRepositoryBase
{
    public function __construct(ConnectionInterface $connection, HasherContract $hasher, string $table, string $hashKey, int $expires = 3600, int $throttle = 60)
    {
        $expires = config('auth.password_timeout');
        parent::__construct($connection, $hasher, $table, $hashKey, $expires, $throttle);
    }

    public function create(CanResetPasswordContract $user): string
    {
        $this->deleteExisting($user);
        $token = $this->createNewToken();

        $this->getTable()->insert($this->getPayload($user, $token));
        return $token;
    }

    protected function deleteExisting(CanResetPasswordContract $user): int
    {
        return $this->getTable()
            ->where("user_type", get_class($user))
            ->where("user_id", $user->getKey())
            ->delete();
    }

    protected function getPayload($user, $token): array
    {
        $email = $user->getEmailForPasswordReset();
        return [
            "user_type" => get_class($user),
            "user_id" => $user->getKey(),
            "email" => $email,
            "token" => $this->hasher->make($token),
            "created_at" => now(),
        ];
    }

    public function exists(CanResetPasswordContract $user, $token): bool
    {
        $record = (array) $this->getTable()
            ->where("user_type", get_class($user))
            ->where("user_id", $user->getKey())
            ->first();

        return $record &&
            ! $this->tokenExpired($record['created_at']) &&
            $this->hasher->check($token, $record['token']);
    }

    public function recentlyCreatedToken(CanResetPasswordContract $user): bool
    {
        $record = (array)$this->getTable()
            ->where("user_type", get_class($user))
            ->where("user_id", $user->getKey())
            ->first();

        return $record && $this->tokenRecentlyCreated($record['created_at']);
    }
}
