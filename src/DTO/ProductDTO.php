<?php

namespace App\DTO;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class ProductDTO implements DTOInterface
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private ?string $type = null;

    #[Assert\Range(min: 1, max: 10)]
    private ?int $quality = null;

    #[Assert\NotNull]
    #[Assert\Type(type: 'float')]
    private ?float $price = null;

    #[Assert\Type(type: 'float')]
    private ?float $fakePrice = null;

    #[Assert\Range(min: 1, max: 10)]
    private ?int $popularity = null;

    #[Assert\Type(type: 'bool')]
    private ?bool $bestSelling = null;

    /**
     * @var ImageDTO[]
     */
    #[Assert\Valid]
    #[SerializedName("images")]
    private ?array $images;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getQuality(): ?int
    {
        return $this->quality;
    }

    public function setQuality(?int $quality): void
    {
        $this->quality = $quality;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    public function getFakePrice(): ?float
    {
        return $this->fakePrice;
    }

    public function setFakePrice(?float $fakePrice): void
    {
        $this->fakePrice = $fakePrice;
    }

    public function getPopularity(): ?int
    {
        return $this->popularity;
    }

    public function setPopularity(?int $popularity): void
    {
        $this->popularity = $popularity;
    }

    public function isBestSelling(): ?bool
    {
        return $this->bestSelling;
    }

    public function setBestSelling(?bool $bestSelling): void
    {
        $this->bestSelling = $bestSelling;
    }

    /**
     * @param ImageDTO[] $images
     */
    public function setImages(array $images): void {
        $this->images = $images;
    }

    public function getImages(): array {
        return $this->images;
    }

}
