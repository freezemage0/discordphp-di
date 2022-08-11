<?php


namespace Freezemage\Discord;

use Discord\Discord;
use Discord\Exceptions\IntentException;
use Psr\Container\ContainerInterface;


final class Builder
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @throws BuilderException
     * @throws IntentException
     */
    public function build(array $options = []): Decorator
    {
        if (!class_exists('Discord\\Discord')) {
            throw BuilderException::missingDiscord();
        }

        return $this->wrap(new Discord($options));
    }

    /**
     * @throws IntentException
     */
    public function wrap(Discord $discord): Decorator
    {
        $wrapper = new Decorator($discord);
        $wrapper->setContainer($this->container);

        return $wrapper;
    }
}