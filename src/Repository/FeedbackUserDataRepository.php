<?php

namespace App\Repository;

use App\Entity\FeedbackUserData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FeedbackUserData|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeedbackUserData|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeedbackUserData[]    findAll()
 * @method FeedbackUserData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedbackUserDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeedbackUserData::class);
    }

    // /**
    //  * @return FeedbackUserData[] Returns an array of FeedbackUserData objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FeedbackUserData
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
