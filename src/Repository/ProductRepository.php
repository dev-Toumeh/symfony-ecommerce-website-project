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

    public function isNameUsed($name): bool
    {
        return $this->findOneBy([Product::NAME => $name]) !== null;
    }

    public function findStartSliderRecords() {
        return $this->createQueryBuilder('p')
            ->select('p.bannerDescription, p.name, i.base64Image ')
            ->join('p.images', 'i')
            ->orderBy('RAND()')
            ->setMaxResults(3)
            ->where("i.type = 'banner'")
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
