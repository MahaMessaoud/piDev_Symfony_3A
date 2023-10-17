<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FournisseurRepository::class)]
class Fournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("fournisseurs")]

    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min :3,
        max: 30 ,
        minMessage: "Le nom du fournisseur doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le nom du fournisseur doit contenir au plus {{ limit }} caractères",

    )]
    #[Groups("fournisseurs")]

    private ?string $nomFournisseur = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        exactly:8,
        exactMessage: "Le numero doit etre {{ limit }} nombres",
    )]
    #[Assert\Regex(
        pattern: '/^[0-9]+$/i',
        message:"Invalid phone number") ]
    #[Groups("fournisseurs")]

    private ?int $contactFournisseur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Groups("fournisseurs")]

    private ?string $emailFournisseur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Groups("fournisseurs")]

    private ?string $adresseFournisseur = null;

    #[ORM\OneToMany(mappedBy: 'fournisseur', targetEntity: Charge::class)]
    #[Groups("fournisseurs")]

    private Collection $charges;

    public function __construct()
    {
        $this->charges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getNomFournisseur(): ?string
    {
        return $this->nomFournisseur;
    }

    public function setNomFournisseur(string $nomFournisseur): self
    {
        $this->nomFournisseur = $nomFournisseur;

        return $this;
    }

    public function getContactFournisseur(): ?int
    {
        return $this->contactFournisseur;
    }

    public function setContactFournisseur(int $contactFournisseur): self
    {
        $this->contactFournisseur = $contactFournisseur;

        return $this;
    }

    public function getEmailFournisseur(): ?string
    {
        return $this->emailFournisseur;
    }

    public function setEmailFournisseur(string $emailFournisseur): self
    {
        $this->emailFournisseur = $emailFournisseur;

        return $this;
    }

    public function getAdresseFournisseur(): ?string
    {
        return $this->adresseFournisseur;
    }

    public function setAdresseFournisseur(string $adresseFournisseur): self
    {
        $this->adresseFournisseur = $adresseFournisseur;

        return $this;
    }

    /**
     * @return Collection<int, Charge>
     */
    public function getCharges(): Collection
    {
        return $this->charges;
    }

    public function addCharge(Charge $charge): self
    {
        if (!$this->charges->contains($charge)) {
            $this->charges->add($charge);
            $charge->setFournisseur($this);
        }

        return $this;
    }

    public function removeCharge(Charge $charge): self
    {
        if ($this->charges->removeElement($charge)) {
            // set the owning side to null (unless already changed)
            if ($charge->getFournisseur() === $this) {
                $charge->setFournisseur(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->nomFournisseur;
    }

}
