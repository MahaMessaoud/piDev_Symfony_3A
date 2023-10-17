<?php

namespace App\Entity;

use App\Repository\CategoryReclamationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryReclamationRepository::class)]
class CategoryReclamation
{
    #[Groups("categoryreclamation")]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups("categoryreclamation")]
    #[Assert\NotBlank(message:"Veuillez saisir le nom de la categorie de reclamation.")]
    #[ORM\Column(length: 255)]
    private ?string $nomCategory = null;

    #[Groups("categoryreclamation")]
    #[Assert\NotBlank(message:"Veuillez saisir la description de la categorie de reclamation.")]
    #[ORM\Column(length: 255)]
    private ?string $descriptionCategory = null;

    #[Groups("categoryreclamation")]
    #[Assert\NotBlank(message:"Veuillez saisir la priorité de la categorie de reclamation.")]
    #[ORM\Column(length: 255)]
    private ?string $prioriteCategory = null;

    #[Groups("categoryreclamation")]
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Reclamation::class)]
    private Collection $reclamations;

    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategory(): ?string
    {
        return $this->nomCategory;
    }

    public function setNomCategory(string $nomCategory): self
    {
        $this->nomCategory = $nomCategory;

        return $this;
    }

    public function getDescriptionCategory(): ?string
    {
        return $this->descriptionCategory;
    }

    public function setDescriptionCategory(string $descriptionCategory): self
    {
        $this->descriptionCategory = $descriptionCategory;

        return $this;
    }

    public function getPrioriteCategory(): ?string
    {
        return $this->prioriteCategory;
    }

    public function setPrioriteCategory(string $prioriteCategory): self
    {
        $this->prioriteCategory = $prioriteCategory;

        return $this;
    }

    public function __toString(){
        return (string) $this->nomCategory;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setCategory($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getCategory() === $this) {
                $reclamation->setCategory(null);
            }
        }

        return $this;
    }
}
