<?php

namespace App\Service;

use App\Constants\AppConstants;
use App\DTO\ProductDTO;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface     $validator,
        private readonly ImageService $imageService
    )
    {
        $this->productRepository = $this->entityManager->getRepository(Product::class);
    }

    public function insert(ProductDTO $productDTO): void
    {
        $this->validate($productDTO);

        $product = new Product();
        $product->setName($productDTO->getName());
        $product->setCategory($productDTO->getCategory());
        $product->setQuality($productDTO->getQuality());
        $product->setPrice($productDTO->getPrice());
        $product->setFakePrice($productDTO->getFakePrice());
        $product->setPopularity($productDTO->getPopularity());
        $product->setBestSelling($productDTO->isBestSelling());
        $product->setBannerDescription($productDTO->getBannerDescription());
        if ($productDTO->getImages() !== null) {
            foreach ($productDTO->getImages() as $image) {
                $imageEntity = $this->imageService->insert($image, $product);
                $product->addImage($imageEntity);
            }
        }
        $this->productRepository->save($product);
    }

    public function getHomePageData(): array
    {
        return [
            AppConstants::START_SLIDER =>  $this->productRepository->findStartSliderRecords(),
            AppConstants::CATEGORIES =>  $this->productRepository->findCategories()
        ];
    }

    /**
     * @param ProductDTO $productDTO
     * @return void
     */
    private function validate(ProductDTO $productDTO): void
    {
        if ($this->productRepository->isNameUsed($productDTO->getName())) {
            throw new ValidatorException('Product name' . $productDTO->getName() . 'has been taken');
        }
        $errors = $this->validator->validate($productDTO);
        if (count($errors) > 0) {
            throw new ValidationFailedException('Validation failed', $errors);
        }
    }
}

