<?php

namespace App\Repository;

use App\Entity\Habitat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Habitat>
 *
 * @method Habitat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Habitat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Habitat[]    findAll()
 * @method Habitat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HabitatRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
      parent::__construct($registry, Habitat::class);
  }

  public function add(Habitat $entity, bool $flush = false): void
  {
      $this->getEntityManager()->persist($entity);

      if ($flush) {
          $this->getEntityManager()->flush();
      }
  }

  public function remove(Habitat $entity, bool $flush = false): void
  {
      $this->getEntityManager()->remove($entity);

      if ($flush) {
          $this->getEntityManager()->flush();
      }
  }

  public function isAvailable($nproduit, $date_debut, $date_fin)
    {
        $em = $this->_em;

        $query = $em->createQuery(
            'SELECT r
             FROM App\Entity\Reservation r
             WHERE r.nproduit = :nproduit
             AND (
                   (r.date <= :date_fin AND r.dateretour >= :date_debut)
                   OR (r.date <= :date_fin AND r.dateretour >= :date_fin)
                   OR (:date_debut <= r.date AND :date_fin >= r.date)
                   OR (:date_debut <= r.dateretour AND :date_fin >= r.dateretour)
                 )'
        )
        ->setParameters([
            'nproduit' => $nproduit,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
        ]);

        $reservations = $query->getResult();

        if (count($reservations) > 0) {
            return false;
        } else {
            return true;
        }
    }





//    /**
//     * @return Habitat[] Returns an array of Habitat objects
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

//    public function findOneBySomeField($value): ?Habitat
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
