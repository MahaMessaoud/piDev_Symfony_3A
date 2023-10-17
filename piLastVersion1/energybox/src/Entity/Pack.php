<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert; 
use App\Repository\PackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PackRepository::class)]
class Pack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("packs")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(  message: "Le type du pack est vide !",     )]
    #[Assert\Regex(pattern: '/[a-zA-Z]/',
    message:' le type du pack doit contenir que des lettres !!')]
    #[Groups("packs")]
    private ?string $typePack = null;

    #[ORM\Column]
    #[Assert\NotBlank( message: "Le champ est vide !",         )]
    #[Assert\Positive( message: "Le montant doit etre positive!",   )]
    #[Groups("packs")]
    private ?float $montantPack = null;

    #[ORM\OneToMany(mappedBy: 'pack', targetEntity: Abonnement::class , cascade:["remove"])]
    #[Groups("packs")]
    private Collection $abonnements;

    #[ORM\Column]
    #[Assert\NotBlank(   message: "Le  durée est vide !"   )]
    #[Assert\Positive(  message: "Le durée du pack doit etre positive!" )]
    #[Groups("packs")]
    private ?int $DureePack = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(   message: "La description est vide !"    )]
    #[Groups("packs")]
    private ?string $descriptionPack = null;

    #[ORM\Column]
    #[Groups("packs")]
    private ?int $placesPack = 0;

    #[ORM\Column]
    #[Assert\NotBlank(   message: "La disponibilité est vide !"   )]
    #[Assert\Positive(  message: "La disponibilité doit etre positive!" )]
    #[Groups("packs")]
    private ?int $disponibilitePack = null;


    public function __construct()
    {
        $this->abonnements = new ArrayCollection();
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

    public function getTypePack(): ?string
    {
        return $this->typePack;
    }

    public function setTypePack(string $typePack): self
    {
        $this->typePack = $typePack;

        return $this;
    }


    public function getMontantPack(): ?float
    {
        return $this->montantPack;
    }

    public function setMontantPack(float $montantPack): self
    {
        $this->montantPack = $montantPack;

        return $this;
    }

    /**
     * @return Collection<int, Abonnement>
     */
    public function getAbonnements(): Collection
    {
        return $this->abonnements;
    }

    public function addAbonnement(Abonnement $abonnement): self
    {
        if (!$this->abonnements->contains($abonnement)) {
            $this->abonnements->add($abonnement);
            $abonnement->setPack($this);
        }

        return $this;
    }

    public function removeAbonnement(Abonnement $abonnement): self
    {
        if ($this->abonnements->removeElement($abonnement)) {
            // set the owning side to null (unless already changed)
            if ($abonnement->getPack() === $this) {
                $abonnement->setPack(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return(String) $this->getTypePack();
    }
    

    public function getDureePack(): ?int
    {
        return $this->DureePack;
    }

    public function setDureePack(int $DureePack): self
    {
        $this->DureePack = $DureePack;

        return $this;
    }

   


    public function getDescriptionPack(): ?string
    {
        return $this->descriptionPack;
    }

    public function setDescriptionPack(string $descriptionPack): self
    {
        $this->descriptionPack = $descriptionPack;

        return $this;
    }

    public function getPlacesPack(): ?int
    {
        return $this->placesPack;
    }

    public function setPlacesPack(?int $placesPack): self
    {
        $this->placesPack = $placesPack;

        return $this;
    }

    public function getDisponibilitePack(): ?int
    {
        return $this->disponibilitePack;
    }

    public function setDisponibilitePack(int $disponibilitePack): self
    {
        $this->disponibilitePack = $disponibilitePack;

        return $this;
    }
}
