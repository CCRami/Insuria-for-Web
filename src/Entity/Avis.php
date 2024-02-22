<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:10,minMessage:'Comment must be more than 8 caracter')]
    #[Assert\NotBlank(message:'Comment is required')]
    private ?string $commentaire = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:'Note is required')]
    #[Assert\Positive(message:'Note must be positive')]
    private ?int $note = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_avis = null;

    #[ORM\ManyToOne(inversedBy: 'client')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Avis = null;

    #[ORM\ManyToOne(inversedBy: 'agav')]
    private ?Agence $agenceav = null;


    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getdate_avis(): ?\DateTimeInterface
    {
        return $this->date_avis;
    }

    public function setdate_avis(\DateTimeInterface $date_avis): static
    {
        $this->date_avis = $date_avis;

        return $this;
    }

    public function getAvis(): ?User
    {
        return $this->Avis;
    }

    public function setAvis(?User $Avis): static
    {
        $this->Avis = $Avis;

        return $this;
    }

    public function getAgenceav(): ?Agence
    {
        return $this->agenceav;
    }

    public function setAgenceav(?Agence $agenceav): static
    {
        $this->agenceav = $agenceav;

        return $this;
    }

   
   

    
}
