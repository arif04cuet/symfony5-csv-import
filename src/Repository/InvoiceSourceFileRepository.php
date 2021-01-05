<?php

namespace App\Repository;

use App\Entity\InvoiceSourceFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InvoiceSourceFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvoiceSourceFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvoiceSourceFile[]    findAll()
 * @method InvoiceSourceFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceSourceFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvoiceSourceFile::class);
    }

    // /**
    //  * @return InvoiceSourceFile[] Returns an array of InvoiceSourceFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InvoiceSourceFile
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
