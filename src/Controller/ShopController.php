<?php

namespace App\Controller;
use App\Constants\AppConstants;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShopController extends AbstractController
{

  public function __construct(private LoggerInterface $logger,
    private ProductRepository $ProductRepository,
    private PaginatorInterface $paginator
  )
    {
    }

    /**
     * @return Response|RedirectResponse
     */
    #[Route('/shop')]
    public function index(Request $request): Response|RedirectResponse 
    {
    $queryGrid = $this->ProductRepository->getShopPagePaginationQuery();
    $queryList = $this->ProductRepository->getShopPagePaginationQuery("list");

    $paginationGridView = $this->paginator->paginate(
      $queryGrid, 
      $request->query->getInt('page', 1), 9 );
    $paginationListView = $this->paginator->paginate(
      $queryList, 
      $request->query->getInt('page', 1), 4 );
    //dd($pagination);
    return $this->render(AppConstants::SHOP_PAGE, [AppConstants::INSTAGRAM_THUMBNAILS_URLS => [],
                                                   'paginationGrid' => $paginationGridView,
                                                   'paginationList' => $paginationListView]);
    }
}
