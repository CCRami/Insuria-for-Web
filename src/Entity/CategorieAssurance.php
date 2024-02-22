<?php

namespace App\Entity;

use App\Repository\CategorieAssuranceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategorieAssuranceRepository::class)]
class CategorieAssurance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 15,
        minMessage: 'The name must be at least {{ limit }} characters long',
        maxMessage: 'The name cannot be longer than {{ limit }} characters'
    )]
    private ?string $name_cat_ins = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Description cannot be blank')]
    #[Assert\Length(max: 255, maxMessage: 'Description cannot be longer than {{ limit }} characters')]
    private ?string $desc_cat_ins = null;

    #[ORM\OneToMany(targetEntity: Assurance::class, mappedBy: 'catA')]
    private Collection $assurances;

    public function __construct()
    {
        $this->assurances = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Assurance>
     */
    public function getAssurances(): Collection
    {
        return $this->assurances;
    }

    public function addAssurance(Assurance $assurance): static
    {
        if (!$this->assurances->contains($assurance)) {
            $this->assurances->add($assurance);
            $assurance->setCatA($this);
        }

        return $this;
    }

    public function removeAssurance(Assurance $assurance): static
    {
        if ($this->assurances->removeElement($assurance)) {
            // set the owning side to null (unless already changed)
            if ($assurance->getCatA() === $this) {
                $assurance->setCatA(null);
            }
        }

        return $this;
    }
}
