<?php

namespace App\Repository;

use App\Entity\RegisteredUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RegisteredUser>
 *
 * @method RegisteredUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegisteredUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegisteredUser[]    findAll()
 * @method RegisteredUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegisteredUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegisteredUser::class);
    }

    public function save(RegisteredUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RegisteredUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Recherche des abonnés en fonction du formulaire
     *
     * @return void
     */
    public function search($words){
        $query = $this->createQueryBuilder('a');
        if($words != null){
            $query->where('MATCH_AGAINST(a.lastName, a.firstName) AGAINST (:words boolean)>0')
                ->setParameter('words',  '*' . $words . '*' );
        }

        return $query->getQuery()->getResult();
    }

//    /**
//     * @return RegisteredUser[] Returns an array of RegisteredUser objects
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

//    public function findOneBySomeField($value): ?RegisteredUser
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
