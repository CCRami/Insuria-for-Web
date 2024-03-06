<?php

namespace App\Entity;

use App\Repository\IndemnissationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: IndemnissationRepository::class)]
class Indemnissation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable:true)]
    #[Assert\GreaterThan("today", message: "The date must be in the future")]
   
    private ?\DateTimeInterface $date = null;
    public function __construct()
    {
        $this->date = null;
        $this->montant=null;
      
    }


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $beneficitaire ;
#[Assert\NotBlank(message: "The Amount is required")]
    #[ORM\Column(type:"float", nullable:true)]
    #[Assert\GreaterThanOrEqual(
     value:0,
     message:"The amount must be greater than 0")]
        private ?float $montant =null;

        public function getId(): ?int
        {
            return $this->id;
        }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date ): static
    {
        $this->date = $date;

        return $this;
    }

    public function getBeneficitaire(): ?string
    {
        return $this->beneficitaire;
    }

    public function setBeneficitaire(?string $beneficitaire): static
    {
        $this->beneficitaire = $beneficitaire;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(?float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

}
