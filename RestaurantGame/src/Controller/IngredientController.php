<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ingredient')]
class IngredientController extends AbstractController
{
    private $ingredientRepository;
    private $entityManager;

    public function __construct(IngredientRepository $ingredientRepository, EntityManagerInterface $entityManager)
    {
        $this->ingredientRepository = $ingredientRepository;
        $this->entityManager = $entityManager;
    }

    // Créer un nouvel ingrédient
    #[Route('/create', name: 'ingredient_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // Vérification des données requises
        if (!isset($data['nom'], $data['stock'])) {
            return $this->json(['error' => 'Nom et stock requis'], Response::HTTP_BAD_REQUEST);
        }

        // Création de l'ingrédient
        $ingredient = new Ingredient();
        $ingredient->setNom($data['nom']);
        $ingredient->setStock($data['stock']);
        $ingredient->setDateCreation(new \DateTime());
        $ingredient->setDateModification(new \DateTime());

        // Sauvegarde dans la base de données
        $this->entityManager->persist($ingredient);
        $this->entityManager->flush();

        return $this->json(['message' => 'Ingrédient créé avec succès'], Response::HTTP_CREATED);
    }

    // Modifier un ingrédient existant
    #[Route('/edit/{id}', name: 'ingredient_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): Response
    {
        // Trouver l'ingrédient
        $ingredient = $this->ingredientRepository->find($id);

        if (!$ingredient) {
            return $this->json(['error' => 'Ingrédient introuvable'], Response::HTTP_NOT_FOUND);
        }

        // Modifier les données de l'ingrédient
        $data = json_decode($request->getContent(), true);
        $ingredient->setNom($data['nom'] ?? $ingredient->getNom());
        $ingredient->setStock($data['stock'] ?? $ingredient->getStock());
        $ingredient->setDateModification(new \DateTime());

        // Sauvegarde des modifications
        $this->entityManager->flush();

        return $this->json(['message' => 'Ingrédient modifié avec succès']);
    }

    // Supprimer un ingrédient
    #[Route('/delete/{id}', name: 'ingredient_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        // Trouver l'ingrédient
        $ingredient = $this->ingredientRepository->find($id);

        if (!$ingredient) {
            return $this->json(['error' => 'Ingrédient introuvable'], Response::HTTP_NOT_FOUND);
        }

        // Supprimer l'ingrédient de la base de données
        $this->entityManager->remove($ingredient);
        $this->entityManager->flush();

        return $this->json(['message' => 'Ingrédient supprimé avec succès']);
    }

    // Lister tous les ingrédients
    #[Route('/list', name: 'ingredient_list', methods: ['GET'])]
    public function list(): Response
    {
        // Récupérer tous les ingrédients
        $ingredients = $this->ingredientRepository->findAll();
        return $this->json($ingredients);
    }
}
