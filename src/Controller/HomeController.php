<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/about')]
    public function aboutUs(): Response
    {
        return $this->render('about/about.html.twig');
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