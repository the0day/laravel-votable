<?php

namespace Zeroday\LaravelVotable\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Zeroday\LaravelVotable\Contracts\Votable;
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
}
