<?php

namespace Zeroday\LaravelVotable\Tests;

use CreateVotesTable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Zeroday\LaravelVotable\LaravelVotableServiceProvider;
use Zeroday\LaravelVotable\Tests\Models\Post;
use Zeroday\LaravelVotable\Tests\Models\User;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();

        Factory::guessFactoryNamesUsing(
            fn(string $modelName) => 'Zeroday\\LaravelVotable\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelVotableServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);

        config()->set('auth.providers.users.model', User::class);
        config()->set('app.key', 'base64:' . base64_encode(Encrypter::generateKey(config()['app.cipher'])));
    }

    protected function createMainTables()
    {
        require_once __DIR__ . '/../database/migrations/create_votes_table.php.stub';
        (new CreateVotesTable)->up();
    }

    protected function setUpDatabase(): void
    {
        $this->createMainTables();
        $this->createTables('posts', 'users');
        $this->seedModels(Post::class, User::class);
    }

    protected function seedModels(...$modelClasses): void
    {
        collect($modelClasses)->each(function (string $modelClass) {
            foreach (range(1, 0) as $index) {
                $modelClass::create(['name' => "name {$index}"]);
            }
        });
    }

    protected function createTables(...$tableNames): void
    {
        collect($tableNames)->each(function (string $tableName) {
            Schema::create($tableName, function (Blueprint $table) use ($tableName) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('text')->nullable();
                $table->timestamps();
                $table->softDeletes();

                if ($tableName === 'posts') {
                    $table->integer('user_id')->unsigned()->nullable();
                    $table->unsignedInteger(getLikesColumnName())->default(0);
                    $table->unsignedInteger(getDislikesColumnName())->default(0);
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                }
            });
        });
    }

    public function createPost(): Post
    {
        $post = new Post;
        $post->name = fake()->word;
        $post->likes = 0;
        $post->dislikes = 0;
        $post->save();

        return $post;
    }

    public function createUser(): User
    {
        $user = new User;
        $user->name = fake()->userName;
        $user->save();

        return $user;
    }

    public function loginWithFakeUser(): User
    {
        $user = $this->createUser();
        $this->be($user);

        return $user;
    }
}
