<?php

namespace Zeroday\LaravelVotable\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Zeroday\LaravelVotable\Enums\Reaction;

interface Votable
{
    public function vote(Authenticatable $user, Reaction $reaction): bool;
    public function unvote(Authenticatable $user): bool;
    public function votes(): MorphMany;
    public function getLikes(bool $fromRelation = false): int;
    public function getDislikes(bool $fromRelation = false): int;
}
