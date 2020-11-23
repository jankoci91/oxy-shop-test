<?php declare(strict_types=1);

namespace App\Manager;

use LogicException;

class ManagerException extends LogicException
{
    public function __construct($message)
    {
        parent::__construct($message, 0, null);
    }
}
