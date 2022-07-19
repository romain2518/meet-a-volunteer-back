<?php

namespace App\Repository;

use App\Entity\ReceptionStructure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReceptionStructure>
 *
 * @method ReceptionStructure|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReceptionStructure|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReceptionStructure[]    findAll()
 * @method ReceptionStructure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReceptionStructureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReceptionStructure::class);
    }

    public function add(ReceptionStructure $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReceptionStructure $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ReceptionStructure[] Returns an array of ReceptionStructure objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReceptionStructure
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
