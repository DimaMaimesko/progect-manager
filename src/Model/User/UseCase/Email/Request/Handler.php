<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Email\Request;

use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\EmailChangeTokenGenerator;
use App\Model\User\Service\NewEmailConfirmTokenizer;
use App\Model\User\Service\NewEmailConfirmTokenSender;

class Handler
{
    public function __construct(
        protected UserRepository $users,
        protected EmailChangeTokenGenerator $tokenGenerator,
        protected NewEmailConfirmTokenSender $sender,
        protected Flusher $flusher
    )
    {
    }

    public function handle(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));

        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new \DomainException('Email is already in use.');
        }

        $user->requestEmailChanging(
            $email,
            $token = $this->tokenGenerator->generate()
        );

        $this->flusher->flush();

        $this->sender->send($email, $token);
    }
}
