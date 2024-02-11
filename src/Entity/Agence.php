<?php

namespace App\Entity;

use App\Repository\AgenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgenceRepository::class)]
class Agence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_age = null;

    #[ORM\Column(length: 255)]
    private ?string $addresse = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $tel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAge(): ?string
    {
        return $this->nom_age;
    }

    public function setNomAge(string $nom_age): static
    {
        $this->nom_age = $nom_age;

        return $this;
    }

    public function getAddresse(): ?string
    {
        return $this->addresse;
    }

    public function setAddresse(string $addresse): static
    {
        $this->addresse = $addresse;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): static
    {
        $this->tel = $tel;

        return $this;
    }
}
