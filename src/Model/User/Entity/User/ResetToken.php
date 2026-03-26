<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use PHPUnit\Framework\Assert;

class ResetToken
{
    /**
     * @var string
     */
    private $token;
    /**
     * @var \DateTimeImmutable
     */
    private $expires;

    public function __construct(string $token, \DateTimeImmutable $expires)
    {
        Assert::assertNotEmpty($token);
        $this->token = $token;
        $this->expires = $expires;
    }

    public function isExpiredTo(\DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
