<?php

namespace App\Repository;

use App\Entity\VolunteeringType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VolunteeringType>
 *
 * @method VolunteeringType|null find($id, $lockMode = null, $lockVersion = null)
 * @method VolunteeringType|null findOneBy(array $criteria, array $orderBy = null)
 * @method VolunteeringType[]    findAll()
 * @method VolunteeringType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VolunteeringTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VolunteeringType::class);
    }

    public function add(VolunteeringType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VolunteeringType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return VolunteeringType[] Returns an array of VolunteeringType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VolunteeringType
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
