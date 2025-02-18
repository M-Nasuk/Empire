<?php

namespace App\Repository;

use App\Entity\Album;
use App\Entity\Artiste;
use App\Entity\Track;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Track|null find($id, $lockMode = null, $lockVersion = null)
 * @method Track|null findOneBy(array $criteria, array $orderBy = null)
 * @method Track[]    findAll()
 * @method Track[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Track::class);
    }

    public function tracksByArtist(Artiste $artiste)
    {
        return $this->createQueryBuilder('t')
            ->select('t.id', 't.name')
            ->where('t.artiste = :id')
            ->setParameter('id', $artiste->getId())
            ->getQuery()
            ->getResult()
            ;
    }

    public function tracksByAlbum(Album $album)
    {
        return $this->createQueryBuilder('t')
            ->select('t.id', 't.name')
            ->where('t.album = :id')
            ->setParameter('id', $album->getId())
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return Track[] Returns an array of Track objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Track
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
