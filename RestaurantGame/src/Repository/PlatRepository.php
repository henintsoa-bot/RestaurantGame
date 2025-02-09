<?php

namespace App\Repository;

use App\Entity\Plat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Plat>
 */
class PlatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plat::class);
    }

    /**
     * Sauvegarde un plat dans la base de données.
     */
    public function save(Plat $plat): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($plat);
        $entityManager->flush();
    }

    /**
     * Supprime un plat de la base de données.
     */
    public function remove(Plat $plat): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($plat);
        $entityManager->flush();
    }

    /**
     * Trouve un plat par son nom.
     *
     * @param string $nom
     * @return Plat|null
     */
    public function findByNom(string $nom): ?Plat
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nom = :nom')
            ->setParameter('nom', $nom)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Trouve tous les plats créés avant une certaine date.
     *
     * @param \DateTimeInterface $date
     * @return Plat[]
     */
    public function findCreatedBefore(\DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.dateCreation < :date')
            ->setParameter('date', $date)
            ->orderBy('p.dateCreation', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve tous les plats dans la base de données.
     *
     * @return Plat[]
     */
    public function findAllPlats(): array
    {
        return $this->findBy([]);
    }
}
