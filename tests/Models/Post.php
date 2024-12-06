<?php

namespace Zeroday\LaravelVotable\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Zeroday\LaravelVotable\Contracts\Votable;
use Zeroday\LaravelVotable\Enums\Reaction;
use Zeroday\LaravelVotable\Traits\WithVote;

/**
 * @property int $likes
 * @property int $dislikes
 */
class Post extends Model implements Votable
{
    use WithVote;

    protected $table = 'posts';
    protected $guarded = [];

    public function getLikes(bool $fromRelation = false): int
    {
        if ($fromRelation || !$this->hasAttribute(getLikesColumnName())) {
            return $this->votes()->where('type', '=', Reaction::LIKE)->count();
        }

        return $this->{getLikesColumnName()};
    }

    public function getDislikes(bool $fromRelation = false): int
    {
        if (!$fromRelation || !$this->hasAttribute(getDislikesColumnName())) {
            return $this->votes()->where('type', '=', Reaction::DISLIKE)->count();
        }

        return $this->{getDislikesColumnName()};
    }
}
