<?php

namespace App\Providers;

use App\Repository\Eloquent\ArticleRepository; 
use App\Repository\ArticleRepositoryInterface; 
use App\Repository\Eloquent\TagRepository; 
use App\Repository\TagRepositoryInterface; 
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
    }
}
