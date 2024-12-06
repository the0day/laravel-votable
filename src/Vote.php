<?php

namespace Zeroday\LaravelVotable;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Zeroday\LaravelVotable\Enums\Reaction;

/**
 * @property int $id
 * @property int $votable_id
 * @property string $votable_type
 * @property string $user_id
 * @property Reaction $type
 * @property Carbon|null $created_at
 * @method static Builder|Vote newModelQuery()
 * @method static Builder|Vote newQuery()
 * @method static Builder|Vote query()
 * @method null|static first($columns = ['*'])
 */
class Vote extends Model
{
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        if (!isset($this->table)) {
            $this->setTable(config('votable.tables.votes', 'votes'));
        }

        $morphName = getMorphName();
        $this->fillable = ["{$morphName}_id", "{$morphName}_type", 'user_id', 'type'];

        parent::__construct($attributes);
    }

    protected function casts(): array
    {
        return [
            'type' => Reaction::class,
        ];
    }

    public function votable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
