<?php namespace ArtMoi\LaravelDatadog;

use DataDog\DogStatsd;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;


/**
 * Class DatadogMonologHandler
 *
 * @package ArtMoi\LaravelDatadog
 */
class DatadogMonologHandler extends AbstractProcessingHandler
{
    /** @var string[] */
    const MONOLOG_DOGSTATSD_MAP = [
        Logger::DEBUG => DogStatsDLogLevel::INFO,
        Logger::INFO => DogStatsDLogLevel::INFO,
        Logger::NOTICE => DogStatsDLogLevel::WARNING,
        Logger::WARNING => DogStatsDLogLevel::WARNING,
        Logger::ERROR => DogStatsDLogLevel::ERROR,
        Logger::ALERT => DogStatsDLogLevel::ERROR,
        Logger::CRITICAL => DogStatsDLogLevel::ERROR,
        Logger::EMERGENCY => DogStatsDLogLevel::ERROR,
    ];

    /**
     * @var \DataDog\DogStatsd
     */
    private $dogStatsd;

    /** @var LaravelDatadogService */
    private $laravelDatadogService;

    /** @var string[] */
    private $tags;

    /**
     * DatadogMonologHandler constructor.
     *
     * @param \DataDog\DogStatsd $dogStatsd
     * @param string[] $tags
     * @param int $level
     * @param bool $bubble
     */
    public function __construct(DogStatsd $dogStatsd, LaravelDatadogService $laravelDatadogService, $tags = [], $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->dogStatsd = $dogStatsd;
        $this->laravelDatadogService = $laravelDatadogService;
        $this->tags = $tags;
    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     *
     * @return void
     */
    protected function write(array $record)
    {
        $this->dogStatsd->event(
            $record['message'],
            [
                'text' => $record['formatted'],
                'aggregation_key' => $this->laravelDatadogService->getAggregationKey(),
                'tags' => array_replace_recursive(
                    [
                        'aggregation_key' => $this->laravelDatadogService->getAggregationKey(),
                    ],
                    $this->tags
                ),
                'alert_type' => isset(static::MONOLOG_DOGSTATSD_MAP[$record['level']])
                    ? static::MONOLOG_DOGSTATSD_MAP[$record['level']]
                    : 'info',
            ]
        );
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultFormatter()
    {
        return new JsonFormatter();
    }
}
