<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_user_networks')]
class Network
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $id;
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'networks')]
    #[ORM\JoinColumn(name: 'user_id', nullable: false)]
    private User $user;
    #[ORM\Column(type: 'string', length: 32)]
    private string $network;
    #[ORM\Column(type: 'string', length: 255)]
    private string $identity;

    public function __construct(User $user, string $network, string $identity)
    {
        $this->id = Uuid::v7()->toString();
        $this->user = $user;
        $this->network = $network;
        $this->identity = $identity;
    }

    public function isForNetwork(string $network): bool
    {
        return $this->network === $network;
    }

    public function getNetwork(): string
    {
        return $this->network;
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }
}
