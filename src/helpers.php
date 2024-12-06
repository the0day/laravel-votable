<?php

use Zeroday\LaravelVotable\Vote;

if (!function_exists('getVotesTableName')) {
    function getVotesTableName(): string
    {
        return config('votable.tables.votes', 'votes');
    }
}

if (!function_exists('getMorphName')) {
    function getMorphName(): string
    {
        return config('votable.morph_name', 'votable');
    }
}

if (!function_exists('getVoteClass')) {
    function getVoteClass(): string
    {
        return config('votable.vote_class', Vote::class);
    }
}

if (!function_exists('getLikesColumnName')) {
    function getLikesColumnName(): string
    {
        return config('votable.columns.likes', 'likes');
    }
}

if (!function_exists('getDislikesColumnName')) {
    function getDislikesColumnName(): string
    {
        return config('votable.columns.dislikes', 'dislikes');
    }
}
