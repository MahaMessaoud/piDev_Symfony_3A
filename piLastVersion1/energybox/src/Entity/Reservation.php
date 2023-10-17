<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("reservation")]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThanOrEqual(value: "today",message:"Veuillez saisir une date supérieure ou égal à la date d'aujourd'hui")]
    private ?\DateTimeInterface $date = null;


   #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Plat $idplat = null;

   #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'reservations')]
   private Collection $Clients;

   public function __construct()
   {
       $this->Clients = new ArrayCollection();
   }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

  

    public function getIdplat(): ?Plat
    {
        return $this->idplat;
    }

    public function setIdplat(?Plat $idplat): self
    {
        $this->idplat = $idplat;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getClients(): Collection
    {
        return $this->Clients;
    }

    public function addClient(User $client): self
    {
        if (!$this->Clients->contains($client)) {
            $this->Clients->add($client);
        }

        return $this;
    }

    public function removeClient(User $client): self
    {
        $this->Clients->removeElement($client);

        return $this;
    }


}
