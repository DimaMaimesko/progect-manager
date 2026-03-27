<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank(message: 'Email is required.')]
    #[Assert\Email(message: 'Enter a valid email.')]
    public string $email = '';

    #[Assert\NotBlank(message: 'Password is required.')]
    #[Assert\Length(min: 6, minMessage: 'Password must be at least 6 characters.')]
    public string $password = '';
}
