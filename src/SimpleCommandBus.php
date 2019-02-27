<?php declare(strict_types=1);

namespace Maybeway\SimpleCommandBus;

use Maybeway\Command\CommandConvention;
use Maybeway\Command\CommandHandler;
use Maybeway\SimpleCommandBus\Exception\CommandHandlerIsNotCallable;
use Maybeway\SimpleCommandBus\Exception\CommandHasNoCorrespondsHandler;
use Psr\Container\ContainerInterface;

/**
 * @package Maybeway\SimpleCommandBus
 * @author Michal Koričanský <koricansky.michal@gmail.com>
 */
class SimpleCommandBus
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var CommandConvention
     */
    protected $commandConvention;

    public function __construct(ContainerInterface $container, CommandConvention $commandConvention)
    {
        $this->container = $container;
        $this->commandConvention = $commandConvention;
    }

    /**
     * @param Command $command
     *
     * @throws CommandHandlerIsNotCallable
     * @throws CommandHasNoCorrespondsHandler
     */
    public function execute(Command $command)
    {
        $handler = $this->getCommandHandler($command);

        if (!is_callable($handler) && $handler instanceof CommandHandler) {
            throw new CommandHandlerIsNotCallable(get_class($handler));
        }

        $handler($command);
    }

    /**
     * @param Command $command
     *
     * @return CommandHandler
     *
     * @throws CommandHasNoCorrespondsHandler
     */
    protected function getCommandHandler(Command $command): CommandHandler
    {
        $handlerClass = $this->commandConvention->handlerName($command);

        if (!$this->container->has($handlerClass)) {
            throw new CommandHasNoCorrespondsHandler($handlerClass);
        }

        return $this->container->get($handlerClass);
    }
}