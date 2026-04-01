<?php

declare(strict_types=1);

namespace App\ReadModel\User;


class NetworkView
{
    public function __construct(
        public ?string $network = null,
        public ?string $identity = null,
    ) {
    }

    public static function fromDatabase(array $data): self
    {
        return new self(
            network: $data['network'] ?? null,
            identity: $data['identity'] ?? null,
        );
    }
}
