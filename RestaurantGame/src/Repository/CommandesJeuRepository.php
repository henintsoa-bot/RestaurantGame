<?php

namespace App\Repository;

use App\Entity\CommandesJeu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommandesJeuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandesJeu::class);
    }

    public function save(CommandesJeu $commandesJeu): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($commandesJeu);
        $entityManager->flush();
    }

    public function remove(CommandesJeu $commandesJeu): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($commandesJeu);
        $entityManager->flush();
    }

    public function findByCommande(Commande $commande): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.commande = :commande')
            ->setParameter('commande', $commande)
            ->getQuery()
            ->getResult();
    }
}
