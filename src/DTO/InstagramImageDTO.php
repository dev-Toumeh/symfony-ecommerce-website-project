<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class InstagramImageDTO
{
    #[Assert\NotNull(message: "The image URL must not be null.")]
    #[Assert\Url(message: "The provided URL '{{ value }}' is not a valid URL.", protocols: ['http', 'https'])]
    private ?string $imageUrl = null;

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @param string|null $imageUrl Valid URL of an Instagram image.
     */
    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }
}
