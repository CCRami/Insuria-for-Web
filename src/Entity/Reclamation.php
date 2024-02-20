<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_decl = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_sin = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu_rec = null;

    #[ORM\Column]
    private ?bool $reponse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }
    public function getDate_decl(): ?\DateTimeInterface
     {
    return $this->date_decl;
     }

     public function setDate_decl(\DateTimeInterface $date_decl): static
    {
    $this->date_decl = $date_decl;

    return $this;
    }

    public function getDateSin(): ?\DateTimeInterface
    {
        return $this->date_sin;
    }

    public function setDateSin(\DateTimeInterface $date_sin): static
    {
        $this->date_sin = $date_sin;

        return $this;
    }

    public function getContenuRec(): ?string
    {
        return $this->contenu_rec;
    }

    public function setContenuRec(string $contenu_rec): static
    {
        $this->contenu_rec = $contenu_rec;

        return $this;
    }
    
    public function isReponse(): ?bool
    {
        return $this->reponse;
    }

    public function setReponse(bool $reponse): static
    {
        $this->reponse = $reponse;

        return $this;
    }
}
