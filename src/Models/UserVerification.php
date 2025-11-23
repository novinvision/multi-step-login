<?php

namespace NovinVision\MultiStepLogin\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property Carbon $expired_at
 * @property string $field
 */
class UserVerification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_type',
        'user_id',
        'field',
        'code',
        'expired_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'user_type',
        'user_id',
        'field',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expired_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->expired_at) {
                $expiration = config("multi-step-login.verify_expiration.{$model->field}", '5 minutes');
                $model->expired_at = Carbon::now()->modify("+ $expiration");
            }
        });
    }

    public static function make(Model $user, string $field = 'mobile'): self
    {
        self::query()
            ->where('field', $field)
            ->whereMorphedTo('user', $user)
            ->each(function ($verification) {
                $verification->delete();
            });

        $codeConfig = config("multi-step-login.verify_code_len.$field");
        $codeLen = $codeConfig[$field] ?? $codeConfig;
        $codeLen = is_numeric($codeLen) ? $codeLen : 5;
        return static::create([
            'user_type' => get_class($user),
            'user_id' => $user->getKey(),
            'field' => $field,
            'code' => rand(str_repeat('1', $codeLen), str_repeat('9', $codeLen)),
        ]);
    }

    public static function verify(Model $user, string $code, string $field = 'mobile'):? self
    {
        return static::query()
            ->whereMorphedTo('user', $user)
            ->where('field', $field)
            ->where('code', $code)
            ->where('expired_at', '>=', Carbon::now())
            ->first();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('user');
    }

    public function isExpired(): bool
    {
        return $this->expired_at instanceof Carbon && $this->expired_at->isPast();
    }
}
