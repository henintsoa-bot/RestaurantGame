<?php

namespace App\Repository;

use App\Entity\EtatJeu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EtatJeuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EtatJeu::class);
    }

    public function save(EtatJeu $etatJeu): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($etatJeu);
        $entityManager->flush();
    }

    public function remove(EtatJeu $etatJeu): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($etatJeu);
        $entityManager->flush();
    }

    public function findByUtilisateur(Utilisateur $utilisateur): ?EtatJeu
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.utilisateur = :utilisateur')
            ->setParameter('utilisateur', $utilisateur)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
