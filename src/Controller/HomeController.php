<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\AppConstants;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/')]
    public function index(ProductService $productService,
    ): Response
    {
        $test = $productService->getStartSliderdata();
        return $this->render(AppConstants::HOME_PAGE,
            [AppConstants::START_SLIDER => $productService->getStartSliderdata()]
        );
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


}