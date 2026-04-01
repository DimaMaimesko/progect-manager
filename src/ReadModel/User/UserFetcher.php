<?php

declare(strict_types=1);

namespace App\ReadModel\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\FetchMode;

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

    /**
     * @throws Exception
     */
    public function findForAuth(string $email): ?AuthView
    {
        $result = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'email',
                'password_hash',
                'role',
                'status'
            )
            ->from('user_users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery()
            ->fetchAssociative();

        if ($result === false) {
            return null;
        }

        $view = AuthView::fromDatabase($result);

        return $view;
    }

    public function findDetail(string $id): ?DetailView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'date',
                'email',
                'role',
                'status'
            )
            ->from('user_users')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery()->fetchAssociative();

        if ($stmt === false) {
            return null;
        }
        $view = DetailView::fromDatabase($stmt);


        $stmt = $this->connection->createQueryBuilder()
            ->select('network', 'identity')
            ->from('user_user_networks')
            ->where('user_id = :id')
            ->setParameter('id', $id)
            ->executeQuery()->fetchAssociative();

        if ($stmt !== false) {
            $view->networks = NetworkView::fromDatabase($stmt);
        }

        return $view;
    }
}
