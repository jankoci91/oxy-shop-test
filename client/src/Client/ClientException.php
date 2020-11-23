<?php declare(strict_types=1);

namespace App\Client;

use RuntimeException;
use Throwable;

class ClientException extends RuntimeException
{
    public function __construct($message, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
