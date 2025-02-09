<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    /**
     * @return Ingredient[] Returns an array of Ingredient objects
     */
    public function findByStockThreshold(int $threshold): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.stock <= :threshold')
            ->setParameter('threshold', $threshold)
            ->orderBy('i.stock', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find an ingredient by its name
     */
    public function findOneByName(string $name): ?Ingredient
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.nom = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
