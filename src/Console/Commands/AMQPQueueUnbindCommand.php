<?php

namespace ATarhanov\AMQPAdapter\Console\Commands;

use ATarhanov\AMQPAdapter\Facades\AMQP;
use Illuminate\Console\Command;

class AMQPQueueUnbindCommand extends Command
{
    protected $signature = 'amqp:queue-unbind  
                            {routing-key}                        
                            {queue?}
                            {exchange?}';

    protected $description = 'AMQP Queue Unbind';

    public function handle()
    {
        AMQP::queueUnbind(
            $this->argument('queue'),
            $this->argument('exchange'),
            (string) $this->argument('routing-key'),
        );

        $this->info('Queue unbinded successfully.');
    }
}
