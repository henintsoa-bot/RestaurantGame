<?php

namespace App\Repository;

use App\Entity\TokenFirebase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TokenFirebaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TokenFirebase::class);
    }

    public function save(TokenFirebase $tokenFirebase): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($tokenFirebase);
        $entityManager->flush();
    }

    public function remove(TokenFirebase $tokenFirebase): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($tokenFirebase);
        $entityManager->flush();
    }

    public function findByUtilisateur(Utilisateur $utilisateur): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.utilisateur = :utilisateur')
            ->setParameter('utilisateur', $utilisateur)
            ->getQuery()
            ->getResult();
    }

    public function findByToken(string $token): ?TokenFirebase
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
