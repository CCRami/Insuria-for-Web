<?php

namespace App\Entity;

use App\Repository\AgenceRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Validation;

#[ORM\Entity(repositoryClass: AgenceRepository::class)]
class Agence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:8,minMessage:'Name must be more than 8 caracter')]
    #[Assert\NotBlank(message:'Name is required')]

    private ?string $nomage = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Address is required')]
    private ?string $addresse = null;

   /* #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;*/

    #[ORM\Column(length: 255)]
    #[Assert\Email(message:'Invalid Email')]
    #[Assert\NotBlank(message:'Email is required')]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\Length(min:8,minMessage:'Number must be 8 caracter')]
    #[Assert\Length(max:8,minMessage:'Number must be 8 caracter')]
    #[Assert\Positive(message:'Number must be positive')]
    #[Assert\NotBlank(message:'Number is required')]

    private ?int $tel = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $create_at = null;

    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'agenceav')]
    private Collection $agav;

    public function __construct()
    {
        $this->agav = new ArrayCollection();
    }

   



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAge(): ?string
    {
        return $this->nomage;
    }
    public function setNomAge(?string $nomage): static
    {
        $this->nomage = $nomage;

        return $this;
    }

    public function getAddresse(): ?string
    {
        return $this->addresse;
    }

    public function setAddresse(?string $addresse): static
    {
        $this->addresse = $addresse;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(?int $tel): static
    {
        $this->tel = $tel;

        return $this;
    }
   /*
    public function getPublicationdate(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setPublicationdate(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

   public function getCreateAt(): ?\DateTimeInterface
   {
       return $this->create_at;
   }

   public function setCreateAt(\DateTimeInterface $create_at): static
   {
       $this->create_at = $create_at;

       return $this;
   }
  
 */
public function getcreate_at(): ?\DateTimeInterface
{
    return $this->create_at;
}

public function setcreate_at(?\DateTimeInterface  $create_at): void
{
    $this->create_at = $create_at;
}

/**
 * @return Collection<int, Avis>
 */
public function getAgav(): Collection
{
    return $this->agav;
}

public function addAgav(Avis $agav): static
{
    if (!$this->agav->contains($agav)) {
        $this->agav->add($agav);
        $agav->setAgenceav($this);
    }

    return $this;
}

public function removeAgav(Avis $agav): static
{
    if ($this->agav->removeElement($agav)) {
        // set the owning side to null (unless already changed)
        if ($agav->getAgenceav() === $this) {
            $agav->setAgenceav(null);
        }
    }

    return $this;
}



   
   

  
}
