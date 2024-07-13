<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function findStartSliderRecords(): array
    {
        return $this->createQueryBuilder('p')
            ->setParameter('type', 'banner')
            ->select('p.bannerDescription, p.name, i.base64Image')
            ->join('p.images', 'i')
            ->where('i.type = :type')
            ->orderBy('RAND()')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    // Method to find categories
    public function findCategories(): array
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

    public function getBestSellerProducts(): array
    {
        return $this->createQueryBuilder('p')
            ->select(Product::BEST_SELLER)
            ->join('p.images', 'i')
            ->where('i.type = :type')
            ->orderBy('RAND()')
            ->setMaxResults(4)
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
                $qb->where('p.best_selling = :bool')
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

    public function save(Product $product): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($product);
        $entityManager->flush();
    }
}
