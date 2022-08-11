<?php


namespace Freezemage\Discord;

use Closure;
use Discord\Discord;
use Discord\Factory\Factory;
use Discord\Helpers\RegisteredCommand;
use Discord\Http\Http;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Part;
use Discord\Parts\User\Activity;
use Discord\Voice\VoiceClient;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\Message;
use React\EventLoop\LoopInterface;
use React\Promise\ExtendedPromiseInterface;


class Decorator extends Discord
{
    protected Discord $discord;
    
    protected ContainerInterface $container;

    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct(Discord $discord)
    {
        $this->discord = $discord;
    }

    protected function handleVoiceServerUpdate(object $data): void
    {
        $this->discord->handleVoiceServerUpdate($data); 
    }

    protected function handleResume(object $data): void
    {
        $this->discord->handleResume($data); 
    }

    protected function handleReady(object $data)
    {
        return $this->discord->handleReady($data); 
    }

    protected function handleGuildMembersChunk(object $data): void
    {
        $this->discord->handleGuildMembersChunk($data); 
    }

    protected function handleVoiceStateUpdate(object $data): void
    {
        $this->discord->handleVoiceStateUpdate($data); 
    }

    public function handleWsConnection(WebSocket $ws): void
    {
        $this->discord->handleWsConnection($ws); 
    }

    public function handleWsMessage(Message $message): void
    {
        $this->discord->handleWsMessage($message); 
    }

    protected function processWsMessage(string $data): void
    {
        $this->discord->processWsMessage($data); 
    }

    public function handleWsClose(int $op, string $reason): void
    {
        $this->discord->handleWsClose($op, $reason); 
    }

    public function handleWsError(\Exception $e): void
    {
        $this->discord->handleWsError($e); 
    }

    public function handleWsConnectionFailed(\Throwable $e)
    {
        $this->discord->handleWsConnectionFailed($e); 
    }

    protected function handleDispatch(object $data): void
    {
        $this->discord->handleDispatch($data); 
    }

    protected function handleHeartbeat(object $data): void
    {
        $this->discord->handleHeartbeat($data); 
    }

    protected function handleHeartbeatAck(object $data): void
    {
        $this->discord->handleHeartbeatAck($data); 
    }

    protected function handleReconnect(object $data): void
    {
        $this->discord->handleReconnect($data); 
    }

    protected function handleInvalidSession(object $data): void
    {
        $this->discord->handleInvalidSession($data); 
    }

    protected function handleHello(object $data): void
    {
        $this->discord->handleHello($data); 
    }

    protected function identify(bool $resume = true): bool
    {
        $this->discord->identify($resume); 
    }

    public function heartbeat(): void
    {
        $this->discord->heartbeat(); 
    }

    protected function setupChunking()
    {
        $this->discord->setupChunking(); 
    }

    protected function setupHeartbeat(int $interval): void
    {
        $this->discord->setupHeartbeat($interval); 
    }

    protected function connectWs(): void
    {
        $this->discord->connectWs(); 
    }

    protected function send(array $data, bool $force = false): void
    {
        $this->discord->send($data, $force); 
    }

    protected function ready()
    {
        return $this->discord->ready(); 
    }

    public function updatePresence(
        Activity $activity = null,
        bool $idle = false,
        string $status = 'online',
        bool $afk = false
    ): void {
        $this->discord->updatePresence($activity, $idle, $status, $afk); 
    }

    public function getVoiceClient(string $guild_id): ?VoiceClient
    {
        return $this->discord->getVoiceClient($guild_id); 
    }

    public function joinVoiceChannel(
        Channel $channel,
        $mute = false,
        $deaf = true,
        ?LoggerInterface $logger = null,
        bool $check = true
    ): ExtendedPromiseInterface {
        return $this->discord->joinVoiceChannel($channel, $mute, $deaf, $logger, $check); 
    }

    protected function setGateway(?string $gateway = null): ExtendedPromiseInterface
    {
        return $this->discord->setGateway($gateway); 
    }

    protected function resolveOptions(array $options = []): array
    {
        return $this->discord->resolveOptions($options); 
    }

    public function addLargeGuild(Part $guild): void
    {
        $this->discord->addLargeGuild($guild); 
    }

    public function run(): void
    {
        $this->discord->run(); 
    }

    public function close(bool $closeLoop = true): void
    {
        $this->discord->close($closeLoop); 
    }

    public function factory(string $class, $data = [], bool $created = false)
    {
        return $this->discord->factory($class, $data, $created); 
    }

    public function getFactory(): Factory
    {
        return $this->discord->getFactory(); 
    }

    public function getHttpClient(): Http
    {
        return $this->discord->getHttpClient(); 
    }

    public function getLoop(): LoopInterface
    {
        return $this->discord->getLoop(); 
    }

    public function getLogger(): LoggerInterface
    {
        return $this->discord->getLogger(); 
    }

    public function getHttp(): Http
    {
        return $this->discord->getHttp(); 
    }

    public function __get($name)
    {
        return $this->discord->__get($name); 
    }

    public function __set($name, $value)
    {
        $this->discord->__set($name, $value);
    }

    public function getChannel($channel_id): ?Channel
    {
        return $this->discord->getChannel($channel_id); 
    }

    public function listenCommand(
        $name,
        callable $callback = null,
        ?callable $autocomplete_callback = null
    ): RegisteredCommand {
        return $this->discord->listenCommand($name, $callback, $autocomplete_callback); 
    }

    public function __call($name, $params)
    {
        return $this->discord->__call($name, $params); 
    }

    public function __debugInfo(): array
    {
        return $this->discord->__debugInfo(); 
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function emit($event, array $arguments = [])
    {
        if (!isset($this->container)) {
            $this->discord->emit($event, $arguments);
            return;
        }

        if (isset($this->listeners[$event])) {
            foreach ($this->listeners[$event] as $listener) {
                $this->invoke($listener, $arguments);
            }
        }

        if (isset($this->onceListeners[$event])) {
            foreach ($this->onceListeners[$event] as $listener) {
                $this->invoke($listener, $arguments);
            }
            unset($this->onceListeners[$event]);
        }
    }

    protected function invoke(callable $listener, array $arguments): void {
        if (is_array($listener)) {
            list($class, $method) = $listener;
            if ($this->container->has($class)) {
                $listener = [$this->container->get($class), $method];
            }
        }

        $listener = Closure::fromCallable($listener);
        $listener(...$arguments);
    }
}