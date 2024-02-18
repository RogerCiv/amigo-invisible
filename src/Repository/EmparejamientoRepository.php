<?php

namespace App\Repository;

use App\Entity\Emparejamiento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emparejamiento>
 *
 * @method Emparejamiento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emparejamiento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emparejamiento[]    findAll()
 * @method Emparejamiento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmparejamientoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emparejamiento::class);
    }

//    /**
//     * @return Emparejamiento[] Returns an array of Emparejamiento objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Emparejamiento
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
