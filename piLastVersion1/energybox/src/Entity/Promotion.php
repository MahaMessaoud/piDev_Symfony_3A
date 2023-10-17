<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PromotionRepository::class)]
class Promotion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("promotions")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank( message: "Le code promo est vide !" )]
    #[Groups("promotions")]
    private ?string $codePromotion = null;

    #[ORM\Column]
    #[Assert\NotBlank( message: "La réduction est vide !" )]
    #[Assert\Positive( message: "La réduction doit etre positive!"   )]
    #[Groups("promotions")]
    private ?float $reductionPromotion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank( message: "Le date d expiration est vide !" )]
    #[Assert\GreaterThanOrEqual("now", message :"La date doit être postérieure à la date et à l'heure actuelles.")]
    #[Groups("promotions")]
    private ?\DateTimeInterface $DateExpiration = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function getCodePromotion(): ?string
    {
        return $this->codePromotion;
    }

    public function setCodePromotion(string $codePromotion): self
    {
        $this->codePromotion = $codePromotion;

        return $this;
    }

    public function getReductionPromotion(): ?float
    {
        return $this->reductionPromotion;
    }

    public function setReductionPromotion(float $reductionPromotion): self
    {
        $this->reductionPromotion = $reductionPromotion;

        return $this;
    }

    public function getDateExpiration(): ?\DateTimeInterface
    {
        return $this->DateExpiration;
    }

    public function setDateExpiration(\DateTimeInterface $DateExpiration): self
    {
        $this->DateExpiration = $DateExpiration;

        return $this;
    }
}
