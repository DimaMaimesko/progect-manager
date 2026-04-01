<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('user_status_badge')]
class UserStatusBadge
{
    public string $status;
}
