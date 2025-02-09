<?php

namespace App\Repository;

use App\Entity\Recette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recette>
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class);
    }

    /**
     * Sauvegarde une recette dans la base de données.
     */
    public function save(Recette $recette): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($recette);
        $entityManager->flush();
    }

    /**
     * Supprime une recette de la base de données.
     */
    public function remove(Recette $recette): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($recette);
        $entityManager->flush();
    }

    /**
     * Trouve une recette par plat et ingrédient.
     *
     * @param Plat $plat
     * @param Ingredient $ingredient
     * @return Recette|null
     */
    public function findByPlatAndIngredient(Plat $plat, Ingredient $ingredient): ?Recette
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.plat = :plat')
            ->andWhere('r.ingredient = :ingredient')
            ->setParameter('plat', $plat)
            ->setParameter('ingredient', $ingredient)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Trouve toutes les recettes pour un plat donné.
     *
     * @param Plat $plat
     * @return Recette[]
     */
    public function findByPlat(Plat $plat): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.plat = :plat')
            ->setParameter('plat', $plat)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve toutes les recettes pour un ingrédient donné.
     *
     * @param Ingredient $ingredient
     * @return Recette[]
     */
    public function findByIngredient(Ingredient $ingredient): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.ingredient = :ingredient')
            ->setParameter('ingredient', $ingredient)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve toutes les recettes.
     *
     * @return Recette[]
     */
    public function findAllRecettes(): array
    {
        return $this->findBy([]);
    }
}
