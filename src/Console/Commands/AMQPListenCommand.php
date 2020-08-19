<?php

namespace ATarhanov\AMQPAdapter\Console\Commands;

use ATarhanov\AMQPAdapter\Facades\AMQP;
use Illuminate\Console\Command;

class AMQPListenCommand extends Command
{
    protected $signature = 'amqp:listen
                            {queue?}
                            {--no-ack=0}';

    protected $description = "AMQP Listen";

    public function handle()
    {
        $this->info('[*] Waiting for messages. To exit press CTRL+C');

        AMQP::listen(
            $this->argument('queue'),
            (bool) $this->option('no-ack'),
        );
    }
}
