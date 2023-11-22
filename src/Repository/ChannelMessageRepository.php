<?php

namespace App\Repository;

use App\Entity\ChannelMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChannelMessage>
 *
 * @method ChannelMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChannelMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChannelMessage[]    findAll()
 * @method ChannelMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChannelMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChannelMessage::class);
    }

//    /**
//     * @return ChannelMessage[] Returns an array of ChannelMessage objects
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

//    public function findOneBySomeField($value): ?ChannelMessage
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
