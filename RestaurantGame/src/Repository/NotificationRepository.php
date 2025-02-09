<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function save(Notification $notification): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($notification);
        $entityManager->flush();
    }

    public function remove(Notification $notification): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($notification);
        $entityManager->flush();
    }

    public function findByUtilisateur(Utilisateur $utilisateur): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.utilisateur = :utilisateur')
            ->setParameter('utilisateur', $utilisateur)
            ->getQuery()
            ->getResult();
    }

    public function findUnreadByUtilisateur(Utilisateur $utilisateur): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.utilisateur = :utilisateur')
            ->andWhere('n.statut = :statut')
            ->setParameter('utilisateur', $utilisateur)
            ->setParameter('statut', 'non_lu')
            ->getQuery()
            ->getResult();
    }
}
