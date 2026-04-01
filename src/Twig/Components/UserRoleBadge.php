<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('user_role_badge')]
class UserRoleBadge
{
    public string $role;
}
