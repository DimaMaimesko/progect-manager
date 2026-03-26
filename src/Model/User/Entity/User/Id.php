<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\Uuid;

class Id
{
    private $value;

    public function __construct(string $value)
    {
        Assert::assertNotEmpty($value);
        $this->value = $value;
    }

    public static function next(): self
    {
        return new self(Uuid::v7()->toString());
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
