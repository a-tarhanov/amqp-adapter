<?php

namespace ATarhanov\AMQPAdapter\Console\Commands;

use ATarhanov\AMQPAdapter\Facades\AMQP;
use Illuminate\Console\Command;

class AMQPQueueDeclareCommand extends Command
{
    protected $signature = 'amqp:queue-declare
                            {name?}
                            {--durable=1}
                            {--auto-delete=0}';

    protected $description = 'AMQP Queue Declare';

    public function handle()
    {
        AMQP::queueDeclare(
            $this->argument('name'),
            (bool) $this->option('durable'),
            (bool) $this->option('auto-delete'),
        );

        $this->info('Queue declared successfully.');
    }
}
