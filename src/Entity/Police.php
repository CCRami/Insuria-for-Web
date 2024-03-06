<?php

namespace App\Entity;

use App\Repository\PoliceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PoliceRepository::class)]
#[UniqueEntity(fields: ['police_name'], message: 'An identical police name already exists.')]
class Police
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The police name is required.")]
    #[Assert\Length(
        max: 30,
        maxMessage: "The police name cannot exceed {{ limit }} characters."
    )]
    #[Assert\Regex(
        pattern: '/^[A-Z][a-zA-Z\s]*$/',
        message: "The police name must start with an uppercase letter and contain only letters."
    )]
    private ?string $police_name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The police description is required.")]
    #[Assert\Regex(
        pattern: '/^[A-Z].*$/',
        message: "The description must start with an uppercase letter."
    )]
    #[Assert\Regex(
        pattern: '/\.$/',
        message: "The description must end with a period."
    )]

    private ?string $Description_police = null;

    #[ORM\ManyToOne(inversedBy: 'police')]
    #[Assert\NotNull(message: "A claim must be selected.")]
    private ?Sinistre $sinistre = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoliceName(): ?string
    {
        return $this->police_name;
    }

    public function setPoliceName(?string $police_name): self
    {
        $this->police_name = $police_name;

        return $this;
    }

    public function getDescriptionPolice(): ?string
    {
        return $this->Description_police;
    }

    public function setDescriptionPolice(?string $Description_police): self
    {
        
        $this->Description_police = preg_replace('/\s+/', ' ', trim($Description_police));

        return $this;
    }

    public function getSinistre(): ?Sinistre
    {
        return $this->sinistre;
    }

    public function setSinistre(?Sinistre $sinistre): self
    {
        $this->sinistre = $sinistre;

        return $this;
    }
}
