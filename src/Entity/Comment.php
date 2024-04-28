<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $reviewerName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $review = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(name: "product_id", referencedColumnName: "id", nullable: false)]
    private ?Product $product = null;


    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getReviewerName(): ?string
    {
        return $this->reviewerName;
    }

    public function setReviewerName(?string $reviewerName): static
    {
        $this->reviewerName = $reviewerName;

        return $this;
    }

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function setReview(string $review): static
    {
        $this->review = $review;

        return $this;
    }

    public function getProduct(): ?Product {
        return $this->product;
    }

    public function setProduct(?Product $product): self {
        $this->product = $product;
        return $this;
    }

}
