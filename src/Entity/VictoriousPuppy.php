<?php

namespace App\Entity;

use App\Repository\VictoriousPuppyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VictoriousPuppyRepository::class)]
class VictoriousPuppy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
