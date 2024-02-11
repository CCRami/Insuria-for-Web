<?php

namespace App\Entity;

use App\Repository\SinistreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SinistreRepository::class)]
class Sinistre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $sin_name = null;

    #[ORM\Column(length: 255)]
    private ?string $description_sin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSinName(): ?string
    {
        return $this->sin_name;
    }

    public function setSinName(string $sin_name): static
    {
        $this->sin_name = $sin_name;

        return $this;
    }

    public function getDescriptionSin(): ?string
    {
        return $this->description_sin;
    }

    public function setDescriptionSin(string $description_sin): static
    {
        $this->description_sin = $description_sin;

        return $this;
    }
}
