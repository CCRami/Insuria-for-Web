<?php

namespace App\Entity;

use App\Repository\PoliceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PoliceRepository::class)]
class Police
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $police_name = null;

    #[ORM\Column(length: 255)]
    private ?string $Description_police = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoliceName(): ?string
    {
        return $this->police_name;
    }

    public function setPoliceName(string $police_name): static
    {
        $this->police_name = $police_name;

        return $this;
    }

    public function getDescriptionPolice(): ?string
    {
        return $this->Description_police;
    }

    public function setDescriptionPolice(string $Description_police): static
    {
        $this->Description_police = $Description_police;

        return $this;
    }
}
