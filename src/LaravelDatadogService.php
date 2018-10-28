<?php namespace ArtMoi\LaravelDatadog;

use Ramsey\Uuid\Uuid;


class LaravelDatadogService
{
    /** @var string */
    private $aggregationKey;

    /**
     * @param string|null $aggregationKey
     * @throws \Exception
     */
    public function initialize($aggregationKey = null)
    {
        $this->aggregationKey = $aggregationKey ?: Uuid::uuid4()->toString();
    }

    /**
     * @return string
     */
    public function getAggregationKey()
    {
        return $this->aggregationKey;
    }
}
