<?php declare(strict_types=1);

namespace App\Api;

final class NullObject
{
    private static ?self $instance = null;

    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        return self::$instance ? self::$instance : self::$instance = new self();
    }

    public static function equals($value): bool
    {
        return $value === self::getInstance();
    }
}
