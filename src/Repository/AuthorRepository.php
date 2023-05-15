<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function save(Author $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Author $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Recherche des auteurs en fonction du formulaire
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

    

    /**
     * Pour ajouter le nom d'un auteur à partir d'aun Ajout de Livre via Google Books
     * Fais une recherche puis retourne l'entité trouvée
     */
    public function addFromGoogle($words){
        $query = $this->createQueryBuilder('a');
        if($words != null){
            $query->where('MATCH_AGAINST(a.lastName, a.firstName) AGAINST (:words boolean)>0')
                ->setParameter('words',  '*' . $words . '*' );
        }

        // dd($query->getQuery()->getResult());
        // dd($query->getQuery()->getResult()[0]);
        // return $query->getQuery()->getResult();
        if ($query->getQuery()->getResult()) {
            return $query->getQuery()->getResult()[0];
        }
    }

//    /**
//     * @return Author[] Returns an array of Author objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
