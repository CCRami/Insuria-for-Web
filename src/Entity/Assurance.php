<?php

namespace App\Entity;

use App\Repository\AssuranceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssuranceRepository::class)]
class Assurance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name_ins = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(length: 255)]
    private ?string $doa = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameIns(): ?string
    {
        return $this->name_ins;
    }

    public function setNameIns(string $name_ins): static
    {
        $this->name_ins = $name_ins;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDoa(): ?string
    {
        return $this->doa;
    }

    public function setDoa(string $doa): static
    {
        $this->doa = $doa;

        return $this;
    }
}