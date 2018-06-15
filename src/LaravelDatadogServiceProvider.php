<?php namespace ArtMoi\LaravelDatadog;

use DataDog\DogStatsd;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;


class LaravelDatadogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(DogStatsd::class, function () {

            $defaults = [
                'app_key' => env('DATADOG_APP_KEY'),
                'api_key' => env('DATADOG_API_KEY'),
            ];

            $configured = array_only(config('logging.datadog', []), [
                'app_key',
                'api_key',
                'host',
                'port',
                'datadog_host',
                'curl_ssl_verify_host',
                'curl_ssl_verify_peer',
            ]);

            return new DogStatsd(array_merge($defaults, $configured));
        });

        $this->app->singleton(DatadogMonologHandler::class, function (Application $app) {

            return new DatadogMonologHandler(
                $app->make(DogStatsd::class),
                config('logging.datadog.tags', []),
                config('logging.datadog.level', config('app.debug')
                    ? Logger::DEBUG
                    : Logger::INFO
                )
            );
        });
    }
}
