<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\ResetToken;
use Symfony\Component\Uid\Uuid;

class ResetTokenGenerator
{
    private $interval;

    public function __construct(\DateInterval $interval)
    {
        $this->interval = $interval;
    }

    public function generate(): ResetToken
    {
        return new ResetToken(
            Uuid::v7()->toString(),
            new \DateTimeImmutable()->add($this->interval)
        );
    }
}
