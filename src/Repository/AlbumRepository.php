<?php

namespace App\Repository;

use App\Entity\Album;
use App\Entity\Artiste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Album|null find($id, $lockMode = null, $lockVersion = null)
 * @method Album|null findOneBy(array $criteria, array $orderBy = null)
 * @method Album[]    findAll()
 * @method Album[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Album::class);
    }

    public function albumsByArtist(Artiste $artiste)
    {
        return $this->createQueryBuilder('a')
            ->select('a.id', 'a.name', 'a.picture')
            ->where('a.artiste = :id')
            ->setParameter('id', $artiste->getId())
            ->getQuery()
            ->getResult()
        ;
    }

    public function albumArtist(Album $album, Artiste $artiste)
    {
        return $this->createQueryBuilder('a')
            ->where('a.artiste = :artid')
            ->andWhere('a.id = :albid')
            ->setParameters([
                'artid' => $artiste->getId(),
                'albid' => $album->getId()
            ])
            ->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return Album[] Returns an array of Album objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Album
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
