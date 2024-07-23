<?php

declare(strict_types=1);

namespace App\Controller;

use Exception;
use App\Constants\AppConstants;
use App\Service\ProductService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
  public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @return Response|RedirectResponse
     */
    #[Route('/')]
    public function index(ProductService $productService): Response|RedirectResponse 
    {
        try {
            $homePageData = $productService->getHomePageData();
            return $this->render(AppConstants::HOME_PAGE, $homePageData);
        } catch (Exception $e) {
            $this->logger->error($e);
            return $this->render(AppConstants::ERROR_PAGE, ['message' =>  "The website is currently unavailable. Please try again later."]);
        }
    }

    #[Route('/about')]
    public function aboutUs(): Response
    {
        return $this->render('admin/product.html.twig');
    }

    #[Route('/shop')]
    public function shop(): Response
    {
        return $this->render('shop/shop.html.twig');
    }

    #[Route('/gallery')]
    public function gallery(): Response
    {
        return $this->render('gallery/gallery.html.twig');
    }


    #[Route('/error', name: 'app_home_error')]
    public function error(string $message = null): Response
    {
        return $this->render(AppConstants::ERROR_PAGE);
    }
}
