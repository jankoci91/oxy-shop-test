<?php declare(strict_types=1);

namespace App\Client;

class Response
{
    private const TYPE_DATA = 1;
    private const TYPE_VALIDATION = 2;
    private const TYPE_NOTHING = 3;

    private array $content;
    private int $type;

    public static function data(string $content): self
    {
        return new self($content, self::TYPE_DATA);
    }

    public static function validation(string $content): self
    {
        return new self($content, self::TYPE_VALIDATION);
    }

    public static function nothing(): self
    {
        return new self('', self::TYPE_NOTHING);
    }

    private function __construct(string $content, int $type)
    {
        $this->content = (array) json_decode($content);
        $this->type = $type;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function isData()
    {
        return $this->type === self::TYPE_DATA;
    }

    public function isValidation()
    {
        return $this->type === self::TYPE_VALIDATION;
    }

    public function isNothing()
    {
        return $this->type === self::TYPE_NOTHING;
    }
}
