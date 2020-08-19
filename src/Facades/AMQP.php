<?php

namespace ATarhanov\AMQPAdapter\Facades;

use Illuminate\Support\Facades\Facade;

class AMQP extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'amqp';
    }
}
