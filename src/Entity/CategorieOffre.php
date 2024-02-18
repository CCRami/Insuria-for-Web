<?php

namespace App\Entity;

use App\Repository\CategorieOffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(targetEntity: Offre::class, mappedBy: 'Categorie')]
    private Collection $offres;

    #[ORM\Column(length: 255)]
    private ?string $catimg = null;

    public function __construct()
    {
        $this->offres = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Offre>
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): static
    {
        if (!$this->offres->contains($offre)) {
            $this->offres->add($offre);
            $offre->setCategorie($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): static
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getCategorie() === $this) {
                $offre->setCategorie(null);
            }
        }

        return $this;
    }

    public function getCatimg(): ?string
    {
        return $this->catimg;
    }

    public function setCatimg(string $catimg): static
    {
        $this->catimg = $catimg;

        return $this;
    }
}
