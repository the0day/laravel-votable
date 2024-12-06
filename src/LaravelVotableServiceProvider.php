<?php

namespace Zeroday\LaravelVotable;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelVotableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-votable')
            ->hasConfigFile()
            ->hasMigration('create_votes_table');
    }
}
