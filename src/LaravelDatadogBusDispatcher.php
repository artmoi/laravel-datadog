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
        parent::dispatch($command);
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
        parent::dispatchNow($command, $handler);
    }
}
