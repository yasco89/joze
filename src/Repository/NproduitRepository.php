<?php

namespace App\Repository;

use App\Entity\Nproduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Nproduit>
 *
 * @method Nproduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nproduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nproduit[]    findAll()
 * @method Nproduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NproduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nproduit::class);
    }

//    /**
//     * @return Nproduit[] Returns an array of Nproduit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Nproduit
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
