<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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
    private ?string $full_doa = null;

    #[ORM\Column]
    private ?float $insvalue = null;

    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: 'command')]
    private Collection $reclamations;

    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
    }

   

 

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

    public function getFullDoa(): ?string
    {
        return $this->full_doa;
    }

    public function setFullDoa(string $full_doa): static
    {
        $this->full_doa = $full_doa;

        return $this;
    }

    public function getInsvalue(): ?float
    {
        return $this->insvalue;
    }

    public function setInsvalue(float $insvalue): static
    {
        $this->insvalue = $insvalue;

        return $this;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): static
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setCommand($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): static
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getCommand() === $this) {
                $reclamation->setCommand(null);
            }
        }

        return $this;
    }
}