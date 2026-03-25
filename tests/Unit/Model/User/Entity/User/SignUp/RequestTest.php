<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\User\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = new User(
            $id = Uuid::v7()->toString(),
            $date = new \DateTimeImmutable(),
            $email = 'test@app.test',
            $hash = 'hash'
        );

        self::assertEquals($id, $user->getId());
        self::assertEquals($date, $user->getDate());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($hash, $user->getPasswordHash());
    }
}
