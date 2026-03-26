<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use PHPUnit\Framework\Assert;

class Email
{
    private $value;

    public function __construct(string $value)
    {
        Assert::assertNotEmpty($value);
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Incorrect email.');
        }
        $this->value = mb_strtolower($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
