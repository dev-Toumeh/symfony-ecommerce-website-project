<?php

namespace App\Service;

use App\DTO\DTOInterface;
use App\DTO\ProductDTO;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class ProductService
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ValidatorInterface     $validator,
        private ImageService           $imageService
    )
    {
    }

    public function insert(ProductDTO $productDTO): void
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        if($productRepository->isNameUsed($productDTO->getName())) {
            throw new ValidatorException('name has been taken');
        }
        $errors = $this->validator->validate($productDTO);
        if (count($errors) > 0) {
            throw new ValidationFailedException('Validation failed', $errors);
        }

        $product = new Product();
        $product->setName($productDTO->getName());
        $product->setType($productDTO->getType());
        $product->setQuality($productDTO->getQuality());
        $product->setPrice($productDTO->getPrice());
        $product->setFakePrice($productDTO->getFakePrice());
        $product->setPopularity($productDTO->getPopularity());
        $product->setBestSelling($productDTO->isBestSelling());
        if ($productDTO->getImages() !== null) {
            foreach ($productDTO->getImages() as $image) {
                $imageEntity = $this->imageService->insert($image, $product, [true]);
                $product->addImage($imageEntity);
            }
        }
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }
}

