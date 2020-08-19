<?php

namespace ATarhanov\AMQPAdapter;

use Illuminate\Support\ServiceProvider;

class AMQPServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/amqp.php', 'amqp'
        );

        $this->app->singleton('amqp', function ($app) {
            ['host' => $host, 'port' => $port, 'user' => $user, 'password' => $password] = config('amqp.connection');
            ['exchange' => $exchange, 'queue' => $queue] = config('amqp.default');
            ['prefetch_count' => $prefetch_count] = config('amqp.options');

            return new AMQPAdapter($host, $port, $user, $password, $exchange, $queue, $prefetch_count);
        });

        $this->commands([
            Console\Commands\AMQPExchangeDeclareCommand::class,
            Console\Commands\AMQPExchangeDeleteCommand::class,
            Console\Commands\AMQPListenCommand::class,
            Console\Commands\AMQPQueueBindCommand::class,
            Console\Commands\AMQPQueueDeclareCommand::class,
            Console\Commands\AMQPQueueDeleteCommand::class,
            Console\Commands\AMQPQueuePurgeCommand::class,
            Console\Commands\AMQPQueueUnbindCommand::class,
            Console\Commands\AMQPSendCommand::class,
        ]);
    }
}
