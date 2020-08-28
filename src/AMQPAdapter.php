<?php

namespace ATarhanov\AMQPAdapter;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Ramsey\Uuid\Uuid;

class AMQPAdapter
{
    /**
     * @var AMQPStreamConnection
     */
    private AMQPStreamConnection $connection;

    /**
     * @var AMQPChannel
     */
    private AMQPChannel $channel;

    /**
     * @var string
     */
    private $exchange;

    /**
     * @var string
     */
    private $queue;

    /**
     * @var integer
     */
    private $prefetch_count;

    /**
     * AMQP constructor.
     * @param string $host
     * @param string $port
     * @param string $user
     * @param string $password
     * @param string $exchange
     * @param string $queue
     * @param integer $prefetch_count
     */
    public function __construct(
        $host,
        $port,
        $user,
        $password,
        $exchange = 'default',
        $queue = 'default',
        $prefetch_count = 10
    ) {
        $this->connection = new AMQPStreamConnection($host, $port, $user, $password);

        $this->channel = $this->connection->channel();

        $this->exchange = $exchange;

        $this->queue = $queue;

        $this->prefetch_count = $prefetch_count;
    }

    /**
     * @return AMQPStreamConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return AMQPChannel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param string|null $name
     * @param string $type
     * @param bool $durable
     * @param bool $auto_delete
     */
    public function exchangeDeclare($name = null, $type = 'direct', $durable = true, $auto_delete = false)
    {
        $name ??= $this->exchange;

        $this->channel->exchange_declare($name, $type, false, $durable, $auto_delete);
    }

    /**
     * @param string|null $name
     * @param bool $unused
     */
    public function exchangeDelete($name = null, $unused = true)
    {
        $name ??= $this->exchange;

        $this->channel->exchange_delete($name, $unused);
    }

    /**
     * @param string|null $name
     * @param bool $durable
     * @param bool $auto_delete
     */
    public function queueDeclare($name = null, $durable = true, $auto_delete = false)
    {
        $name ??= $this->queue;

        $this->channel->queue_declare($name, false, $durable, false, $auto_delete);
    }

    /**
     * @param string|null $name
     * @param bool $unused
     * @param bool $empty
     */
    public function queueDelete($name = null, $unused = true, $empty = true)
    {
        $name ??= $this->queue;

        $this->channel->queue_delete($name, $unused, $empty);
    }

    /**
     * @param string|null $queue
     * @param string|null $exchange
     * @param string $routing_key
     */
    public function queueBind($queue = null, $exchange = null, $routing_key = '')
    {
        $queue ??= $this->queue;
        $exchange ??= $this->exchange;

        $this->channel->queue_bind($queue, $exchange, $routing_key);
    }

    /**
     * @param string|null $queue
     * @param string|null $exchange
     * @param string $routing_key
     */
    public function queueUnbind($queue = null, $exchange = null, $routing_key = '')
    {
        $queue ??= $this->queue;
        $exchange ??= $this->exchange;

        $this->channel->queue_unbind($queue, $exchange, $routing_key);
    }

    /**
     * @param string|null $queue
     */
    public function queuePurge($queue = null)
    {
        $queue ??= $this->queue;

        $this->channel->queue_purge($queue);
    }

    /**
     * @param AMQPMessage $msg
     * @param string|null $exchange
     * @param string $routing_key
     */
    public function basicPublish($msg, $exchange = null, $routing_key = '')
    {
        $exchange ??= $this->exchange;

        $this->channel->basic_publish($msg, $exchange, $routing_key);
    }

    /**
     * @param string|null $queue
     * @param bool $no_ack
     * @param callable|null $callback
     */
    public function basicConsume($queue = null, $no_ack = false, $callback = null)
    {
        $queue ??= $this->queue;

        $this->channel->basic_consume($queue, '', false, $no_ack, false, false, $callback);
    }

    /**
     * @param integer $count
     */
    public function basicQos($count)
    {
        $this->channel->basic_qos(null, $count, null);
    }

    /**
     * @param string $msg
     * @param bool $persistent
     * @param array $additional_options
     * @return AMQPMessage
     */
    public function createMessage($msg, $persistent = true, $additional_options = [])
    {
        return new AMQPMessage($msg, array_merge([
            'delivery_mode' => $persistent ?
                AMQPMessage::DELIVERY_MODE_PERSISTENT :
                AMQPMessage::DELIVERY_MODE_NON_PERSISTENT,
            'message_id' => (string)Uuid::uuid4(),
            'timestamp' => time(),
        ], $additional_options));
    }

    /**
     * @param string $msg
     * @param string $routing_key
     * @param string|null $exchange
     * @param bool $persistent
     */
    public function sendMessage($msg, $routing_key, $exchange = null, $persistent = true)
    {
        $this->basicPublish($this->createMessage($msg, $persistent), $exchange, $routing_key);
    }

    /**
     * @param array $msg
     * @param string $routing_key
     * @param string|null $exchange
     * @param bool $persistent
     */
    public function sendJson($msg, $routing_key, $exchange = null, $persistent = true)
    {
        $this->basicPublish($this->createMessage(json_encode($msg), $persistent, [
            'content_type' => 'application/json',
        ]), $exchange, $routing_key);
    }

    /**
     * @param string|null $queue
     * @param bool $no_ack
     * @param $callback
     * @throws \ErrorException
     */
    public function listen($queue = null, $no_ack = false, $callback = null)
    {
        $this->basicQos($this->prefetch_count);
        $this->basicConsume($queue, $no_ack, $callback);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    /**
     * @throws \Exception
     */
    public function close()
    {
        $this->connection->close();
        $this->channel->close();
    }
}
