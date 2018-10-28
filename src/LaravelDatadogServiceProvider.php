<?php namespace ArtMoi\LaravelDatadog;

use DataDog\DogStatsd;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;


class LaravelDatadogServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {

            $this->app->bind(LaravelDatadogService::class);
        }
        else {

            $this->app->singleton(LaravelDatadogService::class);
        }

        $this->app->extend(\Illuminate\Bus\Dispatcher::class, function ($defaultDispatcher) {

            return new LaravelDatadogBusDispatcher($this->app, function ($connection = null) {

                return $this->app->make(QueueFactoryContract::class)->connection($connection);
            });
        });

        $this->app->singleton(DogStatsd::class, function () {

            $configured = array_only(config('logging.datadog', []), [
                'app_key',
                'api_key',
                'host',
                'port',
                'datadog_host',
                'curl_ssl_verify_host',
                'curl_ssl_verify_peer',
            ]);

            return new DogStatsd($configured);
        });

        $this->app->bind(DatadogMonologHandler::class, function (Application $app) {

            return new DatadogMonologHandler(
                $app->make(DogStatsd::class),
                $app->make(LaravelDatadogService::class),
                config('logging.datadog.tags', []),
                config('logging.datadog.level', config('app.debug')
                    ? Logger::DEBUG
                    : Logger::INFO
                )
            );
        });
    }

    public function boot(LaravelDatadogService $laravelDatadogService)
    {
        if (!$this->app->runningInConsole()) {

            $laravelDatadogService->initialize();
        }
    }
}
