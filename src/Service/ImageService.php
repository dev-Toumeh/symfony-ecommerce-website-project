<?php

namespace App\Service;

use App\DTO\DTOInterface;
use App\DTO\ImageDTO;
use App\Entity\Image;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

readonly class ImageService
{
    public const SEPARATE = 'separate';

    public function __construct(private EntityManagerInterface $entityManager,
                                private ValidatorInterface     $validator)
    {
    }

    public function insert(ImageDTO $imageDTO, Product $product, array $content = []): Image
    {
        $productRepository = $this->entityManager->getRepository(Image::class);
        if ($productRepository->isNameUsed($imageDTO->getImageFilename())) {
            throw new ValidatorException('ImageFilename has been taken');
        }

        $errors = $this->validator->validate($imageDTO);
        if (count($errors) > 0) {
            throw new ValidationFailedException('Validation failed for one or more images', $errors);
        }

        $image = new Image();
        $image->setImageFilename($imageDTO->getImageFilename());
        $image->setType($imageDTO->getType());
        $image->setProduct($product);
        $this->entityManager->persist($image);

        if (isset($content[self::SEPARATE])) {
            $product->addImage($image);
            $this->entityManager->flush();
        }
        return $image;
    }


    /**
     * @param array $dtoImages
     * @param Product $product
     */
    public function insertImages(array $dtoImages, Product $product): void
    {
        if (!empty($dtoImages)) {
            foreach ($dtoImages as $image) {
                $this->insert($image, $product, [self::SEPARATE => true]);
            }
        }
    }
}
