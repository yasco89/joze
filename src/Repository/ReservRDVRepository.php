<?php

namespace App\Repository;

use App\Entity\ReservRDV;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReservRDV>
 *
 * @method ReservRDV|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservRDV|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservRDV[]    findAll()
 * @method ReservRDV[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservRDVRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservRDV::class);
    }

//    /**
//     * @return ReservRDV[] Returns an array of ReservRDV objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReservRDV
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
