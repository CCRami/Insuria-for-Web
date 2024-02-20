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
    private ?array $doa = null;

    #[ORM\ManyToOne(inversedBy: 'assurance')]
    private ?CategorieAssurance $catA = null;

    #[ORM\Column(length: 255)]
    private ?string $insImage = null;

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

    public function getDoa(): ?array
    {
        return $this->doa;
    }

    public function setDoa(array $doa): static
    {
        $this->doa = $doa;

        return $this;
    }

    public function getCatA(): ?CategorieAssurance
    {
        return $this->catA;
    }

    public function setCatA(?CategorieAssurance $catA): static
    {
        $this->catA = $catA;

        return $this;
    }

    public function getInsImage(): ?string
    {
        return $this->insImage;
    }

    public function setInsImage(string $insImage): static
    {
        $this->insImage = $insImage;

        return $this;
    }
}
