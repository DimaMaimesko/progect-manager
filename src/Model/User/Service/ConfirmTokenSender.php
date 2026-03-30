<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\Email;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;

class ConfirmTokenSender
{
    public function __construct(
        protected MailerInterface $mailer,
        #[Autowire('%admin_email%')] private string $adminEmail,
    )
    {
    }

    public function send(Email $email, string $token): void
    {
        $this->mailer->send(new NotificationEmail()
            ->subject('Confirm your email')
            ->htmlTemplate('mail/user/signup-confirm.html.twig')
            ->from($this->adminEmail)
            ->to($email->getValue())
            ->context(['token' => $token]));
    }
}
