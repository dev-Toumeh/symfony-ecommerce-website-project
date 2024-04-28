<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Image>
 *
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    public function isNameUsed($imageFilename): bool
    {
        return $this->findOneBy([Image::IMAGE_FILENAME => $imageFilename]) !== null;
    }

    public function save(Image $image, bool $flush = false): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($image);
        $flush ? $entityManager->flush(): null;
    }
}
