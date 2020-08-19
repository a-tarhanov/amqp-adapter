<?php

namespace ATarhanov\AMQPAdapter\Console\Commands;

use ATarhanov\AMQPAdapter\Facades\AMQP;
use Illuminate\Console\Command;

class AMQPExchangeDeclareCommand extends Command
{
    protected $signature = 'amqp:exchange-declare
                            {name?}
                            {--type=direct}
                            {--durable=1}
                            {--auto-delete=0}';

    protected $description = 'AMQP Exchange Declare';

    public function handle()
    {
        AMQP::exchangeDeclare(
            $this->argument('name'),
            $this->option('type'),
            (bool) $this->option('durable'),
            (bool) $this->option('auto-delete'),
        );

        $this->info('Exchange declared successfully.');
    }
}
