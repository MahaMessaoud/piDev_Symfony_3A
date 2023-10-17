<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("posts")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 5,
        max: 15,minMessage: "le titre su post doit contenir au moin {{ limit }} caractères",maxMessage: "le titre su post doit contenir au plus {{ limit }} caractères",
    )]
    #[Groups("posts")]
    private ?string $title = null;

    #[ORM\Column(length: 500)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 10,
        max: 150,
        minMessage: "le titre su post doit contenir au moin {{ limit }} caractères",
        maxMessage: "le titre su post doit contenir au plus {{ limit }} caractères",
    )]
    #[Groups("posts")]
    private ?string $details = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups("posts")]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("posts")]
    private ?\DateTimeInterface $datePost = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class)]
    //#[Groups("posts")]
    private Collection $comments;

    #[ORM\Column(nullable: true)]
    private ?int $rate = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDatePost(): ?\DateTimeInterface
    {
        return $this->datePost;
    }

    public function setDatePost(\DateTimeInterface $datePost): self
    {
        $this->datePost = $datePost;

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
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
return $this->getTitle();
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(?int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
