<?php declare(strict_types=1);

namespace Maybeway\SimpleCommandBus;

use Maybeway\Command\Command;
use Maybeway\Command\CommandConvention;

/**
 * @package Maybeway\SimpleCommandBus
 * @author Michal Koričanský <koricansky.michal@gmail.com>
 */
class CommandNameConvention implements CommandConvention
{

    /**
     * @param Command $command
     *
     * @return string
     */
    public function handlerName(Command $command): string
    {
        $className = lcfirst(ClassProperties::name($command));

        return str_replace('Command', 'CommandHandler', $className);
    }
}