<?php

namespace ATarhanov\AMQPAdapter\Console\Commands;

use ATarhanov\AMQPAdapter\Facades\AMQP;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;

class AMQPListenCommand extends Command
{
    protected $signature = 'amqp:listen
                            {queue?}
                            {--no-ack=0}';

    protected $description = "AMQP Listen";

    public function handle()
    {
        $this->info('[*] Waiting for messages. To exit press CTRL+C');

        $queue = $this->argument('queue');

        $no_ack = (bool)$this->option('no-ack');

        $callback = function ($msg) use ($no_ack) {
            $properties = $msg->get_properties();
            $event = $msg->delivery_info['routing_key'];
            $channel = $msg->delivery_info['channel'];
            $delivery_tag = $msg->delivery_info['delivery_tag'];
            $body = $msg->body;

            if (data_get($properties, 'content_type') === 'application/json') {
                $body = json_decode($body, true);
            }

            try {
                Event::dispatch($event, $body);

                if (!$no_ack) {
                    $channel->basic_ack($delivery_tag);
                }
            } catch (\Exception $e) {
                if (!$no_ack) {
                    $channel->basic_cancel($delivery_tag);
                }
            }
        };

        AMQP::listen($queue, $no_ack, $callback);
    }
}
