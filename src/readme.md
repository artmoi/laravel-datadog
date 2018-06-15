# Laravel Datadog

Everything you want for datadog logging in Laravel under a single package.


### Configuring

Add the following to `config/logging.php` in your project:

```php
    // ...
    
    'datadog' => [
        
        // You can provdie any key/value pairs normally accepted by `\DataDog\DogStatsD::__construct()` at this level.
        'app_key' => env('DATADOG_APP_KEY'),
        'api_key' => env('DATADOG_API_KEY'),
        
        // Optional: Minimum monolog logging level. If left unconfigured, it will self-configure based on `app.debug`
        // 'level' => Logger::DEBUG,
        
        // Optional: Tags to include when sending events to datadog.
        // 'tags' => [
        // ],
        
    ],
    
    'channels' => [

        // Add a new monolog-based channel using the handler from this package.
        'datadog' => [
            'driver' => 'monolog',
            'handler' => \ArtMoi\LaravelDatadog\DatadogMonologHandler::class,
            'formatter' => 'default',
        ],
    
    // ...
```

### Usage

After configuring the logger, any application logs should be automatically sent to datadog.

If you wish to trigger your own events via `DogStatsD`, simply request the type for dependency injection.

### Meta

There are two libraries already out there for logging in either laravel or with monolog.  This library combines the advantages 
of both while following a more composable architecture:

- Laravel package auto discovery.
- Monolog handler does not internally construct the type `\DataDog\DogStatsd`.
