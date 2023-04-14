<?php

namespace App\Repository;

use App\Entity\Fruits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fruits>
 *
 * @method Fruits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fruits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fruits[]    findAll()
 * @method Fruits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fruits::class);
    }

    public function save(Fruits $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fruits $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Fruits[] Returns an array of Fruits objects
     */
    public function searchByNameAndFamily($name = "", $family = ""): array
    {
        $query = $this->createQueryBuilder('f')
            ->andWhere('f.name LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->andWhere('f.family LIKE  :family')
            ->setParameter('family', '%'.$family.'%')
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery();

        return $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }

    public function searchFavourites(): array
    {
        $query = $this->createQueryBuilder('f')
            ->andWhere('f.favourite = true')
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery();

        return $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }
    
}
