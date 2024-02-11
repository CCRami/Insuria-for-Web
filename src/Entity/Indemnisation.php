<?php

namespace App\Entity;

use App\Repository\IndemnisationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IndemnisationRepository::class)]
class Indemnisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_ind = null;

    #[ORM\Column(length: 255)]
    private ?string $benif = null;

    #[ORM\Column]
    private ?float $montant_ind = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateInd(): ?\DateTimeInterface
    {
        return $this->date_ind;
    }

    public function setDateInd(\DateTimeInterface $date_ind): static
    {
        $this->date_ind = $date_ind;

        return $this;
    }

    public function getBenif(): ?string
    {
        return $this->benif;
    }

    public function setBenif(string $benif): static
    {
        $this->benif = $benif;

        return $this;
    }

    public function getMontantInd(): ?float
    {
        return $this->montant_ind;
    }

    public function setMontantInd(float $montant_ind): static
    {
        $this->montant_ind = $montant_ind;

        return $this;
    }
}
