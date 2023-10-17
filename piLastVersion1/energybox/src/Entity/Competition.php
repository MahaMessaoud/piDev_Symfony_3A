<?php

namespace App\Entity;

use App\Repository\CompetitionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CompetitionRepository::class)]
class Competition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("competitions")]
    private ?int $id = null;
    
    
    #[ORM\Column]
    #[Groups("competitions")]
    #[Assert\NotBlank(message:"Il faut déclarer le nom de compétition à ajouter !!")]
    private ?string $nomCompetition = null;
   

    #[ORM\Column]
    #[Groups("competitions")]
    #[Assert\Positive(message:"Prix invalide !!")]
    private ?float $fraisCompetition = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("competitions")]
    #[Assert\GreaterThanOrEqual(value: "today",message:"Date Invalide !!")]
    private ?\DateTimeInterface $dateCompetition = null;

    #[ORM\Column]
    #[Groups("competitions")]
        
       #[ Assert\Range(
               min : 0,
               max : 50,
               notInRangeMessage : "Veuillez entrer un nombre entre 0 et 50.",
               invalidMessage : "Veuillez entrer un nombre valide."
          )] 
    private ?int $nbrMaxInscrit = null;


    #[ORM\Column(length: 255)]
    #[Groups("competitions")]
    #[Assert\NotBlank(message:"L'Etat ne peut être que 'disponible' ou 'non disponible' !!")]
    private ?string $etatCompetition = null;

    #[ORM\OneToMany(mappedBy: 'competition', targetEntity: Ticket::class, cascade : ["remove"])]
    private Collection $tickets;

    #[ORM\Column(nullable: true)]
    #[Groups("competitions")]
    private ?int $nbrParticipant = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'competitions')]
    private Collection $inscrit;

 

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->inscrit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCompetition(): ?string
    {
        return $this->nomCompetition;
    }

    public function setNomCompetition(string $nomCompetition): self
    {
        $this->nomCompetition = $nomCompetition;

        return $this;
    }

    public function getFraisCompetition(): ?float
    {
        return $this->fraisCompetition;
    }

    public function setFraisCompetition(float $fraisCompetition): self
    {
        $this->fraisCompetition = $fraisCompetition;

        return $this;
    }

    public function getDateCompetition(): ?\DateTimeInterface
    {
        return $this->dateCompetition;
    }

    public function setDateCompetition(\DateTimeInterface $dateCompetition): self
    {
        $this->dateCompetition = $dateCompetition;

        return $this;
    }

    public function getNbrMaxInscrit(): ?int
    {
        return $this->nbrMaxInscrit;
    }

    public function setNbrMaxInscrit(int $nbrMaxInscrit): self
    {
        $this->nbrMaxInscrit = $nbrMaxInscrit;

        return $this;
    }

    public function getEtatCompetition(): ?string
    {
        return $this->etatCompetition;
    }

    public function setEtatCompetition(string $etatCompetition): self
    {
        $this->etatCompetition = $etatCompetition;

        return $this;
    }

    public function __toString()
    {
        return $this->nomCompetition;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setCompetition($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getCompetition() === $this) {
                $ticket->setCompetition(null);
            }
        }

        return $this;
    }

    public function getNbrParticipant(): ?int
    {
        return $this->nbrParticipant;
    }

    public function setNbrParticipant(?int $nbrParticipant): self
    {
        $this->nbrParticipant = $nbrParticipant;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getInscrit(): Collection
    {
        return $this->inscrit;
    }

    public function addInscrit(User $inscrit): self
    {
        if (!$this->inscrit->contains($inscrit)) {
            $this->inscrit->add($inscrit);
        }

        return $this;
    }

    public function removeInscrit(User $inscrit): self
    {
        $this->inscrit->removeElement($inscrit);

        return $this;
    }


}
