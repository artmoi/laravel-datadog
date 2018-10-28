<?php namespace ArtMoi\LaravelDatadog;

use Illuminate\Bus\Dispatcher;


class LaravelDatadogBusDispatcher extends Dispatcher
{
    /**
     * Dispatch a command to its appropriate handler.
     *
     * @param  mixed  $command
     * @return mixed
     */
    public function dispatch($command)
    {
        /** @var LaravelDatadogService $laravelDatadogService */
        $laravelDatadogService = $this->container->make(LaravelDatadogService::class);

        $command->laravelDatadogAggregationKey = $laravelDatadogService->getAggregationKey();

        return parent::dispatch($command);
    }

    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * @param  mixed  $command
     * @param  mixed  $handler
     * @return mixed
     */
    public function dispatchNow($command, $handler = null)
    {
        /** @var LaravelDatadogService $laravelDatadogService */
        $laravelDatadogService = $this->container->make(LaravelDatadogService::class);

        $laravelDatadogService->initialize(object_get($command, 'laravelDatadogAggregationKey'));
        unset($command->laravelDatadogAggregationKey);

        return parent::dispatchNow($command, $handler);
    }
}
