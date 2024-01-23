# Queues

## Introduction

Laravel allows you to easily create `queued jobs` that may be processed in the background. By moving time intensive tasks to a queue, your application can respond to web requests with blazing speed and provide a better user experience to your customers.

Laravel queues provide a unified queueing API across a variety of different queue backends, such as Amazon SQS, Redis, or even a relational database

Configuration options for Laravel's queue services are stored in the `config/queue.php` configuration file.

## Connections vs Queues

In your `config/queue.php` configuration file, there is a `connections` configuration array. This option defines the connections to backend queue services such as Amazon SQS, Beanstalk, or Redis.

Note that **each connection configuration example in the queue `configuration` file contains a `queue` attribute**. This is the default queue that jobs will be dispatched to when they are sent to a given connection.

```php
'connections' => [

        'sync' => [
            'driver' => 'sync',
        ],

        'database' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'default',
            'retry_after' => 90,
            'after_commit' => false,
        ],

        'beanstalkd' => [
            'driver' => 'beanstalkd',
            'host' => 'localhost',
            'queue' => 'default',
            'retry_after' => 90,
            'block_for' => 0,
            'after_commit' => false,
        ],

        'sqs' => [
            'driver' => 'sqs',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
            'queue' => env('SQS_QUEUE', 'default'),
            'suffix' => env('SQS_SUFFIX'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'after_commit' => false,
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => env('REDIS_QUEUE', 'default'),
            'retry_after' => 90,
            'block_for' => null,
            'after_commit' => false,
        ],

    ],
```

For example, creating a connection named `create_booking` that uses the `rabbitmq` driver and a `create_booking` queue, you may do the following:

```php
'create_booking' => [
            'driver' => 'rabbitmq',
            'queue' => 'create_booking',
            'connection' => PhpAmqpLib\Connection\AMQPLazyConnection::class,

            'hosts' => [
                [
                    'host' => env('RABBITMQ_HOST', '127.0.0.1'),
                    'port' => env('RABBITMQ_VIBE_PORT', 5672),
                    'user' => env('RABBITMQ_USER', 'guest'),
                    'password' => env('RABBITMQ_PASSWORD', 'guest'),
                    'vhost' => env('RABBITMQ_VHOST', '/'),
                ],
            ],

            'options' => [
                'ssl_options' => [
                    'cafile' => env('RABBITMQ_SSL_CAFILE'),
                    'local_cert' => env('RABBITMQ_SSL_LOCALCERT'),
                    'local_key' => env('RABBITMQ_SSL_LOCALKEY'),
                    'verify_peer' => env('RABBITMQ_SSL_VERIFY_PEER', true),
                    'passphrase' => env('RABBITMQ_SSL_PASSPHRASE'),
                ],
                'queue' => [
                    'job' => App\Jobs\CreateBookingConsumerJob::class,
                    'exchange' => 'create_booking',
                    'exchange_type' => AMQPExchangeType::FANOUT,
                    'exchange_routing_key' => '',
                ],
                'heartbeat' => 10,
            ],

            /*
             * Set to "horizon" if you wish to use Laravel Horizon.
             */
            'worker' => env('RABBITMQ_WORKER', 'default'),
        ],
```

### RabbitMQ Explained

In Rabbitmq, there are 3 main components:

-   **Producer**: A producer is an application that sends messages. The producer doesn't know if a message is actually delivered to a queue. It just sends messages to a broker and forgets about it. The producer publish the message to an exchange with a `routing key`. The `routing key` is used to route the message to the specified queue.

-   **Exchange**: A message routing agent that **routes messages to queues**. It is responsible for receiving messages from producers and routing them to queues. It is not possible to send messages directly to a queue. Instead, producers send messages to an exchange and the exchange routes them to one or more queues.

-   **Queue**: A buffer that stores messages whose main purpose is to hold messages until they are consumed. When a message is published to an exchange, the exchange routes it to one or more queues. Queues are bound to exchanges with a `binding key`. The `binding key` and `routing key` is used to route the message to the specified queue.

-   [![RabbitMQ](../rabbitmq_direct_exchange_process_flow_diagram.png)](https://www.rabbitmq.com/tutorials/tutorial-four-php.html)
