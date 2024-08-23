<?php

namespace App\Providers;
use App\Repositories\Interfaces\DocumentCategoryRepositoryInterface;
use App\Repositories\DocumentCategoryRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DocumentCategoryRepositoryInterface::class, DocumentCategoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if($this->app->environment('local')) {
            URL::forceScheme('https');
        }
    }
}
