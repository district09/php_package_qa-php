<?php

declare(strict_types=1);

namespace GrumPHP\Event;

use GrumPHP\Task\TaskInterface;
use LogicException;

final class TaskEvent
{
    public function getTask(): TaskInterface
    {
        throw new LogicException();
    }
}
