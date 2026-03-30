<?php

declare(strict_types=1);

namespace App\ReadModel\User;

use Doctrine\DBAL\Connection;

class UserFetcher
{
    public function __construct(protected Connection $connection)
    {
    }

    public function existsByResetToken(string $token): bool
    {
        return $this->connection->createQueryBuilder()
                ->select('COUNT(*)')
                ->from('user_users')
                ->where('reset_token_token = :token')
                ->setParameter('token', $token)
                ->executeQuery()->fetchOne() > 0;
    }
}
