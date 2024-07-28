<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param string $name
     */
    public function isNameUsed(string $name): bool
    {
        return $this->findOneBy([Product::NAME => $name]) !== null;
    }

    // Method to find start slider records
    public function getElementData(string $type): array
    {
        return $this->createQueryBuilder('p')
            ->setParameter('type', $type)
            ->select('p.bannerDescription, p.name, i.base64Image')
            ->join('p.images', 'i')
            ->where('i.type = :type')
            ->orderBy('RAND()')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    // Method to find categories
    public function getHomePageCategories(): array
    {
        return $this->createQueryBuilder('p')
            ->setParameter('type', 'category')
            ->select('p.category, i.base64Image')
            ->join('p.images', 'i')
            ->where('i.type = :type')
            ->orderBy('RAND()')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

  
public function getProProducts(string $basedOn = ""): array
{
    $qb = $this->createQueryBuilder('p')
        ->select("p.bestSelling, p.popularity, p.name, p.price, i.base64Image")
        ->join('p.images', 'i');

    if (!empty($basedOn)) {
        switch ($basedOn) {
            case 'best_selling':
                $qb->where('p.bestSelling = :bool')
                   ->setParameter('bool', 1);
                break;
            case 'quality':
                $qb->where('p.quality <= :bool')
                   ->setParameter('bool', 2);
                break;
            case 'popularity':
                $qb->where('p.popularity >= :bool')
                   ->setParameter('bool', 2);
                break;
            default:
                // Optional: handle the default case if needed
                break;
        }
    }

    return $qb->andWhere('i.type = :type')
              ->setParameter('type', 'pro')
              ->orderBy('RAND()')
              ->setMaxResults(4)
              ->getQuery()
              ->getResult();
}

    /**
     * @return void
     */
    public function save(Product $product): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($product);
        $entityManager->flush();
    }

    /**
     * @return QueryBuilder
     */
public function getShopPagePaginationQuery(string $view = 'grid'): QueryBuilder {
    $queryBuilder = $this->createQueryBuilder('p')
        ->innerJoin('p.images', 'i', 'WITH', 'i.type = :type')
        ->setParameter('type', 'pro');

    if ($view === 'list') {
        $queryBuilder->select('p.name, p.price, i.base64Image, p.bannerDescription, p.fakePrice, p.bestSelling, p.popularity ');
    } else {
        $queryBuilder->select('p.name, p.price, i.base64Image, p.bestSelling, p.popularity ');
    }

    return $queryBuilder->orderBy('RAND()');
}


}
