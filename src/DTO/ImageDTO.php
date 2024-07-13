<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ImageDTO implements DTOInterface
{
    #[Assert\NotBlank(message: "Image filename cannot be blank.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "The image filename cannot be longer than {{ limit }} characters."
    )]
    private ?string $imageFilename = null;

    #[Assert\NotBlank(message: "Image type cannot be blank.")]
    #[Assert\Length(
        max: 100,
        maxMessage: "The image type cannot be longer than {{ limit }} characters."
    )]
    private ?string $type = null;

    private ?string $base64Image;

    #[Assert\NotBlank(message: "productID cannot be blank.")]
    private ?string $productId;

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(string $imageFilename): static
    {
        $this->imageFilename = $imageFilename;

        return $this;
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

    public function getBase64Image(): ?string
    {
        return $this->base64Image;
    }

    public function setBase64Image(?string $base64Image): self
    {
        $this->base64Image = $base64Image;
        return $this;
    }
    public function getProductId( ): ?string
    {
        return   $this->productId;
    }

    public function setProductId(string $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getProductIdFromDTOImages($dtoImages): ?string
    {
        if (!empty($dtoImages)) {
            foreach ($dtoImages as $image) {
                $image->getProductId();
            }
        }
    }
}