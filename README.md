AMQP Adapter for Lumen
======================
[![Latest Stable Version](https://poser.pugx.org/a-tarhanov/amqp-adapter/v/stable?format=flat-square)](https://packagist.org/packages/a-tarhanov/amqp-adapter)
[![Total Downloads](https://poser.pugx.org/a-tarhanov/amqp-adapter/downloads?format=flat-square)](https://packagist.org/packages/a-tarhanov/amqp-adapter)
[![License](https://poser.pugx.org/a-tarhanov/amqp-adapter/license?format=flat-square)](https://packagist.org/packages/a-tarhanov/amqp-adapter)

## Installation

You can install this package via composer using this command:

```
composer require a-tarhanov/amqp-adapter
```

Enable facades and add the service provider in your `bootstrap/app.php` file:

```
$app->withFacades();
```

```
$app->register(ATarhanov\AMQPAdapter\AMQPServiceProvider::class);
```

Don't forget to add variables to your `.env`:

```
AMQP_EXCHANGE=default
AMQP_QUEUE=default
AMQP_HOST=127.0.0.1
AMQP_PORT=5672
AMQP_USER=guest
AMQP_PASSWORD=guest
AMQP_PREFETCH_COUNT=10
```

## Usage

You can use the following commands:

- **AMQP Exchange Declare**

```
Usage:
  amqp:exchange-declare [options] [--] [<name>]

Arguments:
  name

Options:
      --type[=TYPE]                 [default: "direct"]
      --durable[=DURABLE]           [default: "1"]
      --auto-delete[=AUTO-DELETE]   [default: "0"]
```

- **AMQP Exchange Delete**

```
Usage:
  amqp:exchange-delete [options] [--] [<name>]

Arguments:
  name

Options:
      --unused[=UNUSED]   [default: "1"]
```

- **AMQP Queue Declare**

```
Usage:
  amqp:queue-declare [options] [--] [<name>]

Arguments:
  name

Options:
      --durable[=DURABLE]           [default: "1"]
      --auto-delete[=AUTO-DELETE]   [default: "0"]
```

- **AMQP Queue Delete**

```
Usage:
  amqp:queue-delete [options] [--] [<name>]

Arguments:
  name

Options:
      --unused[=UNUSED]   [default: "1"]
      --empty[=EMPTY]     [default: "1"]
```

- **AMQP Queue Bind**

```
Usage:
  amqp:queue-bind <routing-key> [<queue> [<exchange>]]

Arguments:
  routing-key
  queue
  exchange
```

- **AMQP Queue Unbind**

```
Usage:
  amqp:queue-unbind <routing-key> [<queue> [<exchange>]]

Arguments:
  routing-key
  queue
  exchange
```

- **AMQP Queue Purge**

```
Usage:
  amqp:queue-purge [<queue>]

Arguments:
  queue
```

- **AMQP Send Message**

```
Usage:
  amqp:send <message> <routing-key> [<exchange>]

Arguments:
  message
  routing-key
  exchange
```

- **AMQP Listen**

```
Usage:
  amqp:listen [options] [--] [<queue>]

Arguments:
  queue

Options:
      --no-ack[=NO-ACK]   [default: "0"]
```

You can use facade `AMQP` for send plain message, for example:

```
@param string $message
@param string $routing_key
@param string|null $exchange

AMQP::sendMessage($message, $routing_key, $exchange);
```

or for send array as json, for example:

```
@param array $message
@param string $routing_key
@param string|null $exchange

AMQP::sendJson($message, $routing_key, $exchange);
```

For listen event you can add to your `EventServiceProvider`:

```
Event::listen('event.some', fn($payload) => (new ExampleJob($payload))->handle());
```
