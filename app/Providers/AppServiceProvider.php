<?php

namespace App\Providers;

use App\Infrastructure\FilesystemAdapter;
use App\Infrastructure\FilesystemAdapterInterface;
use App\Service\Schedule\Dictionary\ScheduleDictionary;
use App\Service\Schedule\Dictionary\ScheduleDictionaryFactory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(FilesystemAdapterInterface::class, FilesystemAdapter::class);
        $this->app->bind(ScheduleDictionary::class, function($app, array $parameters) {
            /** @var ScheduleDictionaryFactory $factory */
            $factory = $app->make(ScheduleDictionaryFactory::class);
            return $factory->create();
        });
    }
}
