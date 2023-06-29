<?php

namespace App\Repository;

use App\Entity\RaspProject;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RaspProject>
 *
 * @method RaspProject|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaspProject|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaspProject[]    findAll()
 * @method RaspProject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaspProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaspProject::class);
    }

    public function save(RaspProject $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RaspProject $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByAuthorizatedUser(User $user): mixed
    {
        return $this->createQueryBuilder('r')
            ->andWhere(':user MEMBER OF r.authorizedUsers')
            ->setParameter('user', $user)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return RaspProject[] Returns an array of RaspProject objects
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

//    public function findOneBySomeField($value): ?RaspProject
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
