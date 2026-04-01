<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_users')]
class User
{
    private const STATUS_NEW = 'new';
    private const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';

    #[ORM\Id]
    #[ORM\Column(type: 'user_user_id')]
    private $id;
    #[ORM\Column(type: 'datetime_immutable')]
    private $date;
    #[ORM\Column(type: 'user_user_email', nullable: true)]
    private ?Email $email = null;
    #[ORM\Column(type: 'user_user_email', nullable: true)]
    private ?Email $newEmail = null;
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $newEmailToken = null;
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $passwordHash;
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $confirmToken = null;
    #[ORM\Embedded(class: ResetToken::class)]
    private ?ResetToken $resetToken = null;
    #[ORM\Column(type: 'string', length: 16)]
    private $status;
    #[ORM\Column(type: 'user_user_role')]
    private $role;
    #[ORM\OneToMany(targetEntity: Network::class, mappedBy: 'user', cascade: ['all'], orphanRemoval: true)]
    private $networks;

    private function __construct(Id $id, \DateTimeImmutable $date)
    {
        $this->id = $id;
        $this->date = $date;
        $this->role = Role::user();
        $this->networks = new ArrayCollection();
    }

    public static function signUpByEmail(Id $id, \DateTimeImmutable $date, Email $email, string $hash, string $token): self
    {
        $user = new self($id, $date);
        $user->email = $email;
        $user->passwordHash = $hash;
        $user->confirmToken = $token;
        $user->status = self::STATUS_WAIT;
        return $user;
    }

    public function confirmSignUp(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already confirmed.');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    public static function signUpByNetwork(Id $id, \DateTimeImmutable $date, string $network, string $identity): self
    {
        $user = new self($id, $date);
        $user->attachNetwork($network, $identity);
        $user->status = self::STATUS_ACTIVE;
        return $user;
    }

    private function attachNetwork(string $network, string $identity): void
    {
        foreach ($this->networks as $existing) {
            if ($existing->isForNetwork($network)) {
                throw new \DomainException('Network is already attached.');
            }
        }
        $this->networks->add(new Network($this, $network, $identity));
    }

    public function requestPasswordReset(ResetToken $token, \DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('User is not active.');
        }
        if (!$this->email) {
            throw new \DomainException('Email is not specified.');
        }
        if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Resetting is already requested.');
        }
        $this->resetToken = $token;
    }

    public function passwordReset(\DateTimeImmutable $date, string $hash): void
    {
        if (!$this->resetToken) {
            throw new \DomainException('Resetting is not requested.');
        }
        if ($this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Reset token is expired.');
        }
        $this->passwordHash = $hash;
        $this->resetToken = null;
    }

    public function requestEmailChanging(Email $email, string $token): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('User is not active.');
        }
        if ($this->email && $this->email->isEqual($email)) {
            throw new \DomainException('Email is already same.');
        }
        $this->newEmail = $email;
        $this->newEmailToken = $token;
    }

    public function confirmEmailChanging(string $token): void
    {
        if (!$this->newEmailToken) {
            throw new \DomainException('Changing is not requested.');
        }
        if ($this->newEmailToken !== $token) {
            throw new \DomainException('Incorrect changing token.');
        }
        $this->email = $this->newEmail;
        $this->newEmail = null;
        $this->newEmailToken = null;
    }

    public function changeRole(Role $role): void
    {
        if ($this->role->isEqual($role)) {
            throw new \DomainException('Role is already same.');
        }
        $this->role = $role;
    }

    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    public function getResetToken(): ?ResetToken
    {
        return $this->resetToken;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @return Network[]
     */
    public function getNetworks(): array
    {
        return $this->networks->toArray();
    }

    public function getNewEmail(): ?Email
    {
        return $this->newEmail;
    }

    public function getNewEmailToken(): ?string
    {
        return $this->newEmailToken;
    }

    #[ORM\PostLoad]
    public function checkEmbeds(): void
    {
        if ($this->resetToken->isEmpty()) {
            $this->resetToken = null;
        }
    }

}
