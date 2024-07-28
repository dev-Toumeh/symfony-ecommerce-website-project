<?php

namespace App\Service;

use Exception;
use App\Entity\Image;
use App\DTO\ProductDTO;
use App\Entity\Product;
use Psr\Log\LoggerInterface;
use App\Constants\AppConstants;
use App\Http\InstagramApiClient;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Serializer\DTOSerializerInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ProductService
{
    private ProductRepository $productRepository;
    private ImageRepository $imageRepository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface     $validator,
        private readonly ImageService $imageService,
        private readonly DTOSerializerInterface $serializer,
        private LoggerInterface $logger,
        private InstagramApiClient $InstagramApiClient,
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
                AppConstants::START_SLIDER => $this->productRepository->getElementData(AppConstants::BANNER),
                AppConstants::CATEGORIES => $this->productRepository->getHomePageCategories(),
                AppConstants::ADVERTISES => $this->imageRepository->getAdvertisingImages(),
                AppConstants::BLOGS => $this->productRepository->getElementData(AppConstants::BLOG),
                AppConstants::PRO => $this->proProductHandler(),
                AppConstants::INSTAGRAM_THUMBNAILS_URLS => $this->InstagramImagesHandler(),
            ];
        } catch (Exception $e) {
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

    private function proProductHandler(): array
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

    /**
     * Fetches and validates user Instagram images, returning valid URLs or an empty array on errors.
     * @return array An array of valid Instagram image URLs, or an empty array on error.
     */
    private function InstagramImagesHandler(): array
    {
        $response = $this->InstagramApiClient->fetchInstagramData('mrbeast');
        if ($response->getStatusCode() !== JsonResponse::HTTP_OK) {
            $this->logger->error($response->getContent());
            return [];
        }

        $instagramImages = $this->serializer->deserialize($response->getContent(), 'App\\DTO\\InstagramImageDTO[]', 'json');
        $validUrls = [];
        foreach ($instagramImages as $imageDTO) {
            $errors = $this->validator->validate($imageDTO);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }
                $this->logger->error(implode(', ', $errorMessages));
            } else {
                $validUrls[] = $imageDTO->getImageUrl();
            }
        }
        return $validUrls;
    }
}
