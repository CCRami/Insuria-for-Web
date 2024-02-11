<?php

namespace App\Entity;

use App\Repository\CategorieAssuranceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieAssuranceRepository::class)]
class CategorieAssurance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name_cat_ins = null;

    #[ORM\Column(length: 255)]
    private ?string $desc_cat_ins = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameCatIns(): ?string
    {
        return $this->name_cat_ins;
    }

    public function setNameCatIns(string $name_cat_ins): static
    {
        $this->name_cat_ins = $name_cat_ins;

        return $this;
    }

    public function getDescCatIns(): ?string
    {
        return $this->desc_cat_ins;
    }

    public function setDescCatIns(string $desc_cat_ins): static
    {
        $this->desc_cat_ins = $desc_cat_ins;

        return $this;
    }
}
