<?php

namespace Zeroday\LaravelVotable\Tests;

use Zeroday\LaravelVotable\Contracts\Votable;
use Zeroday\LaravelVotable\Enums\Reaction;

class VoteTest extends TestCase
{
    public function test_user_can_vote()
    {
        $post = $this->createPost();

        $post->vote($user = $this->createUser(), Reaction::LIKE);
        $this->assertCount(1, $post->votes);
        $this->assertDatabaseHas(getVotesTableName(), [
            'user_id' => $user->id,
            'type'    => Reaction::LIKE,
        ]);
    }

    public function test_user_can_delete_vote()
    {
        $post = $this->createPost();
        $post->vote($user = $this->createUser(), Reaction::LIKE);
        $post->vote($this->createUser(), Reaction::LIKE);
        $post->unvote($user);

        $this->assertLikesCount(1, $post);
    }

    public function test_user_can_change_vote()
    {
        $post = $this->createPost();

        $post->vote($user = $this->createUser(), Reaction::DISLIKE);
        $this->assertCount(1, $post->votes);
        $this->assertDatabaseHas(getVotesTableName(), [
            'user_id' => $user->id,
            'type'    => Reaction::DISLIKE,
        ]);

        $post->vote($user, Reaction::LIKE);
        $this->assertCount(1, $post->votes);
        $this->assertDatabaseHas(getVotesTableName(), [
            'user_id' => $user->id,
            'type'    => Reaction::LIKE,
        ]);
    }

    public function test_multiple_users_can_vote_multiple_posts()
    {
        $post1 = $this->createPost();
        $post2 = $this->createPost();
        $post3 = $this->createPost();

        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $user3 = $this->createUser();
        $user4 = $this->createUser();
        $user5 = $this->createUser();

        $this->assertTrue($post1->vote($user1, Reaction::LIKE));
        $this->assertFalse($post1->vote($user1, Reaction::LIKE));
        $post2->vote($user1, Reaction::DISLIKE);
        $post3->vote($user1, Reaction::LIKE);

        $post1->vote($user2, Reaction::DISLIKE);
        $post2->vote($user2, Reaction::LIKE);
        $post3->vote($user2, Reaction::LIKE);

        $post1->vote($user3, Reaction::LIKE);
        $post2->vote($user3, Reaction::LIKE);

        $post1->vote($user4, Reaction::LIKE);

        $post1->vote($user5, Reaction::DISLIKE);
        $post2->vote($user5, Reaction::DISLIKE);
        $post3->vote($user5, Reaction::DISLIKE);

        $this->assertLikesCount(3, $post1);
        $this->assertDislikesCount(2, $post1);

        $this->assertLikesCount(2, $post2);
        $this->assertDislikesCount(2, $post2);

        $this->assertLikesCount(2, $post3);
        $this->assertDislikesCount(1, $post3);
    }

    private function assertLikesCount(int $expected, Votable $post): void
    {
        $this->assertEquals($expected, $post->likes);
        $this->assertEquals($expected, $post->getLikes());
        $this->assertEquals($expected, $post->getLikes(true));
    }

    private function assertDislikesCount(int $expected, Votable $post): void
    {
        $this->assertEquals($expected, $post->dislikes);
        $this->assertEquals($expected, $post->getDislikes());
        $this->assertEquals($expected, $post->getDislikes(true));
    }
}
