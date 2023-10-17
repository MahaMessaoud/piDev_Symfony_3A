<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[Groups("cours")]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups("cours")]
    #[Assert\NotBlank(message:"Vous n'avez pas saisi le nom du Cours.")]
    #[ORM\Column(length: 255)]
    private ?string $nomCours = null;

    #[Groups("cours")]
    #[Assert\NotBlank(message:"Vous n'avez pas saisi le nom Coach.")]
    #[ORM\Column(length: 255)]
    private ?string $nomCoach = null;

    #[Groups("cours")]
    #[Assert\NotBlank(message:"Vous n'avez pas saisi l'age minimale de ce cours.")]
    #[Assert\Positive(message:"Veuillez saisir une valeur positive.")]
    #[ORM\Column]
    private ?int $ageMinCours = null;

    #[Groups("cours")]
    #[Assert\NotBlank(message:"Vous n'avez pas saisi le prix.")]
    #[Assert\Positive(message:"Veuillez saisir une valeur positive.")]
    #[ORM\Column]
    private ?float $prixCours = null;

    #[Groups("cours")]
    #[Assert\NotBlank(message:"Vous n'avez pas saisi la description du cours.")]
    #[ORM\Column(length: 255)]
    private ?string $descriptionCours = null;

    #[Assert\NotBlank(message:"Vous n'avez pas saisi les activités de ce cours.")]
    #[ORM\ManyToMany(targetEntity: Activite::class, inversedBy: 'cours')]
    private Collection $activites;

    #[ORM\OneToMany(mappedBy: 'cours', targetEntity: Planning::class, cascade:["persist","remove"])]
    private Collection $plannings;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    private ?User $CoachName = null;

    

    


    public function __construct()
    {
        $this->activites = new ArrayCollection();
        $this->plannings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCours(): ?string
    {
        return $this->nomCours;
    }

    public function setNomCours(string $nomCours): self
    {
        $this->nomCours = $nomCours;

        return $this;
    }

    public function getPrixCours(): ?float
    {
        return $this->prixCours;
    }

    public function setPrixCours(float $prixCours): self
    {
        $this->prixCours = $prixCours;

        return $this;
    }

    public function getNomCoach(): ?string
    {
        return $this->nomCoach;
    }

    public function setNomCoach(string $nomCoach): self
    {
        $this->nomCoach = $nomCoach;

        return $this;
    }

    public function getDescriptionCours(): ?string
    {
        return $this->descriptionCours;
    }

    public function setDescriptionCours(string $descriptionCours): self
    {
        $this->descriptionCours = $descriptionCours;

        return $this;
    }

    public function getAgeMinCours(): ?int
    {
        return $this->ageMinCours;
    }

    public function setAgeMinCours(int $ageMinCours): self
    {
        $this->ageMinCours = $ageMinCours;

        return $this;
    }

    public function __toString():string{
        return $this->id;
    }

    /**
     * @return Collection<int, Activite>
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function addActivite(Activite $activite): self
    {
        if (!$this->activites->contains($activite)) {
            $this->activites->add($activite);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        $this->activites->removeElement($activite);

        return $this;
    }

    /**
     * @return Collection<int, Planning>
     */
    public function getPlannings(): Collection
    {
        return $this->plannings;
    }

    public function addPlanning(Planning $planning): self
    {
        if (!$this->plannings->contains($planning)) {
            $this->plannings->add($planning);
            $planning->setCours($this);
        }

        return $this;
    }

    public function removePlanning(Planning $planning): self
    {
        if ($this->plannings->removeElement($planning)) {
            // set the owning side to null (unless already changed)
            if ($planning->getCours() === $this) {
                $planning->setCours(null);
            }
        }

        return $this;
    }

    public function getCoachName(): ?User
    {
        return $this->CoachName;
    }

    public function setCoachName(?User $CoachName): self
    {
        $this->CoachName = $CoachName;

        return $this;
    }
}
