<?php

namespace App\Repository;

use App\Entity\GrupoAmigos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GrupoAmigos>
 *
 * @method GrupoAmigos|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrupoAmigos|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrupoAmigos[]    findAll()
 * @method GrupoAmigos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrupoAmigosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GrupoAmigos::class);
    }

//    /**
//     * @return GrupoAmigos[] Returns an array of GrupoAmigos objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GrupoAmigos
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
