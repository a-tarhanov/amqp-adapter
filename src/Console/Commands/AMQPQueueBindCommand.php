<?php

namespace ATarhanov\AMQPAdapter\Console\Commands;

use ATarhanov\AMQPAdapter\Facades\AMQP;
use Illuminate\Console\Command;

class AMQPQueueBindCommand extends Command
{
    protected $signature = 'amqp:queue-bind   
                            {routing-key}               
                            {queue?}
                            {exchange?}';

    protected $description = 'AMQP Queue Bind';

    public function handle()
    {
        AMQP::queueBind(
            $this->argument('queue'),
            $this->argument('exchange'),
            (string) $this->argument('routing-key'),
        );

        $this->info('Queue binded successfully.');
    }
}
