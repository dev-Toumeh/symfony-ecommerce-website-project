<?php

namespace App\Service;

use App\DTO\ImageDTO;
use App\Entity\Image;
use App\Entity\Product;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

readonly class ImageService
{
    public const SEPARATE = 'separate';
    private ImageRepository$imageRepository;

    public function __construct(private EntityManagerInterface $entityManager,
                                private ValidatorInterface     $validator)
    {
        $this->imageRepository = $this->entityManager->getRepository(Image::class);
    }

    public function insert(ImageDTO $imageDTO, Product $product, array $content = []): Image
    {
        $this->validateImage($imageDTO);

        $image = new Image();
        $image->setImageFilename($imageDTO->getImageFilename());
        $image->setBase64Image($imageDTO->getBase64Image());
        $image->setType($imageDTO->getType());
        $image->setProduct($product);
        $this->imageRepository->save($image, $content[self::SEPARATE]);

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

    /**
     * @param ImageDTO $imageDTO
     * @return void
     */
    public function validateImage(ImageDTO $imageDTO): void
    {
        if ($this->imageRepository->isNameUsed($imageDTO->getImageFilename())) {
            throw new ValidatorException('ImageFilename' . $imageDTO->getImageFilename() .' has been taken ');
        }

        $errors = $this->validator->validate($imageDTO);
        if (count($errors) > 0) {
            throw new ValidationFailedException('Validation failed for one or more images', $errors);
        }
    }
}
