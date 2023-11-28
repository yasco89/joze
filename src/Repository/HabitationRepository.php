<?php

namespace App\Repository;

use App\Entity\Habitation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Habitation>
 *
 * @method Habitation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Habitation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Habitation[]    findAll()
 * @method Habitation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HabitationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Habitation::class);
    }

//    /**
//     * @return Habitation[] Returns an array of Habitation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Habitation
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
