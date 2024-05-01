<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\AppConstants;
use App\Service\ProductService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/')]
    public function index(ProductService $productService): Response
    {
        try {
            $homePageData = $productService->getHomePageData();
            return $this->render(AppConstants::HOME_PAGE, $homePageData);
        } catch (Exception $e) {
            $this->addFlash('we have some Problems at the moment please come back Later', "");
            // add logger here
            return $this->redirectToRoute('app_home_error');
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


    #[Route('/error')]
    public function error(): Response
    {
            return $this->render(AppConstants::ERROR_PAGE);
    }
}