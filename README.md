# DiscordPHP-DI

## Description
The package allows you to integrate `DI Container` with your `Discord\Discord` client.
Your `EventHandler` will be automatically built in runtime whenever `Event` happens.

## Installation
This package is available for installation via `composer`:

`composer require freezemage0/discordphp-di`

## Usage
Let's say that you have an `EventHandler` class 
```php
<?php


class EventHandler {
    private DatabaseDriver $driver;
    private Cache $cache;
    
    public function __construct(DatabaseDriver $driver, Cache $cache)
    {
        $this->driver = $driver;
        $this->cache = $cache;
    }
    
    public function handleOnGuildJoin(): void
    {
        // your code ...     
    }
}
```
Now you can register your `EventHandler` as a handle for Discord event:
```php
<?php

$handler = new EventHandler(
    new DatabaseDriver(),
    new Cache()
);

$discord = new \Discord\Discord();
$discord->on('GUILD_CREATE', [$handler, 'handleOnGuildJoin']);
$discord->run();
```

There are two major flaws in that approach:
1. You MUST create all dependencies before creating `EventHandler` instance;
2. Your `EventHandler` may actually never get called in a runtime but it allocates the memory anyway.

The `freezemage0/discordphp-di` allows you to eliminate those flaws, see below:

```php
<?php

// Take note that in this example we will use `php-di/php-di` package.
$container = \DI\ContainerBuilder::buildDevContainer();
$builder = new \Freezemage\Discord\Builder($container);

$discord = $builder->build(); // This will instantiate Discord\Discord class!
$discord->on('GUILD_CREATE', [EventHandler::class, 'handleOnGuildJoin']);
$discord->run();
```
Now, your `EventHandler` will be instantiated (and get its dependencies resolved) only when the event is actually fired.

## Integrating into already existing instance of `Discord\Discord`

In case you extended the `Discord\Discord` class in order to add your own functionality to it, you can use `$builder->wrap($myDiscordInstance);` to inject the container **and** preserve custom-defined behaviour.
```php
$discord = new \MyProject\MyDiscord(); // extends \Discord\Discord;
$builder = new \Freezemage\Discord\Builder($container);
$discord = $builder->wrap($discord); // now $discord has Container injected with original class preserved. 
```
