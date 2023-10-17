<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[Groups("reponse")]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups("reponse")]
    #[Assert\NotBlank(message:"Vous n'avez pas saisi l objet.")]
    #[ORM\Column(length: 255)]
    private ?string $objetReponse = null;

    #[Groups("reponse")]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateReponse = null;

    #[Groups("reponse")]
    #[ORM\Column(length: 255)]
    private ?string $pieceJointe = null;

    #[Groups("reponse")]
    #[Assert\NotBlank(message:"Vous n'avez pas saisi le contenu de la reponse.")]
    #[ORM\Column(length: 255)]
    private ?string $contenuReponse = null;

    #[Groups("reponse")]
    #[ORM\ManyToOne(inversedBy: 'reponses')]
    private ?Reclamation $reclamation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjetReponse(): ?string
    {
        return $this->objetReponse;
    }

    public function setObjetReponse(string $objetReponse): self
    {
        $this->objetReponse = $objetReponse;

        return $this;
    }

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->dateReponse;
    }

    public function setDateReponse(\DateTimeInterface $dateReponse): self
    {
        $this->dateReponse = $dateReponse;

        return $this;
    }

    public function getPieceJointe(): ?string
    {
        return $this->pieceJointe;
    }

    public function setPieceJointe(?string $pieceJointe): self
    {
        $this->pieceJointe = $pieceJointe;

        return $this;
    }

    public function getContenuReponse(): ?string
    {
        return $this->contenuReponse;
    }

    public function setContenuReponse(string $contenuReponse): self
    {
        $this->contenuReponse = $contenuReponse;

        return $this;
    }

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(?Reclamation $reclamation): self
    {
        $this->reclamation = $reclamation;

        return $this;
    }
}
