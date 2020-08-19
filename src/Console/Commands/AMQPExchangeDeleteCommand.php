<?php

namespace ATarhanov\AMQPAdapter\Console\Commands;

use ATarhanov\AMQPAdapter\Facades\AMQP;
use Illuminate\Console\Command;

class AMQPExchangeDeleteCommand extends Command
{
    protected $signature = 'amqp:exchange-delete
                            {name?}
                            {--unused=1}';

    protected $description = 'AMQP Exchange Declare';

    public function handle()
    {
        AMQP::exchangeDelete(
            $this->argument('name'),
            (bool) $this->option('unused'),
        );

        $this->info('Exchange deleted successfully.');
    }
}
