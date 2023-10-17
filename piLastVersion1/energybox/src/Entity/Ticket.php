<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("ticket")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"CHAMP VIDE !! Il faut déclarer une description de la ticket !!")]
    #[Groups("ticket")]
    private ?string $descriptionTicket = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("ticket")]
    private ?Competition $competition = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptionTicket(): ?string
    {
        return $this->descriptionTicket;
    }

    public function setDescriptionTicket(string $descriptionTicket): self
    {
        $this->descriptionTicket = $descriptionTicket;

        return $this;
    }

    
    public function __toString()
    {
        return $this->descriptionTicket;
    }

    public function getCompetition(): ?Competition
    {
        return $this->competition;
    }

    public function setCompetition(?Competition $competition): self
    {
        $this->competition = $competition;

        return $this;
    }
}
