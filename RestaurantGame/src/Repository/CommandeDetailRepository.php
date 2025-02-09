<?php

namespace App\Repository;

use App\Entity\CommandeDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommandeDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeDetail::class);
    }

    public function save(CommandeDetail $commandeDetail): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($commandeDetail);
        $entityManager->flush();
    }

    public function remove(CommandeDetail $commandeDetail): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($commandeDetail);
        $entityManager->flush();
    }

    public function findByCommande(Commande $commande): array
    {
        return $this->createQueryBuilder('cd')
            ->andWhere('cd.commande = :commande')
            ->setParameter('commande', $commande)
            ->getQuery()
            ->getResult();
    }
}
