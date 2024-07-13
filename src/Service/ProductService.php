<?php

namespace App\Service;

use Exception;
use App\Entity\Image;
use App\DTO\ProductDTO;
use App\Entity\Product;
use Psr\Log\LoggerInterface;
use App\Constants\AppConstants;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ProductService
{
    private ProductRepository $productRepository;
    private ImageRepository $imageRepository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface     $validator,
        private readonly ImageService $imageService,
        private LoggerInterface $logger
    ) {
        $this->productRepository = $this->entityManager->getRepository(Product::class);
        $this->imageRepository = $this->entityManager->getRepository(Image::class);
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

    /**
     * @return array<string,array>
     * @throws \Exception
     */
    public function getHomePageData(): array
    {
        try {
            return [
                AppConstants::START_SLIDER => $this->productRepository->findStartSliderRecords(),
                AppConstants::CATEGORIES => $this->productRepository->findCategories(),
                AppConstants::ADVERTISES => $this->imageRepository->getAdvertisingImages(),
                AppConstants::PRO => $this->adjustProProduct()
            ];
        } catch (\Exception $e) {
            $this->logger->error($e);
            throw new Exception('Error fetching home page data');
        }
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


    private function adjustProProduct(): array
   {
        $products = $this->productRepository->getProProducts();
        $bestSellersCount = 0;
        $popularCount = 0;

        if (!empty($products)) {
            foreach ($products as $index => $product) {
                if ($product['bestSelling'] && $bestSellersCount < 4) {
                    $products[$index]['type'] = 'best-seller';
                    $bestSellersCount++;
                } elseif ($product['popularity'] <= 2 && $popularCount < 4) {
                    $products[$index]['type'] = 'popular';
                    $popularCount++;
                } else {
                    $products[$index]['type'] = '';
                }
            }
        }
        return $products;
    }
}
