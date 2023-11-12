<?php

namespace App\Repository;

use App\Entity\PrivateMessageResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PrivateMessageResponse>
 *
 * @method PrivateMessageResponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrivateMessageResponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrivateMessageResponse[]    findAll()
 * @method PrivateMessageResponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrivateMessageResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrivateMessageResponse::class);
    }

//    /**
//     * @return PrivateMessageResponse[] Returns an array of PrivateMessageResponse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PrivateMessageResponse
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
