<?php

namespace App\Repository;

use App\Entity\Statistique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StatistiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statistique::class);
    }

    public function save(Statistique $statistique): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($statistique);
        $entityManager->flush();
    }

    public function remove(Statistique $statistique): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($statistique);
        $entityManager->flush();
    }

    public function findLatestStats(): ?Statistique
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.dateCreation', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
