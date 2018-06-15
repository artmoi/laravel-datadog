<?php namespace ArtMoi\LaravelDatadog;

use MyCLabs\Enum\Enum;


/**
 * Class DogStatsDLogLevel
 *
 * @package ArtMoi\LaravelDatadog
 *
 * @method static \ArtMoi\LaravelDatadog\DogStatsDLogLevel INFO()
 * @method static \ArtMoi\LaravelDatadog\DogStatsDLogLevel WARNING()
 * @method static \ArtMoi\LaravelDatadog\DogStatsDLogLevel ERROR()
 */
class DogStatsDLogLevel extends Enum
{
    const INFO = 'info';
    const WARNING = 'warning';
    const ERROR = 'error';
}
