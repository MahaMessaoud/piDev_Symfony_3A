<?php

namespace App\Entity;

use App\Repository\PlatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PlatRepository::class)]
class Plat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("plat")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le nom ne doit pas être vide")]
    #[Assert\Regex("/^[A-Z][a-z]*( [a-z]+)*$/", message:"Le nom doit commencer par une majuscule seulement")]
    #[Assert\Length(min: 5,minMessage: 'Le nom  doit au moin avoir 5 characteres ')]
    #[Groups("plat")]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Le prix ne doit pas être vide")]
    #[Assert\Positive(message:"Le prix doit être positive")]
    #[Groups("plat")]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"La description ne doit pas être vide")]
    #[Assert\Length(min: 10,minMessage: 'La description doit au moin avoir 10 characters ')]
    #[Groups("plat")]
    private ?string $description = null;


    #[ORM\Column(length: 255)]
    #[Assert\Regex("/^\d{1,4}$/", message: "Le champ calories doit contenir entre 1 et 4 chiffres.")]
    #[Assert\NotBlank(message:"Le champ calories ne doit pas être vide")]
    #[Groups("plat")]
    private ?string $calories = null;

    #[ORM\Column(length: 255)]
    #[Groups("plat")]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'plats')]
    private ?Menu $categories = null;

   /* #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"le champ ne doit pas être vide")]
    #[Groups("plat")]
    private ?string $UserId = null;*/

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"le champ ne doit pas être vide")]
    #[Groups("plat")]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message:"le champ ne doit pas être vide")]
    #[Assert\Positive(message:"Le nombre de plats doit être positive")]
    #[Assert\Range(min: 1,minMessage:"Le nombre de plats ne doit etre <1 ")]
    #[Assert\Range(max: 50,maxMessage:"Le nombre de plats ne doit dépasser 50")]
    //#[Assert\Regex("/^[0-9]+$/", message: "Le nombre de plats doit être un nombre entier")]
    #[Groups("plat")]
    private ?int $nbp = null;

    //#[ORM\OneToMany(mappedBy: 'plat', targetEntity: Like::class)]
   // private Collection $likes;
/*
    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }
*/
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }



    public function getCalories(): ?string
    {
        return $this->calories;
    }

    public function setCalories(string $calories): self
    {
        $this->calories = $calories;

        return $this;
    }



    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getCategories(): ?Menu
    {
        return $this->categories;
    }

    public function setCategories(?Menu $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

/*    public function getUserId(): ?string
    {
        return $this->UserId;
    }

    public function setUserId(string $UserId): self
    {
        $this->UserId = $UserId;

        return $this;
    }
*/
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function __toString()
    {
        if (is_null($this->id)) {
            return 'NULL';
        }
        return (string) $this->id;
    }

    public function getNbp(): ?int
    {
        return $this->nbp;
    }

    public function setNbp(?int $nbp): self
    {
        $this->nbp = $nbp;

        return $this;
    }
/*
    /**
     * @return Collection<int, Like>
     */
    /*
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setPlat($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPlat() === $this) {
                $like->setPlat(null);
            }
        }

        return $this;
    }
*/
}
