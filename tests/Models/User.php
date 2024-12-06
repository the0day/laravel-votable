<?php

namespace Zeroday\LaravelVotable\Tests\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model implements Authenticatable
{
    protected $table = 'users';
    protected $guarded = [];
    protected $fillable = ['id', 'name'];

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        $name = $this->getAuthIdentifierName();

        return $this->attributes[$name];
    }

    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    public function getAuthPassword()
    {
        return $this->attributes['password'];
    }

    public function getRememberToken(): string
    {
        return 'token';
    }

    public function setRememberToken($value): void {}

    public function getRememberTokenName(): string
    {
        return 'tokenName';
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function latestPost(): HasOne
    {
        return $this->hasOne(Post::class)->latest()->limit(1);
    }
}
