<?php

namespace App\Providers;

use App\Infrastructure\FilesystemAdapter;
use App\Infrastructure\FilesystemAdapterInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FilesystemAdapterInterface::class, FilesystemAdapter::class);
    }
}
