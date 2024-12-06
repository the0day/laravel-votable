<?php

use Zeroday\LaravelVotable\Vote;

return [
    'tables' => [
        'votes' => 'votes',
    ],
    'columns' => [
        'likes'    => 'likes',
        'dislikes' => 'dislikes',
    ],
    'vote_class'            => Vote::class,
    'morph_name'            => 'votable',
    'user_id_is_uuid'       => true,
    'morph_id_type_is_uuid' => false,
];
