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

    public function jsonSerialize(): mixed
    {
        return $this;
    }
}