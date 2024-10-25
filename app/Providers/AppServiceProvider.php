<?php

namespace App\Providers;
use App\Repositories\Interfaces\DocumentCategoryRepositoryInterface;
use App\Repositories\DocumentCategoryRepository;
use Illuminate\Support\Facades\DB;
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
        DB::listen(function ($query) {
            \Log::info("\n\n");

            // Replace bindings into the SQL query
            $fullSql = $query->sql;

            foreach ($query->bindings as $binding) {
                // If the binding is a string, wrap it with quotes
                if (is_string($binding)) {
                    $binding = "'{$binding}'";
                } elseif ($binding === null) {
                    $binding = 'NULL';
                }

                // Replace the first occurrence of '?' with the binding value
                $fullSql = preg_replace('/\?/', $binding, $fullSql, 1);
            }

            \Log::info("Query: " . $fullSql);
            \Log::info('Time: ' . $query->time . 'ms');
        });
        // if($this->app->environment('local')) {
        //     URL::forceScheme('https');
        // }
    }
}
