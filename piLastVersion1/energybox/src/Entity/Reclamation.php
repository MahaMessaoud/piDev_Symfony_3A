<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[Groups("reclamation")]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups("reclamation")]
    #[Assert\NotBlank(message:"Vous n'avez pas saisi votre nom.")]
    #[ORM\Column(length: 255)]
    private ?string $nomUserReclamation = null;

    #[Groups("reclamation")]
    #[Assert\NotBlank(message:"Vous n'avez pas saisi votre email.")]
    #[ORM\Column(length: 255)]
    private ?string $emailUserReclamation = null;
    
    #[Groups("reclamation")]
    #[Assert\NotBlank(message:"Vous n'avez pas saisi l'objet de reclamation.")]
    #[ORM\Column(length: 255)]
    private ?string $objetReclamation = null;

    #[Groups("reclamation")]
    #[Assert\NotBlank(message:"Vous n'avez pas saisi le texte de reclamation.")]
    #[ORM\Column(length: 255)]
    private ?string $texteReclamation = null;

    #[Groups("reclamation")]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateReclamation = null;

    #[Groups("reclamation")]
    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    private ?CategoryReclamation $category = null;

    // #[Groups("reclamation")]
    // #[ORM\OneToMany(mappedBy: 'reclamation', targetEntity: Reponse::class, cascade:["remove"])]
    // private Collection $reponses;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomUserReclamation(): ?string
    {
        return $this->nomUserReclamation;
    }

    public function setNomUserReclamation(string $nomUserReclamation): self
    {
        $this->nomUserReclamation = $nomUserReclamation;

        return $this;
    }

    public function getEmailUserReclamation(): ?string
    {
        return $this->emailUserReclamation;
    }

    public function setEmailUserReclamation(string $emailUserReclamation): self
    {
        $this->emailUserReclamation = $emailUserReclamation;

        return $this;
    }

    public function getObjetReclamation(): ?string
    {
        return $this->objetReclamation;
    }

    public function setObjetReclamation(string $objetReclamation): self
    {
        $this->objetReclamation = $objetReclamation;

        return $this;
    }

    public function getTexteReclamation(): ?string
    {
        return $this->texteReclamation;
    }

    public function setTexteReclamation(string $texteReclamation): self
    {
        $this->texteReclamation = $texteReclamation;

        return $this;
    }

    public function getDateReclamation(): ?\DateTimeInterface
    {
        return $this->dateReclamation;
    }

    public function setDateReclamation(\DateTimeInterface $dateReclamation): self
    {
        $this->dateReclamation = $dateReclamation;

        return $this;
    }

    public function getCategory(): ?CategoryReclamation
    {
        return $this->category;
    }

    public function setCategory(?CategoryReclamation $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function __toString(){
        return (string) $this->id;
    }

    /**
     * @return Collection<int, Reponse>
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses->add($reponse);
            $reponse->setReclamation($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getReclamation() === $this) {
                $reponse->setReclamation(null);
            }
        }

        return $this;
    }
}
