<?php

namespace App\Entity;
use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le libellé est obligatoire")]
    #[Assert\Length(
        max: 15,
        maxMessage: "Le libellé ne peut pas dépasser {{ limit }} caractères"
    )]
    
    private ?string $libelle = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    
    private ?\DateTimeInterface $date_decl = null;
    public function __construct()
    {
        $this->date_decl = new \DateTime();
    }
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
#[Assert\LessThanOrEqual("today", message:"La date ne peut pas être dans le futur.")]
#[Assert\NotNull(message: "La date ne peut pas être nulle.")]
    private ?\DateTimeInterface $date_sin ;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le contenu est obligatoire")]
    #[Assert\Length(
        min: 5,
        minMessage: "Le contenu de la réclamation doit dépasser {{ limit }} caractères"
    )]
    private ?string $contenu_rec = null;


   


    #[ORM\Column(nullable: true)]
    private ?string $reponse= "Currently being processed";

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
   
    private ?Indemnissation $indemnissation = null;
     #[ORM\Column(type:"string", length:255, nullable:true)]
   
   private $fileName;

   public function getFileName(): ?string
   {
       return $this->fileName;
   }

   public function setFileName(?string $fileName): self
   {
       $this->fileName = $fileName;

       return $this;
   }
    
    

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
    public function getDateDecl(): ?\DateTimeInterface
    {
        return $this->date_decl;
    }

    public function setDateDecl(\DateTimeInterface $date_decl): static
    {
        $this->date_decl= $date_decl;

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
    
    public function isReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): static
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getIndemnissation(): ?Indemnissation
    {
        return $this->indemnissation;
    }

    public function setIndemnissation(?Indemnissation $indemnissation): static
    {
        $this->indemnissation = $indemnissation;

        return $this;
    }
    
  
  
}
