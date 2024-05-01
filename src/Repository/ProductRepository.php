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

    public function findImages($type, $fields, $maxResults = 3) {
        return $this->createQueryBuilder('p')
            ->setParameter('type', $type)
            ->select($fields)
            ->join('p.images', 'i')
            ->where('i.type = :type')
            ->orderBy('RAND()')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }

    public function findStartSliderRecords() {
        $fields = 'p.bannerDescription, p.name, i.base64Image';
        return $this->findImages('banner', $fields);
    }

    public function findCategories() {
        $fields = 'p.category, i.base64Image';
        return $this->findImages('category', $fields);
    }

    public function save(Product $product): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($product);
        $entityManager->flush();
    }
}
