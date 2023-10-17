<?php

namespace App\Entity;

use App\Repository\AbonnementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; 
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AbonnementRepository::class)]
class Abonnement
{  
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("abonnements")]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("abonnements")]
    # #[Assert\NotBlank(  message: "Le champ est vide !",  )] 
    private ?\DateTimeInterface $dateAchat = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("abonnements")]
    # #[Assert\NotBlank(   message: "Le champ est vide !",  )]
    private ?\DateTimeInterface $dateFin = null;
    #[ORM\Column(length: 255)]
    #[Groups("abonnements")]
    # #[Assert\NotBlank(message: "Le champ est vide !", )]
    private ?string $etatAbonnement = "";

    #[ORM\ManyToOne(inversedBy: 'abonnements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("abonnements")]
    private ?Pack $pack = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("abonnements")]
    private ?string $CodePromo = "";

    #[ORM\Column]
    #[Groups("abonnements")]
    private ?float $MontantAbonnement = 0;

    #[ORM\ManyToOne(inversedBy: 'Abonnements')]
    #[Groups("abonnements")]
    private ?User $user = null;
   
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->dateAchat;
    }

    public function setDateAchat(\DateTimeInterface $dateAchat): self
    {
        $this->dateAchat = $dateAchat;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getEtatAbonnement(): ?string
    {
        return $this->etatAbonnement;
    }

    public function setEtatAbonnement(string $etatAbonnement): self
    {
        $this->etatAbonnement = $etatAbonnement;

        return $this;
    }

    public function getPack(): ?Pack
    {
        return $this->pack;
    }

    public function setPack(?Pack $pack): self
    {
        $this->pack = $pack;

        return $this;
    }
    public function __toString(){
        return(String) $this->getPack();
    }

    public function getCodePromo(): ?string
    {
        return $this->CodePromo;
    }

    public function setCodePromo(?string $CodePromo): self
    {
        $this->CodePromo = $CodePromo;

        return $this;
    }

    public function getMontantAbonnement(): ?float
    {
        return $this->MontantAbonnement;
    }

    public function setMontantAbonnement(float $MontantAbonnement): self
    {
        $this->MontantAbonnement = $MontantAbonnement;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
