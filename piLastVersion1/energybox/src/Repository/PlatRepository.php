<?php

namespace App\Repository;

use App\Entity\Plat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Plat>
 *
 * @method Plat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plat[]    findAll()
 * @method Plat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plat::class);
    }

    public function save(Plat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function RechercheMobile($prix) {
        $qb = $this->createQueryBuilder('p')
            ->where('p.prix LIKE :prix')
            ->setParameters([
                'prix' => $prix,
            ]);
        return $qb->getQuery()->getResult();
    }

    public function remove(Plat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Plat[] Returns an array of Plat objects
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

//    public function findOneBySomeField($value): ?Plat
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    public function filtrage($categories)
    {
        $em = $this->getEntityManager();
        $q = $em->createQueryBuilder();
        $q->select('p', 'm')
            ->from('App\Entity\Plat', 'p')
            ->andWhere('p.categories = :categories')
         //   ->groupBy('p.categories')
            ->join('p.categories', 'm')
            ->setParameter('categories', $categories);
        return $q->getQuery()->getResult();
    }

    public function orderByPrixASC()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.prix', 'ASC')
            ->getQuery()->getResult();
    }
    public function orderByPrixDESC()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.prix', 'DESC')
            ->getQuery()->getResult();
    }
    public function orderByNomASC()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nom', 'ASC')
            ->getQuery()->getResult();
    }
    public function orderByNomDESC()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nom', 'DESC')
            ->getQuery()->getResult();
    }
    public function orderByNombreASC()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nbp', 'ASC')
            ->getQuery()->getResult();
    }
    public function orderByNombreDESC()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nbp', 'DESC')
            ->getQuery()->getResult();
    }

//RechercheAjax
/*
    public function findPlatByName($nom){
        $qb = $this->createQueryBuilder("p")
            ->where('p.nom LIKE :nom')
            ->setParameter('nom', '%'.$nom.'%');
            return $qb->getQuery()->getResult();
    }
    */
    public function findPlatByName($nom, $page = 1, $limit = 10)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.nom LIKE :nom')
            ->setParameter('nom', '%' . $nom . '%')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);
        return $qb->getQuery()->getResult();
    }

  public function findPlatByNameFront($nom, $categories) {
        $qb = $this->createQueryBuilder('p')
            ->where('p.nom LIKE :nom')
            ->andWhere('p.categories = :categories')
            ->setParameters([
                'nom' => '%'.$nom.'%',
                'categories' => $categories,
            ]);
        return $qb->getQuery()->getResult();
    }



}

