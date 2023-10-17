<?php

namespace App\Entity;

use App\Repository\PlanningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
class Planning
{
    #[Groups("planning")]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups("planning")]
    #[Assert\NotBlank(message:"Vous n'avez pas saisi la date.")]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datePlanning = null;

    #[Groups("planning")]
    #[Assert\NotBlank(message:"Vous n'avez pas saisi le jour.")]
    #[ORM\Column(length: 255)]
    private ?string $jourPlanning = null;

    #[Groups("planning")]
    #[Assert\NotBlank(message:"Vous n'avez pas saisi l'horaire.")]
    #[Assert\Positive(message:"Veuillez saisir l'horaire valide.")]
    #[ORM\Column]
    private ?int $heurePlanning = null;

    #[Assert\NotBlank(message:"Vous n'avez pas saisi le cours dans ce planning.")]
    #[ORM\ManyToOne(inversedBy: 'plannings')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Cours $cours = null;

    public function __construct()
    {
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePlanning(): ?\DateTimeInterface
    {
        return $this->datePlanning;
    }

    public function setDatePlanning(\DateTimeInterface $datePlanning): self
    {
        $this->datePlanning = $datePlanning;

        return $this;
    }

    public function getJourPlanning(): ?string
    {
        return $this->jourPlanning;
    }

    public function setJourPlanning(string $jourPlanning): self
    {
        $this->jourPlanning = $jourPlanning;

        return $this;
    }

    public function getHeurePlanning(): ?int
    {
        return $this->heurePlanning;
    }

    public function setHeurePlanning(int $heurePlanning): self
    {
        $this->heurePlanning = $heurePlanning;

        return $this;
    }

    public function __toString():string{
        return $this->cours;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): self
    {
        $this->cours = $cours;

        return $this;
    }
}
