<?php

namespace App\Entity;

use App\Repository\SinistreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: SinistreRepository::class)]
#[UniqueEntity(fields: ['sin_name'], message: 'An identical claim name already exists.')]
class Sinistre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The claim name is required.")]
    #[Assert\Length(
        max: 30,
        maxMessage: "The claim name cannot exceed {{ limit }} characters."
    )]
    #[Assert\Regex(
        pattern: '/^[A-Z][a-zA-Z\s]*$/',
        message: "The claim name must start with an uppercase letter and contain only letters."
    )]

    private ?string $sin_name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The claim description is required.")]
    #[Assert\Regex(
        pattern: '/^[A-Z].*$/',
        message: "The description must start with an uppercase letter."
    )]
    #[Assert\Regex(
        pattern: '/\.$/',
        message: "The description must end with a period."
    )]

    private ?string $description_sin = null;


    #[ORM\OneToMany(targetEntity: Police::class, mappedBy: 'sinistre')]
    private Collection $police;

    #[ORM\Column(length: 255)]
    

    
    private ?string $imagePath = null;

    public function __construct()
    {
        $this->police = new ArrayCollection();
    }
     

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSinName(): ?string
    {
        return $this->sin_name;
    }

    public function setSinName(?string $sin_name): static
    {
        $this->sin_name = $sin_name;

        return $this;
    }

    public function getDescriptionSin(): ?string
    {
        return $this->description_sin;
    }

    public function setDescriptionSin(?string $description_sin): static
    {
        $this->description_sin = $description_sin = preg_replace('/\s+/', ' ', trim($description_sin));

        return $this;
    }

    /**
     * @return Collection<int, Police>
     */
    public function getPolice(): Collection
    {
        return $this->police;
    }

    public function addPolice(Police $police): static
    {
        if (!$this->police->contains($police)) {
            $this->police->add($police);
            $police->setSinistre($this);
        }

        return $this;
    }

    public function removePolice(Police $police): static
    {
        if ($this->police->removeElement($police)) {
            // set the owning side to null (unless already changed)
            if ($police->getSinistre() === $this) {
                $police->setSinistre(null);
            }
        }

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): static
    {
        $this->imagePath = $imagePath;

        return $this;
    }
}
