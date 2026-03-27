<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use Symfony\Component\Uid\Uuid;

class SignUpConfirmTokenizer
{
    public function generate(): string
    {
        return Uuid::v7()->toString();
    }
}
