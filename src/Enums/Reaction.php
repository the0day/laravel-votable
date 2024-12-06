<?php

namespace Zeroday\LaravelVotable\Enums;

enum Reaction: int
{
    case LIKE = 1;
    case DISLIKE = -1;
}
