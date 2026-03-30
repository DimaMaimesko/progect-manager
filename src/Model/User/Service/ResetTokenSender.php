<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\ResetToken;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class ResetTokenSender
{
    public function __construct(
        protected MailerInterface $mailer,
        #[Autowire('%admin_email%')] private string $adminEmail,
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(Email $email, ResetToken $token): void
    {
        $this->mailer->send(new NotificationEmail()
            ->subject('Password resetting')
            ->htmlTemplate('mail/user/reset.html.twig')
            ->from($this->adminEmail)
            ->to($email->getValue())
            ->context(['token' => $token->getToken()]));
    }
}
