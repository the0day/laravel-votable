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
            ->hasViews()
            ->hasMigration('create_laravel_votable_table');
    }
}
