<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['users'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Veuillez saisir votre email')]
    #[Groups(['users'])]
    private ?string $email;

    #[ORM\Column(type: 'json')]
    #[Groups(['users'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['users'])]
    /**  #[Assert\NotBlank(message: 'Veuillez saisir votre mot de passe')]
     * #[Assert\Regex(pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{6,}$/', message: 'Le mot de passe doit contenir au moins 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial')]**/
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez saisir votre nom')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9]+$/', message: 'Le nom ne doit pas contenir d\'espace')]
    #[Groups(['users'])]
    private ?string $username = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez saisir votre numéro de téléphone')]
    #[Assert\Length(min: 8, max: 8, exactMessage: 'Le numéro de téléphone doit contenir 8 chiffres')]
    #[Assert\Regex(pattern: '/^(2|5|9)[0-9]{7}$/', message: 'Le numéro de téléphone doit commencer par 2 ou 5 ou 9 et contenir 8 chiffres')]
    #[Groups(['users'])]
    private ?int $numTel;
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\LessThan(value: "today", message: "Date Invalide !!")]
    #[Assert\NotBlank(message: 'Veuillez saisir votre date de naissance')]
    #[Groups(['users'])]
    private ?\DateTimeInterface $DateN = null;


    #[ORM\Column(type: "boolean")]
    private $isBlocked = false;
    #[ORM\Column(type: "boolean")]
    private $isApproved = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Abonnement::class)]
    private Collection $Abonnements;

  
    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->isApproved;
    }

    /**
     * @param bool $isApproved
     */
    public function setIsApproved(bool $isApproved): void
    {
        $this->isApproved = $isApproved;
    }

    #[ORM\Column(length: 255)]
    #[Groups(['users'])]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'CoachName', targetEntity: Cours::class)]
    private Collection $cours;

    #[ORM\ManyToMany(targetEntity: Reservation::class, mappedBy: 'Clients')]
    private Collection $reservations;

    #[ORM\ManyToMany(targetEntity: Competition::class, mappedBy: 'inscrit')]
    private Collection $competitions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class)]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class)]
    private Collection $comments;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->competitions = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }


  


    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER


        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getNumTel(): ?int
    {
        return $this->numTel;
    }

    public function setNumTel(int $numTel): self
    {
        $this->numTel = $numTel;

        return $this;
    }

    public function __toString()
    {
        return $this->id.' '.$this->username. ' ' .$this->email. ' ' .$this->numTel. ' ' .$this->image.' ' .$this->isBlocked.'     ' .$this->isApproved;
    }


    public function getDateN(): ?\DateTimeInterface
    {
        return $this->DateN;
    }

    public function setDateN(?\DateTimeInterface $DateN): self
    {
        $this->DateN = $DateN;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage(string $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->isBlocked;
    }

    /**
     * @param bool $isBlocked
     */
    public function setIsBlocked(bool $isBlocked): void
    {
        $this->isBlocked = $isBlocked;
    }


    /**
     * @return Collection<int, Abonnement>
     */
    public function getAbonnements(): Collection
    {
        return $this->Abonnements;
    }

    public function addAbonnement(Abonnement $abonnement): self
    {
        if (!$this->Abonnements->contains($abonnement)) {
            $this->Abonnements->add($abonnement);
            $abonnement->setUser($this);
        }

        return $this;
    }

    public function removeAbonnement(Abonnement $abonnement): self
    {
        if ($this->Abonnements->removeElement($abonnement)) {
            // set the owning side to null (unless already changed)
            if ($abonnement->getUser() === $this) {
                $abonnement->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): self
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setCoachName($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getCoachName() === $this) {
                $cour->setCoachName(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->addClient($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            $reservation->removeClient($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Competition>
     */
    public function getCompetitions(): Collection
    {
        return $this->competitions;
    }

    public function addCompetition(Competition $competition): self
    {
        if (!$this->competitions->contains($competition)) {
            $this->competitions->add($competition);
            $competition->addInscrit($this);
        }

        return $this;
    }

    public function removeCompetition(Competition $competition): self
    {
        if ($this->competitions->removeElement($competition)) {
            $competition->removeInscrit($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }
    


}
