<?php

namespace App\Entity;

use App\Repository\CategorieOffreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieOffreRepository::class)]
class CategorieOffre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $categorie_name = null;

    #[ORM\Column(length: 255)]
    private ?string $description_cat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorieName(): ?string
    {
        return $this->categorie_name;
    }

    public function setCategorieName(string $categorie_name): static
    {
        $this->categorie_name = $categorie_name;

        return $this;
    }

    public function getDescriptionCat(): ?string
    {
        return $this->description_cat;
    }

    public function setDescriptionCat(string $description_cat): static
    {
        $this->description_cat = $description_cat;

        return $this;
    }
}
