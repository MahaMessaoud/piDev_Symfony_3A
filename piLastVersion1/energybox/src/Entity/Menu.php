<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
   #[Groups("menu")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le nom du menu ne doit pas être vide")]
   // #[Assert\Regex('/^[A-Z][a-z]*( [a-z]+)*$/', message:"Le nom doit commencer par une majuscule seulement")]
    #[Assert\Length(min: 5,minMessage: 'Le nom  doit au moin avoir 5 characteres ')]
    #[Groups("menu")]
    private ?string $categories = null;


    #[ORM\OneToMany(mappedBy: 'categories', targetEntity: Plat::class)]
    private Collection $plats;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le champ ne doit pas être vide")]
   #[Groups("menu")]
    private ?string $UserId = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"La description ne doit pas être vide")]
    #[Assert\Length(min: 10,minMessage: 'La description doit au moin avoir 10 characters ')]
   #[Groups("menu")]
    private ?string $descriptionmenu = null;


    #[ORM\Column(nullable: true)]
//    #[Assert\NotBlank(message:"Le nombre de plats ne doit pas être vide")]
    #[Assert\Positive(message:"Le nombre de plats doit être positive")]
    #[Assert\Range(max: 100,maxMessage:"Le nombre de plats ne doit dépasser 100")]
  //  #[Assert\Regex("/^[0-9]+$/", message: "Le nombre de plats doit être un nombre entier")]
    private ?int $nbplats = null;

    public function __construct()
    {
        $this->plats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategories(): ?string
    {
        return $this->categories;
    }

    public function setCategories(string $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Collection<int, Plat>
     */
    public function getPlats(): Collection
    {
        return $this->plats;
    }

    public function addPlat(Plat $plat): self
    {
        if (!$this->plats->contains($plat)) {
            $this->plats->add($plat);
            $plat->setCategories($this);
        }

        return $this;
    }

    public function removePlat(Plat $plat): self
    {
        if ($this->plats->removeElement($plat)) {
            // set the owning side to null (unless already changed)
            if ($plat->getCategories() === $this) {
                $plat->setCategories(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        if (is_null($this->categories)) {
            return 'NULL';
        }
        return (string) $this->categories;
    }

    public function getUserId(): ?string
    {
        return $this->UserId;
    }

    public function setUserId(string $UserId): self
    {
        $this->UserId = $UserId;

        return $this;
    }

    public function getDescriptionmenu(): ?string
    {
        return $this->descriptionmenu;
    }

    public function setDescriptionmenu(string $descriptionmenu): self
    {
        $this->descriptionmenu = $descriptionmenu;

        return $this;
    }

    public function getNbplats(): ?int
    {
        return $this->nbplats;
    }

    public function setNbplats(?int $nbplats): self
    {
        $this->nbplats = $nbplats;

        return $this;
    }


}
