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
    private ?string $category = null;

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

    #[Assert\NotBlank]
    private ?string $bannerDescription = null;

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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): void
    {
        $this->category = $category;
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

    public function setBestSelling(?int $bestSelling): void
    {
        $this->bestSelling = ($bestSelling === 1);
    }

    public function getBannerDescription(): ?string
    {
        return $this->bannerDescription;
    }

    public function setBannerDescription(string $bannerDescription): static
    {
        $this->bannerDescription = $bannerDescription;

        return $this;
    }

    /**
     * @param ImageDTO[] $images
     */
    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    public function getImages(): ?array
    {
        if (isset($this->images)) {
            return $this->images;
        }
        return null;
    }
}
