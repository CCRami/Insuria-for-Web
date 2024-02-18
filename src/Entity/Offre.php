<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $advantage = null;

    #[ORM\Column(length: 255)]
    private ?string $conditions = null;

    #[ORM\Column(length: 255)]
    private ?string $duration = null;

    #[ORM\Column]
    private ?float $discount = null;

    #[ORM\ManyToOne(inversedBy: 'offres')]
    private ?CategorieOffre $categorie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdvantage(): ?string
    {
        return $this->advantage;
    }

    public function setAdvantage(string $advantage): static
    {
        $this->advantage = $advantage;

        return $this;
    }

    public function getConditions(): ?string
    {
        return $this->conditions;
    }

    public function setConditions(string $conditions): static
    {
        $this->conditions = $conditions;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): static
    {
        $this->discount = $discount;

        return $this;
    }

    public function getCategorie(): ?CategorieOffre
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieOffre $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }
}
