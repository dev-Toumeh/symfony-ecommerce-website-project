<?php

namespace App\Entity;

use App\Repository\PaymentMethodRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PaymentMethodRepository::class)]
class PaymentMethod
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 100)]
    private ?string $type = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $NameOnCard = null;

    #[ORM\Column(nullable: true)]
    private ?int $creditCardNumber = null;

    #[ORM\Column(nullable: true)]
    private ?int $cvv = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $Expiration = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getNameOnCard(): ?string
    {
        return $this->NameOnCard;
    }

    public function setNameOnCard(?string $NameOnCard): static
    {
        $this->NameOnCard = $NameOnCard;

        return $this;
    }

    public function getCreditCardNumber(): ?int
    {
        return $this->creditCardNumber;
    }

    public function setCreditCardNumber(?int $creditCardNumber): static
    {
        $this->creditCardNumber = $creditCardNumber;

        return $this;
    }

    public function getCvv(): ?int
    {
        return $this->cvv;
    }

    public function setCvv(?int $cvv): static
    {
        $this->cvv = $cvv;

        return $this;
    }

    public function getExpiration(): ?string
    {
        return $this->Expiration;
    }

    public function setExpiration(?string $Expiration): static
    {
        $this->Expiration = $Expiration;

        return $this;
    }
}
