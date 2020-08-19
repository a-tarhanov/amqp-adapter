<?php

namespace ATarhanov\AMQPAdapter\Console\Commands;

use ATarhanov\AMQPAdapter\Facades\AMQP;
use Illuminate\Console\Command;

class AMQPQueuePurgeCommand extends Command
{
    protected $signature = 'amqp:queue-purge                          
                            {queue?}';

    protected $description = 'AMQP Queue Purge';

    public function handle()
    {
        AMQP::queuePurge(
            $this->argument('queue'),
        );

        $this->info('Queue purged successfully.');
    }
}
