<?php

namespace App\Entity;

use App\Repository\ChargeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ChargeRepository::class)]
class Charge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("charges")]

    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Groups("charges")]

    private ?int $quantiteCharge = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("charges")]
    private ?\DateTimeInterface $dateArrivageCharge = null;

    #[ORM\ManyToOne(inversedBy: 'charges')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Groups("charges")]
    private ?Fournisseur $fournisseur = null;

    #[ORM\ManyToOne(inversedBy: 'charges')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Groups("charges")]
    private ?Materiel $materiel = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function getQuantiteCharge(): ?int
    {
        return $this->quantiteCharge;
    }

    public function setQuantiteCharge(int $quantiteCharge): self
    {
        $this->quantiteCharge = $quantiteCharge;

        return $this;
    }

    public function getDateArrivageCharge(): ?\DateTimeInterface
    {
        return $this->dateArrivageCharge;
    }

    public function setDateArrivageCharge(\DateTimeInterface $dateArrivageCharge): self
    {
        $this->dateArrivageCharge = $dateArrivageCharge;

        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getMateriel(): ?Materiel
    {
        return $this->materiel;
    }

    public function setMateriel(?Materiel $materiel): self
    {
        $this->materiel = $materiel;

        return $this;
    }

}
