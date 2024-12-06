<?php

namespace Zeroday\LaravelVotable\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Zeroday\LaravelVotable\Contracts\Votable;
use Zeroday\LaravelVotable\Enums\Reaction;
use Zeroday\LaravelVotable\Vote;

/**
 * @mixin Votable
 */
trait WithVote
{
    public function vote(Authenticatable $user, Reaction $reaction): bool
    {
        $voted = $this->votesByUserId($user->getAuthIdentifier());
        if (!$voted) {
            $this->votes()->create(['user_id' => $user->getAuthIdentifier(), 'type' => $reaction]);
            $this->increaseReactionCount($reaction);
            $this->save();

            return true;
        }

        if ($voted->type === $reaction) {
            return false;
        }

        $this->increaseReactionCount($reaction);
        $this->decreaseReactionCount($voted->type);
        $this->save();

        $voted->type = $reaction->value;
        $voted->save();

        return true;
    }

    public function unvote(Authenticatable $user): bool
    {
        $voted = $this->votesByUserId($user->getAuthIdentifier());
        if (!$voted) {
            return false;
        }

        $this->decreaseReactionCount($voted->type);

        return $voted->delete();
    }

    private function updateReactionCount(Reaction $reaction, int $value = 1): bool
    {
        $likeColumnName = getLikesColumnName();
        $dislikeColumnName = getDislikesColumnName();
        $column = $reaction == Reaction::LIKE ? $likeColumnName : $dislikeColumnName;
        if (!$this->hasAttribute($column)) {
            return false;
        }

        $this->{$column} += $value;

        return true;
    }

    private function decreaseReactionCount(Reaction $reaction): bool
    {
        return $this->updateReactionCount(reaction: $reaction, value: -1);
    }
    private function increaseReactionCount(Reaction $reaction): bool
    {
        return $this->updateReactionCount(reaction: $reaction);
    }

    public function votesByUserId(string|int $userId): ?Vote
    {
        return $this->votes()->where('user_id', '=', $userId)->lockForUpdate()->first();
    }

    public function votes(): MorphMany
    {
        return $this->morphMany(getVoteClass(), getMorphName());
    }
}
