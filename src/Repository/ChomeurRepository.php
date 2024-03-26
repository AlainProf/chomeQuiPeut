<?php

namespace App\Repository;

use App\Entity\Chomeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chomeur>
 *
 * @method Chomeur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chomeur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chomeur[]    findAll()
 * @method Chomeur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChomeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chomeur::class);
    }

    public function CountEntities()
    {
        return $this->count([]);
    }


//    /**
//     * @return Chomeur[] Returns an array of Chomeur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Chomeur
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
