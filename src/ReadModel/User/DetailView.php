<?php

declare(strict_types=1);

namespace App\ReadModel\User;

class DetailView
{
    /**
     * @var NetworkView[]
     */
    public array $networks = [];

    public function __construct(
        public ?string $id = null,
        public ?string $date = null,
        public ?string $email = null,
        public ?string $role = null,
        public ?string $status = null,
    ) {
    }

    public static function fromDatabase(array $data): self
    {
        return new self(
            id: $data['id'],
            date: $data['date'],
            email: $data['email'],
            role: $data['role'],
            status: $data['status'],
        );
    }
}
