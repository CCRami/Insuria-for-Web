<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_effet = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_exp = null;

    #[ORM\Column(length: 255)]
    private ?array $full_doa = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Assurance $doaCom = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?User $user = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateEffet(): ?\DateTimeInterface
    {
        return $this->date_effet;
    }

    public function setDateEffet(\DateTimeInterface $date_effet): static
    {
        $this->date_effet = $date_effet;

        return $this;
    }

    public function getDateExp(): ?\DateTimeInterface
    {
        return $this->date_exp;
    }

    public function setDateExp(\DateTimeInterface $date_exp): static
    {
        $this->date_exp = $date_exp;

        return $this;
    }

    public function getFullDoa(): ?array
    {
        return $this->full_doa;
    }

    public function setFullDoa(array $full_doa): static
    {
        $this->full_doa = $full_doa;

        return $this;
    }

    public function getDoaCom(): ?assurance
    {
        return $this->doaCom;
    }

    public function setDoaCom(?assurance $doaCom): static
    {
        $this->doaCom = $doaCom;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    
}
