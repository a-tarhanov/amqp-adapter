<?php

namespace ATarhanov\AMQPAdapter\Console\Commands;

use ATarhanov\AMQPAdapter\Facades\AMQP;
use Illuminate\Console\Command;

class AMQPQueueDeleteCommand extends Command
{
    protected $signature = 'amqp:queue-delete
                           {name?}
                           {--unused=1}
                           {--empty=1}';

    protected $description = 'AMQP Queue Delete';

    public function handle()
    {
        AMQP::queueDelete(
            $this->argument('name'),
            (bool) $this->option('unused'),
            (bool) $this->option('empty'),
        );

        $this->info('Queue deleted successfully.');
    }
}
