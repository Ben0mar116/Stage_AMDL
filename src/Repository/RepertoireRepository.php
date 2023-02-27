<?php

namespace App\Repository;

use App\Entity\Repertoire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Repertoire>
 *
 * @method Repertoire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Repertoire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Repertoire[]    findAll()
 * @method Repertoire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepertoireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Repertoire::class);
    }

    public function add(Repertoire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Repertoire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Repertoire[] Returns an array of Repertoire objects
//     */
   public function findtop($value): array
   {
       return $this->createQueryBuilder('r')
           ->andWhere('r.user = :val')
           ->setParameter('val', $value)
           ->orderBy('r.dateCreation', 'ASC')
           ->setMaxResults(4)
           ->getQuery()
           ->getResult()
       ;
   }

   public function findOneBySomeField($value): ?Repertoire
   {
       return $this->createQueryBuilder('r')
           ->andWhere('r.id = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
