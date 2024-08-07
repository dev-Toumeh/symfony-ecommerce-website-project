<?php

namespace App\Controller;

use Exception;
use App\DTO\ProductDTO;
use App\Entity\Product;
use App\Service\ImageService;
use App\Service\ProductService;
use App\Constants\AppConstants;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Serializer\DTOSerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JsonController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DTOSerializerInterface $serializer,
    )
    {}

    #[Route('/add-product', name: 'add-product', methods: ['POST'])]
    public function addProduct(Request        $request,
                               ProductService $productService): JsonResponse
    {

        $productDTO = $this->serializer->deserialize(
            $request->getContent(),
            ProductDTO::class,
            AppConstants::JSON);

        $productService->insert($productDTO);
        return new JsonResponse(['success' => true, 'message' => 'Product added successfully'], Response::HTTP_CREATED);
    }

    #[Route('/add-image', name: 'add-image', methods: ['POST'])]
    public function addImage(Request      $request,
                             ImageService $imageService): JsonResponse
    {
        try {
            $imageDTOArray = $this->serializer->deserialize(
                $request->getContent(),
                AppConstants::IMAGE_TDO_ARRAY,
                AppConstants::JSON);

            $ProductId = $imageDTOArray[0]->getProductId();
           $product = $this->entityManager->getRepository(Product::class)->find($ProductId);
            $imageService->insertImages($imageDTOArray, $product);

            return new JsonResponse(['success' => true, 'message' => 'Image ws added successfully'], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/test', name: 'test', methods: ['GET'])]
    public function test(Request $request): JsonResponse
    {
        $product = $this->entityManager->getRepository(Product::class)->findStartSliderRecords();
        return new JsonResponse(['success' => true, 'message' => 'life is good'], Response::HTTP_OK);
    }

}
