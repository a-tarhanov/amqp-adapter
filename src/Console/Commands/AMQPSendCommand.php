<?php

namespace ATarhanov\AMQPAdapter\Console\Commands;

use ATarhanov\AMQPAdapter\Facades\AMQP;
use Illuminate\Console\Command;

class AMQPSendCommand extends Command
{
    protected $signature = 'amqp:send
                            {message}
                            {routing-key}
                            {exchange?}';

    protected $description = 'AMQP Send Message';

    public function handle()
    {
        AMQP::sendMessage(
            $this->argument('message'),
            $this->argument('routing-key'),
            $this->argument('exchange'),
        );

        $this->info('Message sent successfully.');
    }
}
